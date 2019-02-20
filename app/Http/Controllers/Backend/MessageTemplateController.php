<?php

namespace App\Http\Controllers\Backend;

use App\Models\MessageTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageTemplateController extends Controller
{
    private $addValidationRules = [
        'name' => 'required|string',
        'message' => 'required|string'
    ];

    public function store(Request $request)
    {
        $request->validate($this->addValidationRules);

        $template = new MessageTemplate;
        $template->name = $request->post('name');
        $template->message = $request->post('message');
        $template->save();

        return ['message' => 'Template Created successfully!', 'success' => true, 'id' => $template->id];
    }

    public function update(Request $request, MessageTemplate $messageTemplate)
    {
        $request->validate($this->addValidationRules);

        $messageTemplate->name = $request->post('name');
        $messageTemplate->message = $request->post('message');
        $messageTemplate->save();

        return ['message' => 'Template Updated successfully!', 'success' => true];
    }

    public function delete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string'
        ]);
        $ids = explode(',', $request->post('ids'));

        MessageTemplate::whereIn('id', $ids)->delete();

        return ['message' => 'Templates deleted successfully!', 'success' => true];
    }
}
