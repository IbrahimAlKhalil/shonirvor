<?php

namespace App\Http\Controllers\Frontend;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ChatMessageController extends Controller
{

    public function getMessages(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|in:ind,org,user',
            'cid' => 'required|integer'
        ]);

        // Authorize
        if (Gate::denies('get-messages', $request)) {
            return response('', 401);
        }

        return ChatMessage::query()
            ->where('conversation_id', $request->input('cid'))
            ->select('conversation_member_id as mid', 'id', 'message as msg', 'created_at as at')
            ->paginate()
            ->items();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|in:ind,org,user',
            'cid' => 'required|integer',
            'mid' => 'required|integer',
            'txt' => 'min:1'
        ]);

        // Authorize
        if (Gate::denies('send-message', $request)) {
            return response('', 401);
        }

        DB::beginTransaction();

        $message = new ChatMessage;
        $message->type_id = 1;
        $message->conversation_id = $request->input('cid');
        $message->conversation_member_id = $request->input('mid');
        $message->message = $request->input('txt');
        $message->save();

        DB::commit();

        return [
            'id' => $message->id,
            'at' => $message->created_at
        ];
    }

    public function destroy()
    {

    }
}
