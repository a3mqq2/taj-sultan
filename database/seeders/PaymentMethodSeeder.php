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
            ['name' => 'ون باي - الجمهورية', 'slug' => 'one-pay-jumhoria', 'sort_order' => 7],
            ['name' => 'ون باي - التجاري', 'slug' => 'one-pay-tejari', 'sort_order' => 8],
            ['name' => 'ون باي - الوحدة', 'slug' => 'one-pay-wahda', 'sort_order' => 9],
            ['name' => 'ون باي - شمال افريقيا', 'slug' => 'one-pay-north-africa', 'sort_order' => 10],
            ['name' => 'ادفع لي - التجارة والتنمية', 'slug' => 'edfali-commerce-dev', 'sort_order' => 11],
            ['name' => 'سداد اونلاين (المدار)', 'slug' => 'sadad-online-madar', 'sort_order' => 12],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
