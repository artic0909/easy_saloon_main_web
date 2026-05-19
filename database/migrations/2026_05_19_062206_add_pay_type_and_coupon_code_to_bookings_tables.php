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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('pay_type')->nullable()->after('payment_type');
            $table->string('coupon_code')->nullable()->after('payable_amount');
        });

        Schema::table('custom_bookings', function (Blueprint $table) {
            $table->string('pay_type')->nullable()->after('payment_type');
            $table->string('coupon_code')->nullable()->after('payable_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['pay_type', 'coupon_code']);
        });

        Schema::table('custom_bookings', function (Blueprint $table) {
            $table->dropColumn(['pay_type', 'coupon_code']);
        });
    }
};
