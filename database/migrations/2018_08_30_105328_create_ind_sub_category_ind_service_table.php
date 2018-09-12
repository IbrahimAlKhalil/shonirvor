<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndSubCategoryIndServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_sub_category_ind_service', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_service_id');
            $table->unsignedInteger('ind_sub_category_id');

            $table->timestamps();

            $table->foreign('ind_service_id')
                ->references('id')
                ->on('ind_services')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ind_sub_category_id')
                ->references('id')
                ->on('ind_sub_categories')
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
        Schema::dropIfExists('ind_sub_category_ind_service');
    }
}
