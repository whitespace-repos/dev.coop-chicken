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
        Schema::table('purchase_histories', function (Blueprint $table) {
            //
            $table->string('payment_id')->nullable()->after('payment_type');
            $table->enum('payment_method',['Online','Offline'])->default('Offline')->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchasae_history', function (Blueprint $table) {
            //
        });
    }
};
