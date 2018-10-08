<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgSubCategoryRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_sub_category_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_id');
            $table->unsignedInteger('sub_category_id');
            $table->float('rate')->nullable();
            $table->timestamps();

            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('org_id')
                ->references('id')
                ->on('orgs')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_sub_category_rates');
    }
}
