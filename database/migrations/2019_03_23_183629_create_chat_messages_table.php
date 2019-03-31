<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('conversation_member_id');

            $table->string('message');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('type_id')
                ->references('id')
                ->on('chat_message_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('conversation_member_id')
                ->references('id')
                ->on('conversation_members')
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
        Schema::dropIfExists('chat_messages');
    }
}
