<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToArchivesLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archive_location', function (Blueprint $table) {
            $table->string('key')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archive_location', function (Blueprint $table) {
            $table->dropColumn('key');
            $table->dropColumn('email');
            $table->dropColumn('phone');

        });
    }
}
