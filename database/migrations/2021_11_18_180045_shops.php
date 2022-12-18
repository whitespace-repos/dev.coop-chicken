<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Shops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       //
       Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name');
            $table->string('shop_id')->unique();
            $table->text('address')->nullable();
            $table->string('distance_from_cps')->nullable();
            $table->string('shop_dimentions')->nullable();
            $table->integer('stock_capacity_per_day')->nullable();
            $table->double('max_sale_estimate_per_day', 8, 2)->nullable();
            $table->date('estimated_start_date')->nullable();
            $table->enum('status' ,['Active','Inactive'])->default('Active');
            $table->bigInteger('supplier_id')->unsigned();        
            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger("phone")->nullable();
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
        Schema::dropIfExists('shops');
    }
}
