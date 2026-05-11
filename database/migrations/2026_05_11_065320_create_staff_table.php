<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('salon_id')->nullable()->constrained('salons')->onDelete('set null');
            $table->string('designation')->nullable();
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
