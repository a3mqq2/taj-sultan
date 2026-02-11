<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'شرقي', 'slug' => 'oriental', 'sort_order' => 1],
            ['name' => 'غربي', 'slug' => 'western', 'sort_order' => 2],
            ['name' => 'كرواسون', 'slug' => 'croissant', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
