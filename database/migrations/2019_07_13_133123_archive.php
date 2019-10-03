<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Archive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('company_id')->index();
            $table->string('token')->index();

            $table->string('name');
            $table->string('email');

            $table->timestamp('created_at')->nullable();
        });

        Schema::create('archive_messages', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->integer('archive_id')->index();

            $table->integer('user_id');
            $table->string('message');

            $table->timestamp('created_at')->nullable();
        });

        Schema::create('archive_location', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('archive_id')->index();
            $table->string('ip');

            $table->string('browser');
            $table->string('os');

            $table->string('country');
            $table->string('region');
            $table->string('city');

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archive_location');
        Schema::dropIfExists('archive_messages');
        Schema::dropIfExists('archives');
    }
}
