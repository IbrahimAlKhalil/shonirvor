<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_sub_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_category_id');
            $table->string('sub_category');

            $table->timestamps();

            $table->foreign('ind_category_id')
                ->references('id')
                ->on('ind_categories')
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
        Schema::dropIfExists('ind_sub_categories');
    }
}
