<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSentReceiveToStockRequestedProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_requested_products', function (Blueprint $table) {
            //
            $table->double('stock_sent', 8, 2)->default(0)->nullable();
            $table->double('stock_received', 8, 2)->default(0)->nullable();
            $table->double('stock_wastage', 8, 2)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_requested_products', function (Blueprint $table) {
            //
        });
    }
}
