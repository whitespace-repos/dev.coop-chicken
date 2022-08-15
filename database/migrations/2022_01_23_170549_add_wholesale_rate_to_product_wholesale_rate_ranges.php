<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWholesaleRateToProductWholesaleRateRanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_wholesale_rate_ranges', function (Blueprint $table) {
            //
            $table->double('wholesale_rate', 8, 2)->default(0)->nullable()->after("to");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_wholesale_rate_ranges', function (Blueprint $table) {
            //
        });
    }
}
