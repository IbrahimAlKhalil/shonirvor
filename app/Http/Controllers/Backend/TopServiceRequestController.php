<?php

namespace App\Http\Controllers\Backend;

use App\Models\Income;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TopServiceRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:top-service-request,application', ['except' => 'index']);
    }

    public function index()
    {
        $packageTypeId = 3;
        if (request()->filled('type') && request()->get('type') != 3) {
            if (request()->get('type') != 4) abort('404', 'Type not match.');
            $packageTypeId = request()->get('type');
        }

        $packageIds = DB::table('packages')->where('package_type_id', $packageTypeId)
            ->pluck('id')->toArray();

        $query = Income::with('package.properties')
            ->where('approved', 0)
            ->whereIn('package_id', $packageIds);

        if ($packageTypeId == 3) {
            $query->join('inds', function ($join) {
                    $join->on('incomes.incomeable_id', 'inds.id')
                    ->whereNull('inds.deleted_at');
                })
                ->join('users','inds.user_id', 'users.id')
                ->select('users.name');

            $serviceRequestRoute = 'backend.request.ind-service-request.index';
        } else {
            $query->join('orgs', function ($join) {
                $join->on('incomes.incomeable_id', 'orgs.id')
                    ->whereNull('orgs.deleted_at');
                })
                ->select('orgs.name');

            $serviceRequestRoute = 'backend.request.org-service-request.index';
        }

        $applications = $query->addSelect(
            'incomes.id',
            'incomes.package_id',
            'incomes.incomeable_id',
            'incomes.incomeable_type'
            )->paginate(20);

        $navs = [
            ['url' => route($serviceRequestRoute), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('backend.request.top-service.index').'?type='.$packageTypeId, 'text' => 'টপ সার্ভিস রিকোয়েস্ট'],
            ['url' => route('dashboard'), 'text' => 'এডিট রিকোয়েস্ট']
        ];

        return view('backend.request.top-service.index', compact('applications', 'navs'));
    }

    public function show(Income $application)
    {
        $application->load([
            'incomeable.user',
            'package.properties'
        ]);

        return view('backend.request.top-service.show', compact('application'));
    }

    public function update(Income $application)
    {
        $application->load([
            'incomeable',
            'package.properties'
        ]);

        $packageTypeId = $application->package->type->id;

        $packageDuration = $application->package->properties->where('name', 'duration')->first()->value;
        $currentExpire = $application->incomeable->top_expire;

        if ($currentExpire != null && $currentExpire->isFuture()) {
            $newExpire = $currentExpire->addDays($packageDuration);
        } else {
            $newExpire = now()->addDays($packageDuration);
        }

        DB::beginTransaction();

        $application->approved = 1;
        $application->save();

        $application->incomeable->top_expire = $newExpire;
        $application->incomeable->save();

        DB::commit();

        return redirect(route('backend.request.top-service.index').'?type='.$packageTypeId)
            ->with('success', 'টপ সার্ভিস রিকোয়েস্টটি গ্রহণ করা হয়েছে।');
    }

    public function destroy(Income $application)
    {
        $packageTypeId = $application->package->type->id;
        $application->delete();

        return redirect(route('backend.request.top-service.index').'?type='.$packageTypeId)
            ->with('success', 'টপ সার্ভিস রিকোয়েস্টটি মুছে ফেলা হয়েছে।');
    }
}
