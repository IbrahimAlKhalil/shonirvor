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
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('message_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->mediumText('message');
            $table->timestamps();
        });

        return 'Ok';
    }

    public function configCache()
    {
        Artisan::call('config:cache');
        return 'ok';
    }

    public function routeClear()
    {
        Artisan::call('route:cache');
        return 'ok';
    }
}