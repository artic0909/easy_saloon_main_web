<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('custom_bookings', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('total_price');
            $table->decimal('payable_amount', 10, 2)->default(0.00)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_bookings', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'payable_amount']);
        });
    }
};
