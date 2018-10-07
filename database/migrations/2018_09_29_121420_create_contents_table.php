<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{

    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('content_type_id');
            $table->mediumText('data')->nullable();
            $table->timestamps();

            $table->foreign('content_type_id')
                ->references('id')
                ->on('content_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
