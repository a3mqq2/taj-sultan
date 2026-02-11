<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            // البرجر
            ['name' => 'برجر كلاسيك', 'price' => 18.00, 'category' => 'burgers', 'type' => 'piece'],
            ['name' => 'برجر دبل', 'price' => 25.00, 'category' => 'burgers', 'type' => 'piece'],
            ['name' => 'برجر تشيز', 'price' => 22.00, 'category' => 'burgers', 'type' => 'piece'],
            ['name' => 'برجر مشروم', 'price' => 24.00, 'category' => 'burgers', 'type' => 'piece'],
            ['name' => 'برجر سبايسي', 'price' => 23.00, 'category' => 'burgers', 'type' => 'piece'],

            // الساندويتشات
            ['name' => 'ساندويتش دجاج', 'price' => 15.00, 'category' => 'sandwiches', 'type' => 'piece'],
            ['name' => 'ساندويتش لحم', 'price' => 18.00, 'category' => 'sandwiches', 'type' => 'piece'],
            ['name' => 'ساندويتش فلافل', 'price' => 10.00, 'category' => 'sandwiches', 'type' => 'piece'],
            ['name' => 'ساندويتش شاورما', 'price' => 14.00, 'category' => 'sandwiches', 'type' => 'piece'],

            // الوجبات
            ['name' => 'وجبة برجر', 'price' => 28.00, 'category' => 'meals', 'type' => 'piece'],
            ['name' => 'وجبة دجاج', 'price' => 32.00, 'category' => 'meals', 'type' => 'piece'],
            ['name' => 'وجبة عائلية', 'price' => 75.00, 'category' => 'meals', 'type' => 'piece'],

            // المشروبات
            ['name' => 'بيبسي', 'price' => 5.00, 'category' => 'drinks', 'type' => 'piece'],
            ['name' => 'ميرندا', 'price' => 5.00, 'category' => 'drinks', 'type' => 'piece'],
            ['name' => 'سفن أب', 'price' => 5.00, 'category' => 'drinks', 'type' => 'piece'],
            ['name' => 'عصير برتقال', 'price' => 8.00, 'category' => 'drinks', 'type' => 'piece'],
            ['name' => 'عصير مانجو', 'price' => 8.00, 'category' => 'drinks', 'type' => 'piece'],
            ['name' => 'ماء', 'price' => 2.00, 'category' => 'drinks', 'type' => 'piece'],

            // الحلويات
            ['name' => 'آيس كريم', 'price' => 10.00, 'category' => 'desserts', 'type' => 'piece'],
            ['name' => 'كيك شوكولاتة', 'price' => 15.00, 'category' => 'desserts', 'type' => 'piece'],
            ['name' => 'تشيز كيك', 'price' => 18.00, 'category' => 'desserts', 'type' => 'piece'],

            // المقبلات
            ['name' => 'بطاطس مقلية', 'price' => 8.00, 'category' => 'appetizers', 'type' => 'piece'],
            ['name' => 'ناجتس دجاج', 'price' => 12.00, 'category' => 'appetizers', 'type' => 'piece'],
            ['name' => 'أصابع موزاريلا', 'price' => 14.00, 'category' => 'appetizers', 'type' => 'piece'],
            ['name' => 'حلقات بصل', 'price' => 10.00, 'category' => 'appetizers', 'type' => 'piece'],

            // السلطات
            ['name' => 'سلطة خضراء', 'price' => 12.00, 'category' => 'salads', 'type' => 'piece'],
            ['name' => 'سلطة سيزر', 'price' => 16.00, 'category' => 'salads', 'type' => 'piece'],
            ['name' => 'سلطة كول سلو', 'price' => 8.00, 'category' => 'salads', 'type' => 'piece'],

            // الإضافات
            ['name' => 'جبنة إضافية', 'price' => 3.00, 'category' => 'extras', 'type' => 'piece'],
            ['name' => 'صوص سبايسي', 'price' => 2.00, 'category' => 'extras', 'type' => 'piece'],
            ['name' => 'صوص ثوم', 'price' => 2.00, 'category' => 'extras', 'type' => 'piece'],
        ];

        foreach ($products as $product) {
            $categorySlug = $product['category'];
            unset($product['category']);

            if (isset($categories[$categorySlug])) {
                $product['category_id'] = $categories[$categorySlug]->id;
                $product['slug'] = \Illuminate\Support\Str::slug($product['name']) . '-' . \Illuminate\Support\Str::random(5);
                $product['is_active'] = true;

                Product::create($product);
            }
        }
    }
}
