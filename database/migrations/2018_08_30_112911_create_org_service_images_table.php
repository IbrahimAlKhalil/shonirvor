<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgServiceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_service_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_service_id');
            $table->string('image');

            $table->timestamps();

            $table->foreign('org_service_id', 'org_img_foreign')
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
        Schema::dropIfExists('org_service_images');
    }
}
