<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_fees', function (Blueprint $table) {
            $table->id();
            $table->string('neighborhood')->unique(); // Nom du quartier (Bacongo, Poto-Poto, etc.)
            $table->integer('fee')->default(750); // Frais de livraison en FCFA
            $table->text('description')->nullable(); // Description ou note
            $table->boolean('is_active')->default(true); // État actif ou non
            $table->timestamps();
        });
        
        // Insertion des quartiers par défaut
        DB::table('delivery_fees')->insert([
            ['neighborhood' => 'Bacongo', 'fee' => 750, 'created_at' => now(), 'updated_at' => now()],
            ['neighborhood' => 'Makélékélé', 'fee' => 750, 'created_at' => now(), 'updated_at' => now()],
            ['neighborhood' => 'Poto-Poto', 'fee' => 750, 'created_at' => now(), 'updated_at' => now()],
            ['neighborhood' => 'Moungali', 'fee' => 750, 'created_at' => now(), 'updated_at' => now()],
            ['neighborhood' => 'Ouenzé', 'fee' => 750, 'created_at' => now(), 'updated_at' => now()],
            ['neighborhood' => 'Talangaï', 'fee' => 1000, 'created_at' => now(), 'updated_at' => now()],
            ['neighborhood' => 'Mfilou', 'fee' => 1000, 'created_at' => now(), 'updated_at' => now()],
            ['neighborhood' => 'Madibou', 'fee' => 1000, 'created_at' => now(), 'updated_at' => now()],
            ['neighborhood' => 'Djiri', 'fee' => 1000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_fees');
    }
};
