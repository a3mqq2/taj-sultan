<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('special_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();
            $table->foreignId('safe_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['special_order_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_order_payments');
    }
};
