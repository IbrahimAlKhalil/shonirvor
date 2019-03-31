<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('conversation_id');
            $table->morphs('memberable');
            $table->unsignedInteger('unread');
            $table->dateTime('last_seen');
            $table->timestamps();

            $table->foreign('conversation_id')
                ->references('id')
                ->on('conversations')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversation_members');
    }
}
