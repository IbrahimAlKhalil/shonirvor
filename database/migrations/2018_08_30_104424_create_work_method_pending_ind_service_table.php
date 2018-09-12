<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkMethodPendingIndServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_method_pending_ind_service', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pending_ind_service_id');
            $table->unsignedInteger('work_method_id');

            $table->foreign('work_method_id')
                ->references('id')
                ->on('work_methods')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('pending_ind_service_id')
                ->references('id')
                ->on('pending_ind_services')
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
        Schema::dropIfExists('work_method_pending_ind_service');
    }
}
