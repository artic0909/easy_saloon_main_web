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
            $table->string('razorpay_order_id')->nullable()->after('is_paid');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
        });

        Schema::table('custom_bookings', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('status');
            $table->string('payment_type')->default('online')->after('is_paid');
            $table->string('razorpay_order_id')->nullable()->after('payment_type');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);
        });

        Schema::table('custom_bookings', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'payment_type', 'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);
        });
    }
};
