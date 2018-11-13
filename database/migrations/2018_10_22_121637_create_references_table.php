<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferencesTable extends Migration
{
    public function up()
    {
        Schema::create('references', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('service_type_id');
            $table->integer('onetime_interest');
            $table->integer('renew_interest')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('target')->nullable();
            $table->integer('fail_onetime_interest')->nullable();
            $table->integer('fail_renew_interest')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('service_type_id')
                ->references('id')
                ->on('service_types')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('references');
    }
}
