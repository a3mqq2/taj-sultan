<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('event_type');
            $table->text('description')->nullable();
            $table->date('delivery_date');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2);
            $table->enum('status', ['pending', 'in_progress', 'ready', 'delivered', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index(['status', 'delivery_date']);
            $table->index('customer_name');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_orders');
    }
};
