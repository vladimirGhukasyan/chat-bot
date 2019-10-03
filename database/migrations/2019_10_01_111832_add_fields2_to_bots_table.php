<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFields2ToBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('bots', 'typing_emulation_mode')) {
            Schema::table('bots', function (Blueprint $table) {
                $table->integer('typing_emulation_mode')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('bots', 'typing_emulation_mode'))
        {

            Schema::table('bots', function (Blueprint $table) {
                $table->dropColumn('typing_emulation_mode');
            });
        }
    }
}
