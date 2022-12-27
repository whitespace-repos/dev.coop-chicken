<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->enum('status' ,['Active','Inactive'])->default('Active');
            $table->foreignId('supplier_id')->constrained('users');
            $table->string("weight_unit")->nullable();
            $table->longText("raw_image")->nullable();
            $table->double("wholesale_weight",8,2)->nullable()->default(0);
            $table->string("image")->nullable();
            $table->boolean("stock")->default(0);
            $table->double("conversion_rate",8,2)->nullable()->default(0);
            $table->string("mask")->nullable();
            $table->integer("digits")->default(3);
            $table->foreignId('parent_product_id')->nullable()->constrained('products');            
            $table->boolean("wholesale_weight_range")->default(0);
            $table->double("default_wholesale_weight",8,2)->nullable()->default(0);            
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
        Schema::dropIfExists('products');
    }
}
