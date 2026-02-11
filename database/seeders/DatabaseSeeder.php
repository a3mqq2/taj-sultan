<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'name' => 'Administrator',
            'password' => Hash::make(123123123),
        ]);

        $this->call([
            CategorySeeder::class,
            PaymentMethodSeeder::class,
            EventTypeSeeder::class,
        ]);
    }
}
