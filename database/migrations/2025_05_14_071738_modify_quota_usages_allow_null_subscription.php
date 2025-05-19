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
        Schema::table('quota_usages', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['subscription_id']);
            
            // Change the column to be nullable
            $table->foreignId('subscription_id')->nullable()->change();
            
            // Re-add the foreign key constraint but with onDelete('set null')
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quota_usages', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['subscription_id']);
            
            // Change the column back to non-nullable
            $table->foreignId('subscription_id')->nullable(false)->change();
            
            // Re-add the original foreign key constraint
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
        });
    }
};
