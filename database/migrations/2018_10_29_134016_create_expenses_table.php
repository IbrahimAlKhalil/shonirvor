<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('payment_method_id');
            $table->unsignedInteger('expense_type_id');
            $table->float('amount');
            $table->string('from');
            $table->string('transactionId');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->onUpdate('cascade');

            $table->foreign('expense_type_id')
                ->references('id')
                ->on('expense_types')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
