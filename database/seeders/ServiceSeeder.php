<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $hair = Category::where('slug', 'hair-care')->first();
        $makeup = Category::where('slug', 'makeup')->first();

        if ($hair) {
            $services = [
                ['name' => 'Premium Haircut', 'duration' => 45, 'price' => 499, 'original' => 699],
                ['name' => 'Hair Coloring', 'duration' => 120, 'price' => 1499, 'original' => 1999],
                ['name' => 'Hair Spa', 'duration' => 60, 'price' => 899, 'original' => 1299],
            ];

            foreach ($services as $service) {
                Service::create([
                    'name' => $service['name'],
                    'slug' => Str::slug($service['name']),
                    'category_id' => $hair->id,
                    'duration_minutes' => $service['duration'],
                    'sale_price' => $service['price'],
                    'original_price' => $service['original'],
                    'details' => 'Professional service using premium products.',
                    'is_active' => true,
                ]);
            }
        }

        if ($makeup) {
            $services = [
                ['name' => 'Party Makeup', 'duration' => 90, 'price' => 2499, 'original' => 3499],
                ['name' => 'Bridal Makeup', 'duration' => 180, 'price' => 9999, 'original' => 14999],
            ];

            foreach ($services as $service) {
                Service::create([
                    'name' => $service['name'],
                    'slug' => Str::slug($service['name']),
                    'category_id' => $makeup->id,
                    'duration_minutes' => $service['duration'],
                    'sale_price' => $service['price'],
                    'original_price' => $service['original'],
                    'details' => 'Complete transformation for your special day.',
                    'is_active' => true,
                ]);
            }
        }
    }
}
