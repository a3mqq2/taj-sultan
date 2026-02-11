<?php

namespace Database\Seeders;

use App\Models\PosPoint;
use Illuminate\Database\Seeder;

class PosPointSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPoints = [
            ['name' => 'الغربي', 'slug' => 'gharbi'],
            ['name' => 'الكرواسو', 'slug' => 'croissant'],
            ['name' => 'الشرقي', 'slug' => 'sharqi'],
        ];

        foreach ($defaultPoints as $point) {
            PosPoint::updateOrCreate(
                ['slug' => $point['slug']],
                [
                    'name' => $point['name'],
                    'active' => true,
                    'require_login' => false,
                    'is_default' => true,
                ]
            );
        }
    }
}
