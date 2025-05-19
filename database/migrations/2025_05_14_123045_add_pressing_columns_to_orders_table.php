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
            $table->string('order_type')->default('classic')->after('id'); // classic, pressing
            $table->foreignId('pressing_id')->nullable()->after('order_type')->constrained()->nullOnDelete();
            $table->text('pressing_notes')->nullable()->after('special_instructions'); // Instructions spécifiques au pressing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['pressing_id']);
            $table->dropColumn(['order_type', 'pressing_id', 'pressing_notes']);
        });
    }
};
