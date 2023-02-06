<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('shop_id')->nullable()->constrained('shops');
            $table->foreignId('product_id')->constrained('products');
            $table->double('retail_rate',8,2)->nullable()->default(0);
            $table->double('whole_sale_rate',8,2)->nullable()->default(0);
            $table->string('wholesale_rate')->nullable()->default("[]");
            $table->enum('type',["Regular","Exceptional"])->default("Regular");
            $table->enum('status',["Active","Inactive"])->default("Active");
            $table->string('addons')->nullable()->default("[]");
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
        Schema::dropIfExists('rates');
    }
}
