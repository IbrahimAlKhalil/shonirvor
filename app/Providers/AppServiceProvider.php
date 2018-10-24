<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'ind' => 'App\Models\Ind',
            'org' => 'App\Models\Org'
        ]);
    }

    public function register()
    {
        //
    }
}
