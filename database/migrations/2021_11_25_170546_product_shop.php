<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductShop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('product_shop', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();        
            $table->bigInteger('shop_id')->unsigned();        
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade'); 
            $table->double('stock',8,3)->nullable()->default(0);
            $table->double('totalQtyPerDay',8,3)->nullable()->default(0);            
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
        //
        Schema::dropIfExists('product_shop');
    }
}
