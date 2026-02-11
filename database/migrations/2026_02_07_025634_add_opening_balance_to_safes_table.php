<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('safes', function (Blueprint $table) {
            $table->decimal('opening_balance', 12, 2)->default(0)->after('description');
            $table->decimal('current_balance', 12, 2)->default(0)->after('opening_balance');
            $table->boolean('opening_balance_locked')->default(false)->after('current_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safes', function (Blueprint $table) {
            $table->dropColumn(['opening_balance', 'current_balance', 'opening_balance_locked']);
        });
    }
};
