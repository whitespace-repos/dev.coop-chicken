<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWholesaleRateRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_wholesale_rate_ranges', function (Blueprint $table) {
            $table->id();            
            $table->bigInteger('product_id')->unsigned();                           
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('from')->default(0);
            $table->integer('to')->default(0);
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
        Schema::dropIfExists('product_wholesale_rate_ranges');
    }
}
