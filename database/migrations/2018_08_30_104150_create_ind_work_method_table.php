<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndWorkMethodTable extends Migration
{
    public function up()
    {
        Schema::create('ind_work_method', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_id');
            $table->unsignedInteger('work_method_id');
            $table->timestamps();

            $table->foreign('ind_id')
                ->references('id')
                ->on('inds')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ind_work_method');
    }
}
