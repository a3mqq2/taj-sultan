<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('special_order_items', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('product_id');
            $table->boolean('is_weight')->default(false)->after('total_price');
        });
    }

    public function down(): void
    {
        Schema::table('special_order_items', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'is_weight']);
        });
    }
};
