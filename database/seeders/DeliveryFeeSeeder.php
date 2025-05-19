<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryFee;

class DeliveryFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les données existantes pour éviter les doublons
        DeliveryFee::truncate();
        
        // Liste des quartiers avec leurs frais de livraison
        $neighborhoods = [
            [
                'neighborhood' => 'Bacongo',
                'fee' => 750,
                'description' => 'Quartier au sud de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Poto-Poto',
                'fee' => 750,
                'description' => 'Quartier au centre de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Moungali',
                'fee' => 800,
                'description' => 'Quartier au nord de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Ouenzé',
                'fee' => 800,
                'description' => 'Quartier au nord-est de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Talangaï',
                'fee' => 850,
                'description' => 'Quartier périphérique au nord de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Mfilou',
                'fee' => 850,
                'description' => 'Quartier à l\'ouest de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Djiri',
                'fee' => 900,
                'description' => 'Quartier périphérique au nord de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Madibou',
                'fee' => 900,
                'description' => 'Quartier périphérique au sud de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Plateau des 15 ans',
                'fee' => 800,
                'description' => 'Quartier central de Brazzaville',
                'is_active' => true
            ],
            [
                'neighborhood' => 'Centre-ville',
                'fee' => 750,
                'description' => 'Zone commerciale de Brazzaville',
                'is_active' => true
            ]
        ];
        
        // Insérer les quartiers dans la base de données
        foreach ($neighborhoods as $data) {
            DeliveryFee::create($data);
        }
        
        $this->command->info('Quartiers et frais de livraison insérés avec succès!');
    }
}
