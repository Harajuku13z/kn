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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'pressing_id')) {
                $table->foreignId('pressing_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            }
            
            if (!Schema::hasColumn('orders', 'order_type')) {
                $table->enum('order_type', ['kilogram', 'pressing'])->default('kilogram')->after('pressing_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'pressing_id')) {
                $table->dropForeign(['pressing_id']);
                $table->dropColumn('pressing_id');
            }
            
            if (Schema::hasColumn('orders', 'order_type')) {
                $table->dropColumn('order_type');
            }
        });
    }
};
