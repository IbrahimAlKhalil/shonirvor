<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BackendTopNavComposer
{
    public function compose(View $view)
    {
        $notificationCount = Auth::user()->unreadNotifications->count();
        $view->with(compact('notificationCount'));
    }
}