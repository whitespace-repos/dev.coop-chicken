<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string("payment_id");
            $table->enum("type",["Sale","Stock","Customer","Other"]);            
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->double("amount",8,2)->default(0);
            $table->enum('status',["Complete","Incomplete"])->nullable();
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
        Schema::dropIfExists('payments');
    }
};
