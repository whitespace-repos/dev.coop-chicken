<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTypeToStockRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            //
            $table->enum('type',['Request','Direct'])->default('Request');
            $table->double('total_stock_sent', 8, 2)->default(0)->nullable();
            $table->double('total_stock_received', 8, 2)->default(0)->nullable();
            $table->double('total_stock_wastage', 8, 2)->default(0)->nullable(); 
            $table->double('actual_payment', 8, 2)->default(0)->nullable();             
            $table->double('payment_received', 8, 2)->default(0)->nullable();             
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            //
        });
    }
}
