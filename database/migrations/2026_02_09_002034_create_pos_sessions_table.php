<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pos_point_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->enum('closed_type', ['manual', 'auto', 'timeout'])->nullable();
            $table->unsignedInteger('orders_count')->default(0);
            $table->decimal('orders_total', 12, 3)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'pos_point_id', 'status']);
            $table->index(['pos_point_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_sessions');
    }
};
