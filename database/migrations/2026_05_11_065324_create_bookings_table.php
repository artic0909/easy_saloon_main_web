<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->foreignId('salon_id')->nullable()->constrained('salons')->onDelete('set null');
            $table->enum('service_type', ['home', 'salon_visit']);
            $table->decimal('total_price', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0.00);
            $table->decimal('payable_amount', 15, 2);
            $table->date('booking_date');
            $table->string('time_slot');
            $table->enum('status', ['pending', 'confirmed', 'accepted', 'on_the_way', 'started', 'completed', 'cancelled'])->default('pending');
            $table->boolean('is_paid')->default(false);
            $table->enum('payment_type', ['online', 'wallet', 'cod'])->default('online');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
