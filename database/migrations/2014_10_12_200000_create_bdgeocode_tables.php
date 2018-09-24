<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBdgeocodeTables extends Migration
{
    public function up()
    {
        DB::beginTransaction();

        Schema::create('divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->nullable();
            $table->string('bn_name')->unique();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('division_id');
            $table->string('name')->unique()->nullable();
            $table->string('bn_name')->unique();
            $table->double('lat')->nullable();
            $table->double('lon')->nullable();
            $table->string('website')->nullable();

            $table->foreign('division_id')->references('id')->on('divisions')->onUpdate('cascade');
        });

        Schema::create('thanas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('district_id');
            $table->string('name')->nullable();
            $table->string('bn_name');
            $table->boolean('is_pending')->default(0);

            $table->foreign('district_id')->references('id')->on('districts')->onUpdate('cascade');
        });

        Schema::create('unions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('thana_id');
            $table->string('name')->nullable();
            $table->string('bn_name');
            $table->boolean('is_pending')->default(0);

            $table->foreign('thana_id')->references('id')->on('thanas')->onUpdate('cascade');
        });

        DB::commit();
    }

    public function down()
    {
        Schema::dropIfExists('unions');
        Schema::dropIfExists('thanas');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('divisions');
    }
}