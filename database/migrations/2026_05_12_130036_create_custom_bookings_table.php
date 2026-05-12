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
        Schema::create('custom_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('booking_number')->unique();
            $table->json('service_ids');
            $table->json('equipment')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->integer('total_duration');
            $table->date('booking_date');
            $table->string('time_slot');
            $table->enum('service_type', ['home', 'salon']);
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_bookings');
    }
};
