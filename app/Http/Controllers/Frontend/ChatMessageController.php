<?php

namespace App\Http\Controllers\Frontend;

use App\Events\SendChatMessage;
use App\Models\ChatMessage;
use App\Models\ConversationMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ChatMessageController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'cid' => 'required|integer'
        ]);

        // Authorize
        if (Gate::denies('get-messages', $request)) {
            return response('', 401);
        }

        return ChatMessage::query()
            ->where('conversation_id', $request->input('cid'))
            ->select('conversation_member_id as mid', 'id', 'message as msg', 'created_at as at')
            ->orderBy('at', 'DESC')
            ->paginate(5)
            ->items();
    }

    public function store(Request $request)
    {
        $request->validate([
            'mid' => 'required|integer',
            'txt' => 'min:1'
        ]);

        $member = ConversationMember::query()
            ->select('conversation_id as cid')
            ->where('user_id', Auth::id())
            ->where('id', $request->input('mid'))
            ->limit(1)
            ->first();


        if (!$member) {
            return response('', 401);
        }

        DB::beginTransaction();

        $message = new ChatMessage;
        $message->type_id = 1;
        $message->conversation_id = $member->cid;
        $message->conversation_member_id = $request->input('mid');
        $message->message = $request->input('txt');
        $message->save();

        DB::commit();

        broadcast(new SendChatMessage($message))->toOthers();

        return [
            'id' => $message->id,
            'at' => $message->created_at->toDateTimeString()
        ];
    }

    public function destroy($id)
    {
        ChatMessage::query()->where('id', $id)->delete();
    }
}
