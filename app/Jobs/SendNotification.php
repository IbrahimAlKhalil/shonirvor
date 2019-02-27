<?php

namespace App\Jobs;

use App\Events\NotificationSent;
use App\Models\Ind;
use App\Models\Org;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification as Notify;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $indIds;
    public $orgIds;
    public $message;

    public function __construct($indIds, $orgIds, $message)
    {
        $this->indIds = $indIds;
        $this->orgIds = $orgIds;
        $this->message = $message;
    }


    public function handle()
    {
        $this->notify(Ind::class, $this->indIds);
        $this->notify(Org::class, $this->orgIds);
    }

    public function notify($model, $ids)
    {
        static $count = 0;

        $model::withTrashed()->whereIn('id', $ids)->chunk(100, function ($services) use (&$count) {
            Notify::send($services, new Notification($this->message));
            event(new NotificationSent($count += count($services), true));
        });
    }
}
