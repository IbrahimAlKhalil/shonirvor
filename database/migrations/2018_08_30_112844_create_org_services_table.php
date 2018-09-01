<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_services', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');

            $table->string('email');
            $table->string('org_name');
            $table->text('description');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('service');
            $table->string('address');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('org_services');
    }
}
