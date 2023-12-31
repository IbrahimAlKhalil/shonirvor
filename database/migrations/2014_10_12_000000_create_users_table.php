<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mobile')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('nid')->unique()->nullable();
            $table->date('dob')->nullable();
            $table->string('qualification')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->default('default/user-photo/person.jpg');
            $table->string('password');
            $table->integer('verification_token')->nullable();
            $table->integer('reset_token')->nullable();
            $table->boolean('online')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
