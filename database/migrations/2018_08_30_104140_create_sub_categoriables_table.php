<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCategoriablesTable extends Migration
{
    public function up()
    {
        Schema::create('sub_categoriables', function (Blueprint $table) {
            $table->unsignedInteger('sub_category_id');
            $table->unsignedInteger('sub_categoriable_id');
            $table->string('sub_categoriable_type');
            $table->float('rate')->nullable();
            $table->timestamps();

            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_categoriables');
    }
}
