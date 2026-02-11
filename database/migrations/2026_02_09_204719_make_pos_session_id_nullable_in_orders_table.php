<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['pos_session_id']);
            $table->foreignId('pos_session_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('pos_session_id')->nullable(false)->change();
            $table->foreign('pos_session_id')->references('id')->on('pos_sessions')->onDelete('cascade');
        });
    }
};
