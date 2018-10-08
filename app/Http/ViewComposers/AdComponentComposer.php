<?php

namespace App\Http\ViewComposers;


use App\Models\Ad;
use Illuminate\View\View;

class AdComponentComposer
{
    public function compose(View $view)
    {
        $ads = Ad::inRandomOrder()->take(3)->get();
        $view->with(compact('ads'));
    }
}