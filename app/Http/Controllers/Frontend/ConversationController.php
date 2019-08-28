<?php

namespace App\Http\Controllers\Frontend;

use App\Events\ConversationRemoved;
use App\Models\Conversation;
use App\Models\ConversationMember;
use App\Models\Ind;
use App\Models\Org;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ConversationController extends Controller
{
    public function index()
    {
        return view('frontend.chat.index');
    }

    public function getAccounts(Request $request)
    {
        $user = $request->user();
        $photo = asset('storage/' . $user->photo);

        // Get all the individual service accounts
        $inds = array_map(
            function ($service) use ($photo) {
                $service['type'] = 'ind';
                $service['photo'] = $photo;
                return $service;
            }, Ind::onlyApproved()
            ->select('inds.id', 'categories.name as name')
            ->where('user_id', $user->id)->join('categories', 'categories.id', '=', 'inds.category_id')
            ->get()
            ->toArray()
        );

        // Get all the organization service accounts
        $orgs = array_map(function ($service) {
            $service['type'] = 'org';
            $service['photo'] = asset('storage/' . $service['photo']);
            return $service;
        }, Org::onlyApproved()->where('user_id', $user->id)->select('id', 'name', 'logo as photo')->get()->toArray());

        // Merge organizations and individuals
        $accounts = array_merge($inds, $orgs);

        // Push user account
        array_push($accounts, [
            'id' => $user->id,
            'name' => $user->name,
            'photo' => $photo,
            'type' => 'user'
        ]);

        return $accounts;
    }

    public function getConversations(Request $request)
    {

        // Validate request
        $request->validate([
            'type' => 'in:ind,org,user',
            'id' => 'integer'
        ]);

        $type = $request->input('type', 'user');
        $id = $request->input('id', Auth::id());

        $conversations = DB::table('conversation_members')
            ->select('id', 'conversation_id as cid')
            ->where('user_id', Auth::id())
            ->where('memberable_type', $type)
            ->where('memberable_id', $id)
            ->paginate()
            ->items();

        $conversationsIds = array_map(function ($conversation) {
            return $conversation->cid;
        }, $conversations);

        $ids = array_map(function ($conversation) {
            return $conversation->id;
        }, $conversations);

        $members = DB::table('conversation_members')
            ->select('id', 'conversation_id as cid', 'memberable_type', 'memberable_id')
            ->whereIn('conversation_id', $conversationsIds)
            ->whereNotIn('id', $ids)
            ->get();

        $data = [
            'ind' => [
                'ids' => [],
                'rows' => []
            ],
            'org' => [
                'ids' => [],
                'rows' => []
            ],
            'user' => [
                'ids' => [],
                'rows' => []
            ]
        ];

        foreach ($members as $member) {
            array_push($data[$member->memberable_type]['ids'], $member->memberable_id);
        }

        // Load users
        $data['user']['rows'] = User::query()
            ->select('id', 'name', 'photo')
            ->whereIn('id', $data['user']['ids'])
            ->get();

        // Load inds
        $data['ind']['rows'] = DB::table('inds')
            ->select('inds.id', 'categories.name as name', 'photo')
            ->whereIn('inds.id', $data['ind']['ids'])
            ->join('categories', 'categories.id', '=', 'inds.category_id')
            ->join('users', 'users.id', '=', 'inds.user_id')
            ->get();

        // Load orgs
        $data['org']['rows'] = Org::whereIn('id', $data['org']['ids'])->select('id', 'name', 'logo as photo')->get();


        return array_map(function ($member) use ($data, $conversations) {
            $row = $data[$member->memberable_type]['rows']->where('id', $member->memberable_id)->first();
            $cid = $member->cid;

            $mid = array_first($conversations, function ($value) use ($cid) {
                return $value->cid == $cid;
            })->id;

            return [
                'id' => $cid,
                'mid' => $mid,
                'member' => [
                    'id' => $member->id,
                    'userId' => $member->memberable_id,
                    'type' => $member->memberable_type,
                    'name' => $row->name,
                    'photo' => asset('storage/' . $row->photo)
                ]
            ];
        }, $members->toArray());
    }

    public function store(Request $request)
    {

        // Validate request
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|in:ind,org,user',
            'target' => 'required|integer',
            'targetType' => 'required|in:ind,org,user'
        ]);


        // Authorize
        if (Gate::denies('create-conversation', $request)) {
            return response('', 401);
        }

        // Check existence of the target
        $models = [
            'ind' => Ind::class,
            'org' => Org::class
        ];

        $targetType = $request->input('targetType');
        $target = $request->input('target');
        $type = $request->input('type');
        $id = $request->input('id');

        $targetUserId = null;

        if ($targetType === 'user') {
            $user = User::query()
                ->select('id')
                ->where('id', $target)
                ->limit(1)
                ->first();

            if (!$user) {
                return response('', 422);
            }

            $targetUserId = $user->id;
        } else {
            $targetModel = $models[$targetType];
            $service = $targetModel::onlyApproved()
                ->select('user_id')
                ->where('id', $target)
                ->limit(1)
                ->first();

            if (!$service) {
                return response('', 422);
            }

            $targetUserId = $service->user_id;
        }

        // Check whether the user has already a conversation with the target
        $conversationExists = DB::table('conversation_members as members')
            ->select(
                'members.conversation_id'
            )
            ->join(
                'conversation_members as me',
                'members.conversation_id',
                '=',
                'me.conversation_id'
            )
            ->where('me.memberable_id', '=', $id)
            ->where('me.memberable_type', '=', $type)
            ->where('members.memberable_id', '=', $target)
            ->where('members.memberable_type', '=', $targetType)
            ->limit(1)
            ->exists();

        if ($conversationExists) {
            return response('', 422);
        }

        DB::beginTransaction();
        $conversation = new Conversation;
        $conversation->save();

        // The user who is requesting to open a conversation
        $member1 = $this->createMember($conversation->id, $type, $id, Auth::id());

        // Target user
        $member2 = $this->createMember($conversation->id, $targetType, $target, $targetUserId);


        $namePhoto = null;

        switch ($targetType) {
            case 'user':
                $namePhoto = User::query()
                    ->select('name', 'photo')
                    ->where('id', $target)
                    ->get();
                break;
            case 'ind':
                $namePhoto = DB::table('inds')
                    ->select('categories.name as name', 'photo')
                    ->where('inds.id', $target)
                    ->join('categories', 'categories.id', '=', 'inds.category_id')
                    ->join('users', 'users.id', '=', 'inds.user_id')
                    ->get();
                break;
            default:
                $namePhoto = Org::where('id', $target)->select('name', 'logo as photo')->get();
        }

        DB::commit();

        $row = $namePhoto[0];
        return [
            'id' => $conversation->id,
            'mid' => $member1->id,
            'member' => [
                'id' => $member2->id,
                'userId' => $target,
                'type' => $targetType,
                'name' => $row->name,
                'photo' => asset('storage/' . $row->photo)
            ]
        ];
    }

    public function destroy($cid)
    {

        $member = ConversationMember::query()
            ->select('id')
            ->where('conversation_id', $cid)
            ->where('user_id', Auth::id())
            ->limit(1)
            ->first();


        if (!$member) {
            return response('', 401);
        }

        Conversation::query()->where('id', $cid)->delete();

        event(new ConversationRemoved($cid, $member->id));

        return response('');
    }

    private function createMember($conversation, $type, $id, $userId)
    {
        $member = new ConversationMember;
        $member->conversation_id = $conversation;
        $member->user_id = $userId;
        $member->memberable_type = $type;
        $member->memberable_id = $id;
        $member->save();

        return $member;
    }
}
