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
        Artisan::call('migrate');

        DB::beginTransaction();
        $slugs = [];
        Ind::all()->each(function ($ind) use (&$slugs) {
            array_push($slugs, [
                'name' => $ind->slug,
                'sluggable_id' => $ind->id,
                'sluggable_type' => 'ind',
            ]);
        });

        Org::all()->each(function ($org) use (&$slugs) {
            array_push($slugs, [
                'name' => $org->slug,
                'sluggable_id' => $org->id,
                'sluggable_type' => 'org',
            ]);
        });

        DB::table('slugs')->insert($slugs);

        Schema::table('inds', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('orgs', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        DB::commit();

        return 'Ok';
    }
}