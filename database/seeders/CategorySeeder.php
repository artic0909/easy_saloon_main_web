<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hair Care', 'image' => 'cat-hair.png'],
            ['name' => 'Makeup', 'image' => 'cat-makeup.png'],
            ['name' => 'Facial & Spa', 'image' => 'cat-facial.png'],
            ['name' => 'Men\'s Grooming', 'image' => 'cat-men.png'],
            ['name' => 'Nail Art', 'image' => 'cat-nail.png'],
            ['name' => 'Body Massage', 'image' => 'cat-massage.png'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'image' => $category['image'],
                'is_active' => true,
            ]);
        }
    }
}
