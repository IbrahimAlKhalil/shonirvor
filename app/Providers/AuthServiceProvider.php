<?php

namespace App\Providers;

use App\Policies\AdApplicationPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\IndTopServiceApplicationPolicy;
use App\Policies\OrgTopServiceApplicationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        $this->registerPolicies();
        Gate::resource('ad-application', AdApplicationPolicy::class);

        Gate::resource('ind-top-service-application', IndTopServiceApplicationPolicy::class);
        Gate::resource('org-top-service-application', OrgTopServiceApplicationPolicy::class);
    }
}
