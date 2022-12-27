<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('batch')->nullable();
            $table->date('date')->nullale();
            $table->foreignId('shop_id')->constrained('shops');
            $table->double('stock_request', 8, 2)->default(0)->nullable();
            $table->double('stock_remaining', 8, 2)->default(0)->nullable();
            $table->enum('payment_method',['Pay When Stock Received','Pay After Sales Completed','EMI','Cash','Checks','Debit cards','Credit cards','Mobile payments','UPI','Electronic bank transfers','RazorPay','Online'])->default("RazorPay")->nullable();
            $table->integer('payment_period')->nullable();
            $table->foreignId('stock_requested_by')->nullable()->constrained('users');
            $table->enum('status',['Requested','Pending','Rejected','Approved','Sent','Received','Completed','Processing','Cancelled'])->default('Requested')->nullable();
            $table->enum("type",["Request","Direct"])->default("Request");
            $table->string("payment_id")->nullable();            
            $table->double("total_stock_sent",8,2)->default(0);
            $table->double("total_stock_received",8,2)->default(0);
            $table->double("total_stock_wastage",8,2)->default(0);
            $table->double("actual_payment",8,2)->default(0);
            $table->double("payment_received",8,2)->default(0);            
            $table->boolean('data_sync')->default(0);
            $table->timestamp('data_sync_at')->nullable();
            $table->boolean("notify")->default(0);
            $table->enum('request_direction',['Server','Client'])->nullable();
            $table->boolean("completed")->default(0);
            $table->boolean("server_sync")->default(0);
            $table->boolean("client_sync")->default(0);            
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
        Schema::dropIfExists('stock_requests');
    }
}
