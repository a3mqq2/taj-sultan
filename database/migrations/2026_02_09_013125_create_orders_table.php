<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('pos_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('pos_point_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('total', 10, 3);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
