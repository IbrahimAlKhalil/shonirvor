<?php

namespace App\Channels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        dd('মেসেজ সার্ভিসটি আপাদত বন্ধ আছে।');

        $message = $notification->toSms($notifiable);

        $client = new Client([
            'base_uri' => 'http://portal.smsinbd.com'
        ]);

        $client->request('GET','/smsapi',[
            'query' => [
                'api_key' => 65465165,
                'type' => 'text',
                'contacts' => $notifiable->mobile,
                'senderid' => 'WIFAQ',
                'msg' => $message,
                'method' => 'api'
            ]
        ]);
    }
}