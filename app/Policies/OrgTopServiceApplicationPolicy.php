<?php

namespace App\Policies;

use App\Models\Org;
use App\Models\Package;
use App\Models\User;
use App\Models\Income;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class OrgTopServiceApplicationPolicy
{
    use HandlesAuthorization;

    private $packageTypeId = 4;
    private $services, $packages, $oldApplication;

    public function __construct()
    {
        $this->services = Org::where('user_id', Auth::id())->get();

        $this->packages = Package::with('properties')
            ->where('package_type_id', $this->packageTypeId)
            ->get();

        $this->oldApplication = Income::where([
                ['incomes.incomeable_type', 'org'],
                ['incomes.approved', 0]
            ])
            ->whereIn('incomes.incomeable_id', $this->services->pluck('id')->toArray())
            ->whereIn('incomes.package_id', $this->packages->pluck('id')->toArray())
            ->first();
    }

    public function create(User $user)
    {
        return ! $this->oldApplication;
    }

    public function update(User $user, Income $income)
    {
        return $this->oldApplication && $this->oldApplication->id == $income->id;
    }
}
