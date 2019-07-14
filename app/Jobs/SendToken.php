<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mobile;
    public $token;

    /**
     * Create a new job instance.
     * @param string $mobile
     * @param string $token
     * @return void
     */
    public function __construct($mobile, $token)
    {
        $this->mobile = $mobile;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token = $this->token;
        sms($this->mobile, "The verification code from AreaSheba is: $token");
    }
}
