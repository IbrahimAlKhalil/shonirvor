<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class SliderComposer
{
    public function compose(View $view)
    {
        $view->with(compact('sliders'));
    }
}