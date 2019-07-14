<?php

namespace App\Http\Controllers\Frontend;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        return ChatMessage::query()->where('conversation_id', $request->input('cid'))->paginate()->items();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|in:ind,org,user',
            'cid' => 'required|integer',
            'mid' => 'required|integer',
            'message' => 'required|min:1'
        ]);

        // Authorize
        if (Gate::denies('get-messages', $request)) {
            return response('', 401);
        }

        return true;
    }

    public function destroy()
    {

    }
}
