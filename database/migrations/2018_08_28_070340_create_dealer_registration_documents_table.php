<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerRegistrationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_registration_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dealer_registration_id');
            $table->string('document');
            $table->timestamps();

            $table->foreign('dealer_registration_id')->references('id')->on('dealer_registrations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_registration_documents');
    }
}
