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
            // Ajouter la colonne coupon_id après payment_status
            $table->foreignId('coupon_id')->nullable()->after('payment_status')->constrained()->onDelete('set null');
            
            // Ajouter la colonne delivery_voucher_id après coupon_id
            $table->foreignId('delivery_voucher_id')->nullable()->after('coupon_id')->constrained()->onDelete('set null');
            
            // Ajouter la colonne discount_amount après final_price
            $table->decimal('discount_amount', 10, 2)->default(0)->after('final_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn('coupon_id');
            
            $table->dropForeign(['delivery_voucher_id']);
            $table->dropColumn('delivery_voucher_id');
            
            $table->dropColumn('discount_amount');
        });
    }
};
