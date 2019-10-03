<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('avatar')->default('user.jpg');
            $table->string('user_name')->default('');
            $table->timestamp('recent_visit_at', 0)->nullable();
        });

        Schema::create('botsapp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->index();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('botsapp_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('botsapp_id')->index();
            $table->integer('user_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_manager');
    }
}
