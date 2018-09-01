<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendingIndServiceDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_ind_service_docs', function (Blueprint $table) {
            $table->unsignedInteger('pending_ind_service_id');
            $table->string('doc');

            $table->timestamps();

            $table->foreign('pending_ind_service_id', 'pen_ind_doc_foreign')
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
        Schema::dropIfExists('pending_ind_service_docs');
    }
}
