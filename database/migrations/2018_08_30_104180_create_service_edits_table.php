<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceEditsTable extends Migration
{
    public function up()
    {
        Schema::create('service_edits', function (Blueprint $table) {
            $table->increments('id');
            $table->text('data');
            $table->morphs('service_editable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_edits');
    }
}
