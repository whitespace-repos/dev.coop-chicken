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
            $table->double('rest_amount', 8, 2)->default(0)->nullable()->after('payment_type');
            $table->double('past_due_amount', 8, 2)->default(0)->nullable()->after('rest_amount');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_history', function (Blueprint $table) {
            //
        });
    }
};
