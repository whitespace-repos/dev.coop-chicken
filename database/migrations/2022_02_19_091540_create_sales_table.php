<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->double('total',8,2)->default(0);
            $table->double('receive',8,2)->nullable()->default(0);
            $table->double('quantity',8,3)->default(0);
            $table->double('rate',8,3)->default(0);
            $table->foreignId('sold_by')->constrained('users')->nullable();
            $table->longText('cart')->nullable();
            $table->foreignId('shop_id')->constrained('shops')->nullable();
            $table->foreignId('product_id')->constrained('products')->nullable();
            $table->foreignId('purchase_history_id')->constrained('purchase_histories')->nullable();   
            $table->foreignId('customer_id')->constrained('customers')->nullable();              
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
        Schema::dropIfExists('sales');
    }
}
