<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgServiceDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_service_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_service_id');
            $table->string('doc');

            $table->timestamps();

            $table->foreign('org_service_id', 'org_doc_foreign')
                ->references('id')
                ->on('org_services')
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
        Schema::dropIfExists('org_service_docs');
    }
}
