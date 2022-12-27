<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_histories', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->double('total',8,2)->default(0);
            $table->double('receive',8,2)->nullable()->default(0);
            $table->double('rest_amount',8,2)->default(0);
            $table->double('past_due_amount',8,2)->default(0);            
            $table->integer('quantity')->default(0);            
            $table->foreignId('sold_by')->constrained('users')->nullable();
            $table->foreignId('shop_id')->constrained('shops')->nullable();
            $table->longText('cart')->nullable();
            $table->string("batch")->nullable();
            $table->enum("payment_type",["Discount","Round Off","Pending"])->nullable();
            $table->enum("payment_method",["Online","Offline"])->default("Offline");
            $table->string("payment_id")->nullable();
            $table->foreignId('customer_id')->constrained('customers')->nullable();        
            $table->boolean('data_sync')->default(0);
            $table->timestamp('data_sync_at')->nullable();
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
        Schema::dropIfExists('purchase_histories');
    }
}
