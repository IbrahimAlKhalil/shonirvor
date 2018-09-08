<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendingDealersTable extends Migration
{
    public function up()
    {
        Schema::create('pending_dealers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('category')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('thana_id')->nullable();
            $table->unsignedInteger('union_id')->nullable();
            $table->string('no_area')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onUpdate('cascade');
            $table->foreign('thana_id')->references('id')->on('thanas')->onUpdate('cascade');
            $table->foreign('union_id')->references('id')->on('unions')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pending_dealers');
    }
}
