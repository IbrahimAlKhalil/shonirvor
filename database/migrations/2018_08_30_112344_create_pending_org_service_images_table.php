<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendingOrgServiceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_org_service_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pending_org_service_id');
            $table->string('image');

            $table->timestamps();

            $table->foreign('pending_org_service_id', 'pen_org_img_foreign')
                ->references('id')
                ->on('pending_org_services')
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
        Schema::dropIfExists('pending_org_service_images');
    }
}
