<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->decimal('stock_before', 10, 3)->default(0)->change();
            $table->decimal('stock_after', 10, 3)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->integer('stock_before')->change();
            $table->integer('stock_after')->change();
        });
    }
};
