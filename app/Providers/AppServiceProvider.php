<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'ind' => 'App\Models\Ind',
            'org' => 'App\Models\Org',
            'ad' => 'App\Models\Ad',
        ]);

        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {
            $column = isset($parameters[2]) ? $parameters[2] : 'password';
            $oldPassword = DB::table($parameters[0])->select($column)->where('id', $parameters[1])->first()->{$column};

            return Hash::check($value, $oldPassword);
        });
    }

    public function register()
    {
        //
    }
}
