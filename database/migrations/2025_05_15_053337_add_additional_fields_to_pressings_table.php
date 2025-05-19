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
        Schema::table('pressings', function (Blueprint $table) {
            $table->string('image')->nullable()->after('logo');
            $table->string('neighborhood')->nullable()->after('image');
            $table->float('rating', 3, 1)->default(0)->after('neighborhood');
            $table->integer('reviews_count')->default(0)->after('rating');
            $table->boolean('is_express')->default(false)->after('reviews_count');
            $table->boolean('has_delivery')->default(true)->after('is_express');
            $table->boolean('eco_friendly')->default(false)->after('has_delivery');
            $table->string('delivery_time')->nullable()->after('eco_friendly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pressings', function (Blueprint $table) {
            $table->dropColumn([
                'image',
                'neighborhood',
                'rating',
                'reviews_count',
                'is_express',
                'has_delivery',
                'eco_friendly',
                'delivery_time',
            ]);
        });
    }
};
