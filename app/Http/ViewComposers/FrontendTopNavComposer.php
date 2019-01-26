<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FrontendTopNavComposer
{
    public function compose(View $view)
    {
        $myServiceLink = '';
        $userUnread = null;
        $userUnreadCount = 0;
        $user = Auth::user();

        if ($user) {
            if ($user->inds()->withTrashed()->exists()) {

                $indId = $user->inds()->withTrashed()->first()->id;
                $myServiceLink = route('frontend.my-service.ind.show', $indId);

            } elseif ($user->orgs()->withTrashed()->exists()) {

                $orgId = $user->orgs()->withTrashed()->first()->id;
                $myServiceLink = route('frontend.my-service.org.show', $orgId);

            }


            $unreadNotifications = $user->unreadNotifications();

            if ($unreadNotifications->exists()) {
                $userUnread = true;
                $userUnreadCount = $unreadNotifications->count();
            }
        }

        $view->with(compact('myServiceLink', 'userUnread', 'userUnreadCount'));
    }
}