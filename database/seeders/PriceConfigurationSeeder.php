<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceConfiguration;
use App\Models\User;

class PriceConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin s'il n'existe pas déjà
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        // Créer une configuration de prix par défaut si elle n'existe pas déjà
        PriceConfiguration::firstOrCreate(
            ['effective_date' => now()],
            [
                'price_per_kg' => 1000,
                'last_update_reason' => 'Configuration initiale',
                'created_by_user_id' => $admin->id,
            ]
        );
    }
} 