<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndSubCategoryPendingIndServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_sub_category_pending_ind_service', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pending_ind_service_id');
            $table->unsignedInteger('ind_sub_category_id');

            $table->timestamps();

            $table->foreign('pending_ind_service_id', 'pen_ind_sub')
                ->references('id')
                ->on('ind_services')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ind_sub_category_id', 'ind_sub')
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
        Schema::dropIfExists('ind_sub_category_pending_ind_service');
    }
}
