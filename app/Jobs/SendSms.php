<?php

namespace App\Jobs;

use App\Events\SmsSent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $ids;
    public $message;

    public function __construct($ids, $message)
    {
        $this->ids = $ids;
        $this->message = $message;
    }

    public function handle()
    {
        static $count = 0;

        DB::table('users')
            ->select('mobile')
            ->orderBy('id')
            ->whereIn('id', $this->ids)
            ->chunk(10, function ($users) use (&$count) {

                foreach ($users as $user) {
                    $response = sms($user->mobile, urldecode($this->message));

                    if ($response['success']) {
                        event(new SmsSent(++$count, true));
                    } else {
                        event(new SmsSent(++$count, false));
                    }
                }

            });
    }
}
