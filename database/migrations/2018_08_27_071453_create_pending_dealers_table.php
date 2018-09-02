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
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->string('union')->nullable();
            $table->string('no_area')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pending_dealers');
    }
}
