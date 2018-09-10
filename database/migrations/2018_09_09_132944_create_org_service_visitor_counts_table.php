<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgServiceVisitorCountsTable extends Migration
{
    public function up()
    {
        Schema::create('org_service_visitor_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_service_id');
            $table->integer('how_much')->default(1);
            $table->timestamps();

            $table->foreign('org_service_id')->references('id')->on('org_services')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('org_service_visitor_counts');
    }
}
