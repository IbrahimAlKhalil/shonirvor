<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TopNavComposer
{
    public function compose(View $view)
    {
        $notificationCount = Auth::user()->unreadNotifications->count();

        $view->with('notificationCount', $notificationCount);
    }
}