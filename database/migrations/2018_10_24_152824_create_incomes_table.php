<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('package_id');
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->morphs('incomeable');
            $table->string('from')->nullable();
            $table->string('transactionId')->nullable();
            $table->boolean('approved');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
