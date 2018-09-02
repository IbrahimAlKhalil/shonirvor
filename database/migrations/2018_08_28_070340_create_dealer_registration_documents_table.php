<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerRegistrationDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('dealer_registration_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pending_dealer_id');
            $table->string('document');
            $table->timestamps();

            $table->foreign('pending_dealer_id')->references('id')->on('pending_dealers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dealer_registration_documents');
    }
}
