<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('dealer_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dealer_id');
            $table->string('path');
            $table->timestamps();

            $table->foreign('dealer_id')->references('id')->on('dealers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dealer_documents');
    }
}
