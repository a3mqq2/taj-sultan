<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_point_product', function (Blueprint $table) {
            $table->foreignId('pos_point_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->primary(['pos_point_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_point_product');
    }
};
