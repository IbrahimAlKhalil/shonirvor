<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgAdditionalPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_additional_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_id');
            $table->text('name');
            $table->text('info');
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
        Schema::dropIfExists('org_additional_prices');
    }
}
