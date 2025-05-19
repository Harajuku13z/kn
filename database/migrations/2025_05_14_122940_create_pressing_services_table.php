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
        Schema::create('pressing_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pressing_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price'); // Prix en FCFA
            $table->string('category')->nullable(); // Chemise, Costume, etc.
            $table->string('image')->nullable(); // Chemin vers l'image du service
            $table->boolean('is_available')->default(true);
            $table->integer('estimated_time')->nullable(); // Temps estimé en heures
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pressing_services');
    }
};
