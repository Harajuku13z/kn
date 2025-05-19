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
            // Check if the column doesn't already exist first
            if (!Schema::hasColumn('orders', 'delivery_voucher_id')) {
                $table->foreignId('delivery_voucher_id')->nullable()->after('payment_status')->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_voucher_id']);
            $table->dropColumn('delivery_voucher_id');
        });
    }
};
