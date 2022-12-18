<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockRequestedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_requested_products', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullale();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('stock_request_id')->constrained('stock_requests');
            $table->double('stock_request', 8, 2)->default(0)->nullable();
            $table->double('current_stock', 8, 2)->default(0)->nullable();
            $table->enum('status',['Requested','Pending','Rejected','Approved','Processing','Sent','Received','Completed','Cancelled'])->default('Requested')->nullable();
            $table->double('supply_rate', 8, 2)->default(0)->nullable();
            $table->double('stock_sent', 8, 2)->default(0)->nullable();
            $table->double('stock_received', 8, 2)->default(0)->nullable();
            $table->double('stock_wastage', 8, 2)->default(0)->nullable();
            $table->double('total', 8, 2)->default(0)->nullable();            
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
        Schema::dropIfExists('stock_requested_products');
    }
}
