<?php

namespace App\Providers;

use App\Http\ViewComposers\FrontendTopNavComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\AdComponentComposer;
use App\Http\ViewComposers\BackendTopNavComposer;
use App\Http\ViewComposers\FilterComponentComposer;
use App\Http\ViewComposers\NoticeComponentComposer;
use App\Http\ViewComposers\SliderComponentComposer;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(
            'layouts.backend.partials.top-nav', BackendTopNavComposer::class
        );

        View::composer(
            'layouts.frontend.partials.top-nav', FrontendTopNavComposer::class
        );

        View::composer(
            'components.notice', NoticeComponentComposer::class
        );

        View::composer(
            'components.slider', SliderComponentComposer::class
        );

        View::composer(
            'components.ad', AdComponentComposer::class
        );

        View::composer(
            'components.filter', FilterComponentComposer::class
        );
    }

    public function register()
    {
        //
    }
}
