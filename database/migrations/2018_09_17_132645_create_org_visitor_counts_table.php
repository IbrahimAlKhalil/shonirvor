<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgVisitorCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_visitor_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_id');
            $table->integer('how_much')->default(1);
            $table->timestamps();

            $table->foreign('org_id')->references('id')->on('orgs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_visitor_counts');
    }
}
