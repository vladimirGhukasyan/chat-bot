<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->boolean('typing_emulation_is_active')->default(0);
            $table->integer('typing_emulation_animation_id')->default(0);
            $table->integer('typing_emulation_delay')->default(0);
            $table->integer('typing_emulation_max_delay')->default(0);
            $table->integer('typing_emulation_speed')->default(0);
            $table->boolean('typing_emulation_is_custom')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn('typing_emulation_is_active');
            $table->dropColumn('typing_emulation_animation_id');
            $table->dropColumn('typing_emulation_delay');
            $table->dropColumn('typing_emulation_max_delay');
            $table->dropColumn('typing_emulation_speed');
            $table->dropColumn('typing_emulation_is_custom');

        });
    }
}
