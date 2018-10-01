<?php

namespace App\Providers;

use App\Http\ViewComposers\BackendTopNavComposer;
use App\Http\ViewComposers\NoticeComposer;
use App\Http\ViewComposers\SliderComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(
            'layouts.backend.partials.top-nav', BackendTopNavComposer::class
        );

        View::composer(
            'components.notice', NoticeComposer::class
        );

        View::composer(
            'components.slider', SliderComposer::class
        );
    }

    public function register()
    {
        //
    }
}
