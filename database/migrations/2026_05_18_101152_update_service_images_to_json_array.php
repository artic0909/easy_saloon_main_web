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
        Schema::table('services', function (Blueprint $table) {
            $table->json('images')->nullable()->after('image');
        });

        // Migrate existing strings to json array
        \Illuminate\Support\Facades\DB::table('services')->whereNotNull('image')->get()->each(function ($service) {
            \Illuminate\Support\Facades\DB::table('services')
                ->where('id', $service->id)
                ->update(['images' => json_encode([$service->image])]);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('image')->nullable()->after('images');
        });

        \Illuminate\Support\Facades\DB::table('services')->whereNotNull('images')->get()->each(function ($service) {
            $images = json_decode($service->images, true);
            \Illuminate\Support\Facades\DB::table('services')
                ->where('id', $service->id)
                ->update(['image' => !empty($images) ? $images[0] : null]);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
