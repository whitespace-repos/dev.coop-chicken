<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->bigInteger('phone')->unique();
            $table->string('location')->nullable();
            $table->boolean('data_sync')->default(0);
            $table->timestamp('data_sync_at')->nullable();            
            $table->unsignedBigInteger('shop_id')->nullable();                 
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade'); 
            $table->string('batch_number')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
