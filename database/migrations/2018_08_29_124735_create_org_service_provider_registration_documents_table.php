<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgServiceProviderRegistrationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_service_provider_registration_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_service_provider_registration_id');
            $table->string('document');

            $table->foreign('org_service_provider_registration_id', 'or_se_do_foreign')
                ->references('id')
                ->on('org_service_provider_registrations')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('org_service_provider_registration_documents');
    }
}
