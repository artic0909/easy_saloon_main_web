<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageItem;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $bridal = Service::where('slug', 'bridal-makeup')->first();
        $facial = Service::where('slug', 'premium-haircut')->first(); // Using haircut as placeholder if facial seeder is missed

        if ($bridal && $facial) {
            $package = Package::create([
                'name' => 'Ultimate Wedding Glow',
                'slug' => 'ultimate-wedding-glow',
                'details' => 'The complete package for your big day, including makeup and pre-wedding glow treatment.',
                'original_price' => 12000,
                'sale_price' => 8999,
                'is_active' => true,
            ]);

            PackageItem::create(['package_id' => $package->id, 'service_id' => $bridal->id]);
            PackageItem::create(['package_id' => $package->id, 'service_id' => $facial->id]);
        }

        $haircut = Service::where('slug', 'premium-haircut')->first();
        $coloring = Service::where('slug', 'hair-coloring')->first();

        if ($haircut && $coloring) {
            $package = Package::create([
                'name' => 'Style & Color Combo',
                'slug' => 'style-color-combo',
                'details' => 'Transform your look with a fresh cut and premium coloring service.',
                'original_price' => 2500,
                'sale_price' => 1799,
                'is_active' => true,
            ]);

            PackageItem::create(['package_id' => $package->id, 'service_id' => $haircut->id]);
            PackageItem::create(['package_id' => $package->id, 'service_id' => $coloring->id]);
        }
    }
}
