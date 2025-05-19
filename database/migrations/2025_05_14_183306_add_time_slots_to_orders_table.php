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
            // Add the missing columns
            $table->string('pickup_time_slot')->nullable()->after('pickup_date');
            $table->string('delivery_time_slot')->nullable()->after('delivery_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove the columns when rolling back
            $table->dropColumn('pickup_time_slot');
            $table->dropColumn('delivery_time_slot');
        });
    }
};
