<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionType;

class SubscriptionTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'name' => 'Starter',
                'description' => 'Idéal pour les petits besoins en lavage (1-2 lessives par mois)',
                'quota' => 5,
                'duration' => 30,
                'price' => 2500,
            ],
            [
                'name' => 'Family',
                'description' => 'Parfait pour les familles (3-4 lessives par mois)',
                'quota' => 15,
                'duration' => 30,
                'price' => 6500,
            ],
            [
                'name' => 'Business',
                'description' => 'Pour les besoins professionnels et les grandes familles (5+ lessives par mois)',
                'quota' => 30,
                'duration' => 30,
                'price' => 12000,
            ],
        ];

        foreach ($types as $type) {
            SubscriptionType::create($type);
        }
    }
} 