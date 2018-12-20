<?php

namespace App\Http\Controllers\Backend;

use App\Models\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $notices = Notice::select('id', 'say')->paginate(15);
        return view('backend.notice', compact('notices'));
    }

    public function store(Request $request)
    {
        $notice = new Notice();
        $notice->say = $request->input('notice');
        $notice->save();

        return back()->with('success', 'নতুন নোটিশ তৈরি হয়েছে।');
    }

    public function update(Request $request, Notice $notice)
    {
        $notice->say = $request->input('notice');
        $notice->save();

        return back()->with('success', 'নোটিশ এডিট করা হয়েছে।');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return back()->with('success', 'নোটিশ মুছে ফেলা হয়েছে।');
    }
}
