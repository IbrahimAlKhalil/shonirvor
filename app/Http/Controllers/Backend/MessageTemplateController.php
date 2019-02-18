<?php

namespace App\Http\Controllers\Backend;

use App\Models\MessageTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageTemplateController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'message' => 'required|string'
        ]);

        $template = new MessageTemplate;
        $template->name = $request->post('name');
        $template->message = $request->post('message');
        $template->save();

        return ['message' => 'Template Created successfully!', 'success' => true];
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
