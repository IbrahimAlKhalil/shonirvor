<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndsTable extends Migration
{
    public function up()
    {
        Schema::create('inds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('division_id');
            $table->unsignedInteger('district_id');
            $table->unsignedInteger('thana_id');
            $table->unsignedInteger('union_id');
            $table->unsignedInteger('village_id');

            $table->text('description');
            $table->string('email')->nullable();
            $table->string('mobile', 11);
            $table->string('referrer', 11)->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('address')->nullable();
            $table->string('experience_certificate')->nullable();
            $table->string('cv')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_available')->default(1);
            $table->boolean('is_pending')->default(1);
            $table->boolean('is_top')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('thana_id')
                ->references('id')
                ->on('thanas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('union_id')
                ->references('id')
                ->on('unions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onUpdate('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('inds');
    }
}
