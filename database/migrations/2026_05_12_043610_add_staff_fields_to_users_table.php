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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('salon_id')->nullable()->after('role')->constrained('salons')->onDelete('set null');
            $table->string('designation')->nullable()->after('salon_id');
            $table->text('bio')->nullable()->after('designation');
            $table->integer('experience_years')->default(0)->after('bio');
            $table->boolean('is_available')->default(true)->after('experience_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['salon_id']);
            $table->dropColumn(['salon_id', 'designation', 'bio', 'experience_years', 'is_available']);
        });
    }
};
