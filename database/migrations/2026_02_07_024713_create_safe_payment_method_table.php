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
        Schema::create('safe_payment_method', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['safe_id', 'payment_method_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safe_payment_method');
    }
};
