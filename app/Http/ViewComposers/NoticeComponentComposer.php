<?php

namespace App\Http\ViewComposers;

use App\Models\Notice;
use Illuminate\View\View;

class NoticeComponentComposer
{
    public function compose(View $view)
    {
        $notices = Notice::all();

        $noticeStrCount = 0;
        foreach ($notices as $notice) {
            $noticeStrCount += strlen($notice->say);
        }

        $view->with(compact('notices', 'noticeStrCount'));
    }
}