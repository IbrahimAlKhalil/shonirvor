<?php

namespace App\Providers;

use App\Policies\AdApplicationPolicy;
use App\Policies\IndTopServiceRequestPolicy;
use App\Policies\OrgTopServiceRequestPolicy;
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

        Gate::define('ind-top-service-request', function ($user, $application) {
            return $application->approved == 0
                && $application->package->package_type_id == 3
                && $application->incomeable;
        });

        Gate::define('org-top-service-request', function ($user, $application) {
            return $application->approved == 0
                && $application->package->package_type_id == 4
                && $application->incomeable;
        });
    }
}