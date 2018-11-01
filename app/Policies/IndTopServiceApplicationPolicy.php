<?php

namespace App\Policies;

use App\Models\Ind;
use App\Models\User;
use App\Models\Income;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class IndTopServiceApplicationPolicy
{
    use HandlesAuthorization;

    private $packageTypeId = 3;
    private $services, $packages, $oldApplication;

    public function __construct()
    {
        $this->services = Ind::with('category')
            ->where('user_id', Auth::id())
            ->get();

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

    public function view(User $user, Income $income)
    {
        //
    }

    public function create(User $user)
    {
        return ! $this->oldApplication;
    }

    public function update(User $user, Income $income)
    {
        return $this->oldApplication && $this->oldApplication->id == $income->id;
    }

    public function delete(User $user, Income $income)
    {
        //
    }
}
