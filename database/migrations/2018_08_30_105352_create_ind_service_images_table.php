<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndServiceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_service_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ind_service_id');
            $table->string('image');

            $table->timestamps();

            $table->foreign('ind_service_id', 'ind_img_foreign')
                ->references('id')
                ->on('ind_services')
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
        Schema::dropIfExists('ind_service_images');
    }
}
