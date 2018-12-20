<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ad;
use App\Models\AdEdit;
use App\Models\Income;
use App\Models\Ind;
use App\Models\Org;
use App\Models\ServiceEdit;
use App\Models\User;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function __invoke()
    {
        $userCount = User::count();

        $inds = Ind::withTrashed()->get();
        $orgs = Org::withTrashed()->get();
        $serviceEdits = ServiceEdit::all();

        $indPackageIds = DB::table('packages')->where('package_type_id', 3)
            ->pluck('id')->toArray();
        $indTopRequestCount = Income::with('package.properties')
            ->join('inds', function ($join) {
                $join->on('incomes.incomeable_id', 'inds.id')
                    ->whereNull('inds.deleted_at');
            })
            ->where('approved', 0)
            ->whereIn('package_id', $indPackageIds)
            ->count();

        $orgPackageIds = DB::table('packages')->where('package_type_id', 4)
            ->pluck('id')->toArray();
        $orgTopRequestCount = Income::with('package.properties')
            ->join('orgs', function ($join) {
                $join->on('incomes.incomeable_id', 'orgs.id')
                    ->whereNull('orgs.deleted_at');
            })
            ->where('approved', 0)
            ->whereIn('package_id', $orgPackageIds)
            ->count();

        $ads = Ad::all();
        $adEditCount = AdEdit::count();

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

        return view('backend.dashboard', compact('userCount', 'inds', 'indTopRequestCount', 'orgs', 'orgTopRequestCount', 'serviceEdits', 'ads', 'adEditCount', 'smsBalance'));
    }
}