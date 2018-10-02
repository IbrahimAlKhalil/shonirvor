<?php

namespace App\Http\ViewComposers;


use App\Models\Ad;
use Illuminate\View\View;

class AdComponentComposer
{
    public function compose(View $view)
    {
        $ads = Ad::all();
        if ($ads->count() >= 3) {
            $ads = $ads->random(3);
        }
        $view->with(compact('ads'));
    }
}