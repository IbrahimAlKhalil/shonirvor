<?php

namespace App\Listeners;

use App\Events\SmsSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySmsSent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SmsSent  $event
     * @return void
     */
    public function handle(SmsSent $event)
    {

    }
}
