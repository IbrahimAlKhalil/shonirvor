<?php

namespace App\Channels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);

        if (! env('SMS_ENABLED')) {
            dump($message);
            dump('মেসেজ সার্ভিসটি আপাদত বন্ধ আছে।');
            return;
        }

        $client = new Client([
            'base_uri' => 'http://portal.smsinbd.com'
        ]);

        $client->request('GET','/smsapi',[
            'query' => [
                'api_key' => env('SMS_API_KEY'),
                'type' => 'text',
                'contacts' => $notifiable->mobile,
                'senderid' => 'WIFAQ',
                'msg' => $message,
                'method' => 'api'
            ]
        ]);
    }
}