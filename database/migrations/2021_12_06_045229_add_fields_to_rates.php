<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rates', function (Blueprint $table) {
            //
            $table->double('supply_rate',8,2)->default(0);
            $table->double('whole_sale_rate',8,2)->default(0);
            $table->text('wholesale_rate')->nullable()->default('[]');
            $table->double('retail_rate',8,2)->default(0);
            $table->double('other_rate',8,2)->default(0);
            $table->enum('type', ['Regular', 'Exceptional'])->default('Regular');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rates', function (Blueprint $table) {
            //
        });
    }
}
