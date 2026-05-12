<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Generate slugs for existing sub_categories
        $subs = DB::table('sub_categories')->get();
        foreach ($subs as $sub) {
            DB::table('sub_categories')->where('id', $sub->id)->update([
                'slug' => Illuminate\Support\Str::slug($sub->name)
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
