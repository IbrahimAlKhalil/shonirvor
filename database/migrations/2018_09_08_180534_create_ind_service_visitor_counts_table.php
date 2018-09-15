<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndServiceVisitorCountsTable extends Migration
{
    public function up()
    {
        /*Schema::create('ind_service_visitor_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_service_id');
            $table->integer('how_much')->default(1);
            $table->timestamps();

            $table->foreign('ind_service_id')->references('id')->on('ind_services')->onUpdate('cascade')->onDelete('cascade');
        });*/
    }

    public function down()
    {
        Schema::dropIfExists('ind_service_visitor_counts');
    }
}