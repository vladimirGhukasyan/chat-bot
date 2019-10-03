<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->index();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('botBlock', function (Blueprint $table) {
            $table->integer('bot_id');
            $table->bigIncrements('id');
            $table->string('blockType');
            $table->string('content');
            $table->float('positionX');
            $table->float('positionY');
            $table->string('parameter');
            $table->string('externalLink');
            $table->string('notificationType');
            $table->text('agentExtraInfo');

            $table->integer('emojiId');
            $table->integer('agentId');

            $table->text('range');
            $table->text('pins');
            $table->text('ab');
            $table->timestamps();
        });
        Schema::create('botConnections', function (Blueprint $table) {
            $table->integer('bot_id')->unique();
            $table->text('values');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bots');
        Schema::dropIfExists('botBlock');
        Schema::dropIfExists('botConnections');
    }
}
