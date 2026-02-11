<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('paid_by')->constrained()->nullOnDelete();
            $table->decimal('amount_received', 10, 3)->nullable()->after('payment_method_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['payment_method_id', 'amount_received']);
        });
    }
};
