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
            $table->unsignedInteger('package_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('service_type_id')
                ->references('id')
                ->on('service_types');

            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('references');
    }
}
