<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add user_id if not exists
            if (!Schema::hasColumn('transactions', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            }
            
            // Make booking_id nullable and add custom_booking_id
            $table->foreignId('booking_id')->nullable()->change();
            
            if (!Schema::hasColumn('transactions', 'custom_booking_id')) {
                $table->foreignId('custom_booking_id')->nullable()->after('booking_id')->constrained('custom_bookings')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['custom_booking_id']);
            $table->dropColumn('custom_booking_id');
            $table->foreignId('booking_id')->nullable(false)->change();
        });
    }
};
