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
        Schema::create('safe_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['opening', 'deposit', 'withdraw', 'sale', 'refund'])->default('deposit');
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('description');
            $table->string('reference')->nullable();
            $table->timestamps();

            $table->index(['safe_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safe_transactions');
    }
};
