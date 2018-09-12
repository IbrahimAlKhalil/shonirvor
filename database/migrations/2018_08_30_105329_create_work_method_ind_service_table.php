<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkMethodIndServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_method_ind_service', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_service_id');
            $table->unsignedInteger('work_method_id');

            $table->timestamps();

            $table->foreign('work_method_id')->references('id')->on('work_methods')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ind_service_id')->references('id')->on('ind_services')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_method_ind_service');
    }
}
