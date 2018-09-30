<?php

namespace App\Http\Controllers\Backend;

use App\Models\Content;
use App\Models\ContentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    public function index()
    {
        $navs = $this->navs();
        $contents = getContents('slider');
        $sliders = [];

        foreach ($contents->get(['id', 'data'])->toArray() as $key => $slider) {
            array_push($sliders, json_decode($slider['data'], true));
            $sliders[$key]['id'] = $slider['id'];
        }

        usort($sliders, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        $id = ContentType::where('name', 'slider')->first()->id;
        return view('backend.contents.slider', compact('navs', 'sliders', 'id'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        // TODO:: Delete previous images

        ContentType::find($id)->contents()->delete();

        $data = [];
        if ($request->hasFile('sliders')) {
            foreach ($request->file('sliders') as $key => $slider) {
                array_push($data, [
                    'data' => json_encode([
                        'image' => $slider->store('slider'),
                        'link' => $request->post('images')[$key]['link'],
                        'order' => $request->post('images')[$key]['order']
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
            ['url' => route('contents.registration-instruction.index'), 'text' => 'রেজিস্ট্রেশন নির্দেশিকা'],
            ['url' => route('contents.slider.index'), 'text' => 'হোমপেজ এর স্লাইডার']
        ];
    }
}
