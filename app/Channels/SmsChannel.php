<?php

namespace App\Channels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);

        if (! config('sms.enabled')) {
            dump($message);
            dump('মেসেজ সার্ভিসটি আপাদত বন্ধ আছে।');
            return;
        }

        $client = new Client([
            'base_uri' => 'http://portal.smsinbd.com'
        ]);

        $client->request('GET','/smsapi',[
            'query' => [
                'api_key' => config('sms.api'),
                'type' => 'text',
                'contacts' => $notifiable->mobile,
                'senderid' => config('sms.senderid'),
                'msg' => $message,
                'method' => 'api'
            ]
        ]);
    }
}