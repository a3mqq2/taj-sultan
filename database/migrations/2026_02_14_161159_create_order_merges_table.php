<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_merges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('child_order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('merged_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique('child_order_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('merged_into')->nullable()->after('status')->constrained('orders')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['merged_into']);
            $table->dropColumn('merged_into');
        });

        Schema::dropIfExists('order_merges');
    }
};
