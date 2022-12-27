<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('settings');
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('setting_group_id')->unsigned();        
            $table->foreign('setting_group_id')->references('id')->on('setting_groups')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('key')->nullable();
            $table->string('options')->nullable();
            $table->string('value')->nullable();
            $table->integer("digits")->default(3);
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
        Schema::dropIfExists('settings');
    }
}
