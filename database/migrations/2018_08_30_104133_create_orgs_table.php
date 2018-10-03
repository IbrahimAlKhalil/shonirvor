<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgsTable extends Migration
{
    public function up()
    {
        Schema::create('orgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('division_id');
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('thana_id')->nullable();
            $table->unsignedInteger('union_id')->nullable();
            $table->string('email');
            $table->string('mobile', 11);
            $table->string('referrer', 11)->nullable();
            $table->string('name');
            $table->text('description');
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->boolean('no_area')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('address');
            $table->string('trade_license')->nullable();
            $table->boolean('is_pending')->default(1);
            $table->boolean('is_top')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onUpdate('cascade');

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
        });
    }

    public function down()
    {
        Schema::dropIfExists('orgs');
    }
}
