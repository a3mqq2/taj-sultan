<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $eventTypes = [
            ['name' => 'عيد ميلاد', 'sort_order' => 1],
            ['name' => 'زفاف', 'sort_order' => 2],
            ['name' => 'خطوبة', 'sort_order' => 3],
            ['name' => 'تخرج', 'sort_order' => 4],
            ['name' => 'افتتاح', 'sort_order' => 11],
            ['name' => 'مناسبة عائلية', 'sort_order' => 12],
            ['name' => 'أخرى', 'sort_order' => 99],
        ];

        foreach ($eventTypes as $type) {
            EventType::firstOrCreate(
                ['name' => $type['name']],
                ['sort_order' => $type['sort_order'], 'is_active' => true]
            );
        }
    }
}
