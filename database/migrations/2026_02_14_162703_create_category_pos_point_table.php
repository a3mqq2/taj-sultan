<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_pos_point', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('pos_point_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['category_id', 'pos_point_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_pos_point');
    }
};
