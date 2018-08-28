<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('number', 15);
            $table->string('email')->nullable();
            $table->string('password');
            $table->unsignedTinyInteger('age');
            $table->string('qualification')->nullable();
            $table->string('address');
            $table->string('photo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_registrations');
    }
}
