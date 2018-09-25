<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('service_type_id');
            $table->string('name');
            $table->boolean('is_confirmed')->default(1);
            $table->timestamps();

            $table->foreign('service_type_id')->references('id')->on('service_types')->onUpdate('cascade');
        });
    }


    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
