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
            $table->unsignedInteger('sub_category_id');
            $table->float('rate')->nullable();
            $table->timestamps();

            $table->foreign('ind_id')
                ->references('id')
                ->on('inds')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('work_method_id')
                ->references('id')
                ->on('work_methods')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ind_work_method');
    }
}
