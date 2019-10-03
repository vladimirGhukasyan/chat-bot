<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->index();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('widgetSizeAndLocation', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('embedType')->default('');
            $table->string('width')->default('');
            $table->string('height')->default('');
            $table->string('boxShadow')->default('');
            $table->string('align')->default('');
            $table->timestamps();
        });
        Schema::create('widgetHeader', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('fontFamily')->default('');
            $table->string('fontSize')->default('');
            $table->string('color')->default('');
            $table->string('backgroundSize')->default('');
            $table->string('backgroundImage')->default('');
            $table->string('backgroundColor')->default('');
            $table->timestamps();
        });
        Schema::create('widgetHeaderManagerPhoto', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('backgroundImage')->default('');
            $table->string('backgroundSize')->default('');
            $table->timestamps();
        });
        Schema::create('widgetBody', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('backgroundImage')->default('');
            $table->string('backgroundColor')->default('');
            $table->string('backgroundSize')->default('');
            $table->timestamps();
        });
        Schema::create('widgetDialogueBody', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('paddingLeft')->default('');
            $table->string('paddingRight')->default('');
            $table->timestamps();
        });
        Schema::create('widgetFooter', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('color')->default('');
            $table->string('backgroundColor')->default('');
            $table->string('send')->default('');
            $table->timestamps();
        });
        Schema::create('widgetButton', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('backgroundImage')->default('');
            $table->string('backgroundSize')->default('');
            $table->string('backgroundColor')->default('');
            $table->string('color')->default('');
            $table->string('fontFamily')->default('');
            $table->string('fontSize')->default('');
            $table->string('text')->default('');
            $table->string('width')->default('');
            $table->string('height')->default('');
            $table->string('textAlign')->default('');
            $table->string('shape')->default('');
            $table->string('padding')->default('');
            $table->timestamps();
        });
        Schema::create('widgetBubbleMain', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('maxWidth')->default('');
            $table->string('backgroundSize')->default('');
            $table->timestamps();
        });
        Schema::create('widgetBubble', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('fontFamily')->default('');
            $table->string('fontSize')->default('');
            $table->string('boxShadow')->default('');
            $table->string('backgroundImage')->default('');
            $table->string('backgroundColor')->default('');
            $table->string('shape')->default('');
            $table->string('animation')->default('');
            $table->timestamps();
        });
        Schema::create('widgetBubbleAdmin', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('color')->default('');
            $table->string('backgroundColor')->default('');
            $table->timestamps();
        });
        Schema::create('widgetBubbleClient', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('color')->default('');
            $table->string('backgroundColor')->default('');
            $table->timestamps();
        });
        Schema::create('widgetBackgroundVideo', function (Blueprint $table) {
            $table->integer('widget_id')->unique();
            $table->string('url')->default('');
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
        Schema::dropIfExists('widgets');
        Schema::dropIfExists('widget_size_and_location');
        Schema::dropIfExists('widget_header');
        Schema::dropIfExists('widget_header_manager_photo');
        Schema::dropIfExists('widget_body');
        Schema::dropIfExists('widget_dialogue_body');
        Schema::dropIfExists('widget_footer');
        Schema::dropIfExists('widget_button');
        Schema::dropIfExists('widget_bubble_main');
        Schema::dropIfExists('widget_bubble');
        Schema::dropIfExists('widget_bubble_admin');
        Schema::dropIfExists('widget_bubble_client');
        Schema::dropIfExists('widget_background_video');
    }
}
