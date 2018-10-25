<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FrontendTopNavComposer
{
    public function compose(View $view)
    {
        $myServiceLink = '';

        if (Auth::user())
            if (Auth::user()->inds()->withTrashed()->exists()) {

                $indId = Auth::user()->inds()->withTrashed()->first()->id;
                $myServiceLink = route('frontend.my-service.ind.show', $indId);

            } elseif (Auth::user()->orgs()->withTrashed()->exists()) {

                $orgId = Auth::user()->orgs()->withTrashed()->first()->id;
                $myServiceLink = route('frontend.my-service.org.show', $orgId);

            }

        $view->with(compact('myServiceLink'));
    }
}