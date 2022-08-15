<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_histories', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->double('total',8,2)->default(0);
            $table->double('receive',8,2)->nullable()->default(0);
            $table->integer('quantity')->default(0);
            $table->bigInteger('sold_by')->unsigned();
            $table->foreign('sold_by')->references('id')->on('users')->onDelete('cascade');
            // 
            $table->bigInteger('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade'); 
            // 
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
        Schema::dropIfExists('purchase_histories');
    }
}
