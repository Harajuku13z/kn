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
            $table->integer('pickup_fee')->default(0)->after('delivery_fee')->comment('Frais de collecte');
            
            // Renommer delivery_fee pour clarifier son rôle
            $table->renameColumn('delivery_fee', 'drop_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('drop_fee', 'delivery_fee');
            $table->dropColumn('pickup_fee');
        });
    }
};
