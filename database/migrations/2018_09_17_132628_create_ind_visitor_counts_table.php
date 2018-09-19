<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndVisitorCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_visitor_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_id');
            $table->integer('how_much')->default(1);
            $table->timestamps();

            $table->foreign('ind_id')->references('id')->on('inds')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ind_visitor_counts');
    }
}
