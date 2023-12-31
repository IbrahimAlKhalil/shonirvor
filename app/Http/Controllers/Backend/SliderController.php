<?php

namespace App\Http\Controllers\Backend;

use App\Models\Content;
use App\Models\ContentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $navs = $this->navs();
        $contents = getContents('slider');
        $sliders = [];

        foreach ($contents->get(['id', 'data'])->toArray() as $key => $slider) {
            array_push($sliders, json_decode($slider['data'], true));
            $sliders[$key]['id'] = $slider['id'];
        }

        $id = ContentType::where('name', 'slider')->first()->id;

        return view('backend.contents.slider', compact('navs', 'sliders', 'id'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        // TODO:: Delete previous images

        $data = [];

        $contents = Content::where('content_type_id', $id)->get();
        Content::where('content_type_id', $id)->delete();

        foreach ($contents as $content) {
            $json = json_decode($content->data, true);
            Storage::delete($json['image']);
        }

        foreach ($request->post('images') as $key => $image) {
            if (array_key_exists('prev-image', $image)) {
                switch ($request->hasFile('sliders') && array_key_exists($key, $request->file('sliders'))) {
                    case true:
                        array_push($data, [
                            'data' => json_encode([
                                'link' => $image['link'],
                                'image' => $request->file('sliders.' . $key)->store('slider')
                            ]),
                            'content_type_id' => $id
                        ]);
                        break;
                    case false:
                        array_push($data, [
                            'data' => json_encode([
                                'link' => $image['link'],
                                'image' => $image['prev-image']
                            ]),
                            'content_type_id' => $id
                        ]);
                }
            } else if ($request->hasFile('sliders') && array_key_exists($key, $request->file('sliders'))) {
                array_push($data, [
                    'data' => json_encode([
                        'link' => $image['link'],
                        'image' => $request->file('sliders.' . $key)->store('slider')
                    ]),
                    'content_type_id' => $id
                ]);
            }
        }

        Content::insert($data);

        DB::commit();
        return back()->with('success', 'স্লাইডার আপডেট হয়েছে');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        Content::find($id)->delete();
        DB::commit();
        return back()->with('success', 'ডিলিট হয়েছে');
    }

    public function navs()
    {
        return [
            ['url' => route('contents.slider.index'), 'text' => 'হোমপেজ এর স্লাইডার'],
            ['url' => route('contents.registration-instruction.index'), 'text' => 'রেজিস্ট্রেশন নির্দেশিকা']
        ];
    }
}
