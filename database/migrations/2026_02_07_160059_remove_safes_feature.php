<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('special_order_payments', function (Blueprint $table) {
            $table->dropForeign(['safe_id']);
            $table->dropColumn('safe_id');
        });

        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->dropForeign(['safe_id']);
            $table->dropColumn('safe_id');
        });

        Schema::dropIfExists('safe_transactions');
        Schema::dropIfExists('safe_payment_method');
        Schema::dropIfExists('safe_user');
        Schema::dropIfExists('safes');
    }

    public function down(): void
    {
        Schema::create('safes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->boolean('opening_balance_locked')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('safe_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['safe_id', 'user_id']);
        });

        Schema::create('safe_payment_method', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['safe_id', 'payment_method_id']);
        });

        Schema::create('safe_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['opening', 'deposit', 'withdraw', 'sale', 'refund']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
            $table->index(['safe_id', 'created_at']);
        });

        Schema::table('special_order_payments', function (Blueprint $table) {
            $table->foreignId('safe_id')->after('payment_method_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->foreignId('safe_id')->nullable()->after('payment_method_id')->constrained()->nullOnDelete();
        });
    }
};
