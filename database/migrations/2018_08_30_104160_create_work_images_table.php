<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkImagesTable extends Migration
{
    public function up()
    {
        Schema::create('work_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->string('description')->nullable();
            $table->morphs('work_imagable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_images');
    }
}
