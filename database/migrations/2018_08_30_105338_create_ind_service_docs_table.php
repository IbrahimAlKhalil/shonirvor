<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndServiceDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_service_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_service_id');
            $table->string('doc');

            $table->timestamps();

            $table->foreign('ind_service_id', 'ind_doc_foreign')
                ->references('id')
                ->on('ind_services')
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
        Schema::dropIfExists('ind_service_docs');
    }
}
