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
        Schema::create('pressings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->text('opening_hours')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('commission_rate', 5, 2)->default(0); // Taux de commission en pourcentage
            $table->json('coverage_areas')->nullable(); // Zones desservies (format JSON)
            $table->string('logo')->nullable(); // Chemin vers le logo du pressing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pressings');
    }
};
