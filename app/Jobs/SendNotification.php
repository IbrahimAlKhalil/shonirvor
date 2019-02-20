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

        $model::withTrashed()->get()->whereIn('id', $ids)->chunk(100)->each(function ($services) use (&$count) {
            foreach ($services as $service) {
                try {
                    $service->notify(new Notification($this->message));
                    event(new NotificationSent(++$count, true));
                } catch (\Exception $exception) {
                    event(new NotificationSent(++$count, false));
                }
            }

        });
    }
}
