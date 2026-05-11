<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('status');
            $table->string('payment_mode');
            $table->decimal('amount', 15, 2);
            $table->boolean('wallet_used')->default(false);
            $table->decimal('wallet_amount', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
