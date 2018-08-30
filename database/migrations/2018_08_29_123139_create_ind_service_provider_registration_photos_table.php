<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndServiceProviderRegistrationPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_service_provider_registration_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_service_provider_registration_id');
            $table->string('photo');

            $table->foreign('ind_service_provider_registration_id', 'in_se_foreign')
                ->references('id')
                ->on('ind_service_provider_registrations')
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
        Schema::dropIfExists('ind_service_provider_registration_photos');
    }
}
