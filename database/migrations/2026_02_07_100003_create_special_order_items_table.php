<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('special_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 3);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('special_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_order_items');
    }
};
