<?php

namespace App\Http\Controllers;

use App\Models\Ind;
use App\Models\Org;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CommandController extends Controller
{
    public function storage()
    {
        Artisan::call('storage:link');
        return redirect(route('frontend.home'));
    }

    public function migrateDatabase()
    {
        return 'Ok';
    }

    public function configCache() {
        Artisan::call('config:cache');
        return 'ok';
    }

    public function routeClear() {
        Artisan::call('route:cache');
        return 'ok';
    }
}