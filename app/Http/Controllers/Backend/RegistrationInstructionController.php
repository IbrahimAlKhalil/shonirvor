<?php

namespace App\Http\Controllers\Backend;

use App\Models\ContentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RegistrationInstructionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $navs = $this->navs();
        $content = getContent('registration-instruction');
        $id = $content->contentType->id;
        return view('backend.contents.registration-instruction', compact('navs', 'content', 'id'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        getContent('registration-instruction')->update(['data' => $request->post('data')]);
        DB::commit();
        return back()->with('success', 'আপডেট হয়েছে!');
    }

    public function navs()
    {
        return [
            ['url' => route('contents.slider.index'), 'text' => 'হোমপেজ এর স্লাইডার'],
            ['url' => route('contents.registration-instruction.index'), 'text' => 'রেজিস্ট্রেশন নির্দেশিকা']
        ];
    }
}
