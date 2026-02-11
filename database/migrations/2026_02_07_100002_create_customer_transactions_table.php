<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['payment', 'order', 'refund']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('safe_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'created_at']);
            $table->index('type');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_transactions');
    }
};
