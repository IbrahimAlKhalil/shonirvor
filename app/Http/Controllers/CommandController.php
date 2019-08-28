<?php

namespace App\Http\Controllers;

use App\Models\Ind;
use App\Models\Org;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CommandController extends Controller
{
    public function storage()
    {
        Artisan::call('storage:link');
        return redirect(route('frontend.home'));
    }

    public function migrateDatabase()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chat_message_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('conversation_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('conversation_id');
            $table->unsignedInteger('user_id');
            $table->morphs('memberable');
            $table->timestamps();

            $table->foreign('conversation_id')
                ->references('id')
                ->on('conversations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('conversation_id');
            $table->unsignedInteger('conversation_member_id');

            $table->string('message');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('type_id')
                ->references('id')
                ->on('chat_message_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('conversation_id')
                ->references('id')
                ->on('conversations')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('conversation_member_id')
                ->references('id')
                ->on('conversation_members')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        return 'Ok';
    }

    public function configCache()
    {
        Artisan::call('config:cache');
        return 'ok';
    }

    public function routeClear()
    {
        Artisan::call('route:cache');
        return 'ok';
    }
}
