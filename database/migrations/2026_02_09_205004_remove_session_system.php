<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('pos_session_id');
        });

        Schema::dropIfExists('pos_sessions');
    }

    public function down(): void
    {
        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pos_point_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->string('status')->default('open');
            $table->string('closed_type')->nullable();
            $table->integer('orders_count')->default(0);
            $table->decimal('orders_total', 10, 3)->default(0);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('pos_session_id')->nullable()->after('order_number')->constrained()->nullOnDelete();
        });
    }
};
