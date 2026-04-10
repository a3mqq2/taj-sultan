<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            ['name' => 'كاش', 'slug' => 'cash', 'sort_order' => 1],
            ['name' => 'يسر باي - تجاري', 'slug' => 'ysr-pay-tejari', 'sort_order' => 2],
            ['name' => 'مصرفي باي - الجمهورية', 'slug' => 'masrafi-pay-jumhoria', 'sort_order' => 3],
            ['name' => 'بطاقة مصرفية', 'slug' => 'bank-card', 'sort_order' => 4],
            ['name' => 'موبي كاش - الوحدة', 'slug' => 'mobi-cash-wahda', 'sort_order' => 5],
            ['name' => 'موبي ناب - شمال افريقيا', 'slug' => 'mobi-nab-north-africa', 'sort_order' => 6],
            ['name' => 'ون باي', 'slug' => 'one-pay', 'sort_order' => 7],
            ['name' => 'ادفع لي - التجارة والتنمية', 'slug' => 'edfali-commerce-dev', 'sort_order' => 8],
            ['name' => 'سداد اونلاين (المدار)', 'slug' => 'sadad-online-madar', 'sort_order' => 9],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
