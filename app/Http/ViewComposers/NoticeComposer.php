<?php

namespace App\Http\ViewComposers;

use App\Models\Notice;
use Illuminate\View\View;

class NoticeComposer
{
    public function compose(View $view)
    {
        $notices = Notice::all();
        $view->with(compact('notices'));
    }
}