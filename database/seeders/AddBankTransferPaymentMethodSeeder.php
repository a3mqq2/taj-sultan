<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class AddBankTransferPaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $maxOrder = PaymentMethod::max('sort_order') ?? 0;

        PaymentMethod::firstOrCreate(
            ['slug' => 'bank-transfer'],
            ['name' => 'تحويل مصرفي', 'sort_order' => $maxOrder + 1]
        );
    }
}
