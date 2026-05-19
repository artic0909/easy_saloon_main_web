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
            $table->boolean('verify')->default(false)->after('otp');
        });

        Schema::table('custom_bookings', function (Blueprint $table) {
            $table->boolean('verify')->default(false)->after('otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('verify');
        });

        Schema::table('custom_bookings', function (Blueprint $table) {
            $table->dropColumn('verify');
        });
    }
};
