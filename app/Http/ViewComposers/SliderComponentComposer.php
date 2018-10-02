<?php

namespace App\Http\ViewComposers;

use App\Models\Content;
use Illuminate\View\View;

class SliderComponentComposer
{
    public function compose(View $view)
    {
        $sliders = Content::select('data')->where('content_type_id', 2)->get();
        $view->with(compact('sliders'));
    }
}