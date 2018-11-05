<?php

namespace App\Policies;

use App\Models\Income;
use App\Models\Ind;
use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class IndRenewApplicationPolicy
{
    use HandlesAuthorization;

    private $packageTypeId = 1;
    private $services, $packages, $oldApplication;

    public function __construct()
    {
        $this->services = Ind::where('user_id', Auth::id())->get();

        $this->packages = Package::with('properties')
            ->where('package_type_id', $this->packageTypeId)
            ->get();

        $this->oldApplication = Income::where([
            ['incomes.incomeable_type', 'ind'],
            ['incomes.approved', 0]
        ])
            ->whereIn('incomes.incomeable_id', $this->services->pluck('id')->toArray())
            ->whereIn('incomes.package_id', $this->packages->pluck('id')->toArray())
            ->first();
    }

    public function create()
    {
        return !$this->oldApplication;
    }

    public function update(User $user, Income $income)
    {
        return $this->oldApplication && $this->oldApplication->id == $income->id;
    }
}
