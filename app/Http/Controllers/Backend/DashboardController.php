<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function __invoke()
    {
        $client = new Client([
            'base_uri' => 'http://portal.smsinbd.com'
        ]);

        $response = $client->request('GET','/api', [
            'query' => [
                'api_key' => config('sms.api'),
                'act' => 'balance',
                'method' => 'api'
            ]
        ]);

        $smsBalance = explode(' ', json_decode(trim($response->getBody()->getContents()))->balance)[0];

        return view('backend.dashboard', compact('smsBalance'));
    }
}