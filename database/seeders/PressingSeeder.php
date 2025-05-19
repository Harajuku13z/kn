<?php

namespace Database\Seeders;

use App\Models\Pressing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PressingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pressings = [
            [
                'name' => 'Speed Pressing',
                'slug' => 'speed-pressing',
                'address' => '123 Rue du Commerce, Abidjan',
                'phone' => '+225 07 01 02 03',
                'email' => 'contact@speed-pressing.com',
                'description' => 'Service de pressing rapide et de qualité',
                'opening_hours' => 'Lun-Sam: 8h-18h, Dim: Fermé',
                'is_active' => true,
                'commission_rate' => 10.0,
                'neighborhood' => 'Cocody',
                'rating' => 4.5,
                'reviews_count' => 124,
                'is_express' => true,
                'has_delivery' => true,
                'eco_friendly' => false,
                'delivery_time' => '24h-48h',
            ],
            [
                'name' => 'Clean Express',
                'slug' => 'clean-express',
                'address' => '45 Avenue du Commerce, Abidjan',
                'phone' => '+225 07 04 05 06',
                'email' => 'info@clean-express.com',
                'description' => 'Service de nettoyage professionnel pour tous vos vêtements',
                'opening_hours' => 'Lun-Sam: 7h-19h, Dim: 9h-13h',
                'is_active' => true,
                'commission_rate' => 12.0,
                'neighborhood' => 'Plateau',
                'rating' => 4.2,
                'reviews_count' => 87,
                'is_express' => true,
                'has_delivery' => true,
                'eco_friendly' => true,
                'delivery_time' => '24h-48h',
            ],
            [
                'name' => 'Eco Pressing',
                'slug' => 'eco-pressing',
                'address' => '78 Rue des Jardins, Abidjan',
                'phone' => '+225 07 07 08 09',
                'email' => 'contact@eco-pressing.com',
                'description' => 'Pressing écologique utilisant des produits respectueux de l\'environnement',
                'opening_hours' => 'Lun-Ven: 8h-18h, Sam: 9h-16h, Dim: Fermé',
                'is_active' => true,
                'commission_rate' => 15.0,
                'neighborhood' => 'Cocody',
                'rating' => 4.7,
                'reviews_count' => 92,
                'is_express' => false,
                'has_delivery' => true,
                'eco_friendly' => true,
                'delivery_time' => '48h-72h',
            ],
            [
                'name' => 'Prestige Pressing',
                'slug' => 'prestige-pressing',
                'address' => '12 Boulevard de la République, Abidjan',
                'phone' => '+225 07 10 11 12',
                'email' => 'info@prestige-pressing.com',
                'description' => 'Service de pressing haut de gamme pour vos vêtements délicats',
                'opening_hours' => 'Lun-Sam: 8h-19h, Dim: Fermé',
                'is_active' => true,
                'commission_rate' => 18.0,
                'neighborhood' => 'Plateau',
                'rating' => 4.8,
                'reviews_count' => 145,
                'is_express' => false,
                'has_delivery' => true,
                'eco_friendly' => false,
                'delivery_time' => '48h-72h',
            ],
            [
                'name' => 'Fresh Clean',
                'slug' => 'fresh-clean',
                'address' => '34 Rue des Fleurs, Abidjan',
                'phone' => '+225 07 13 14 15',
                'email' => 'contact@fresh-clean.com',
                'description' => 'Pressing rapide et économique pour tous vos besoins',
                'opening_hours' => 'Lun-Sam: 7h30-19h30, Dim: Fermé',
                'is_active' => true,
                'commission_rate' => 10.0,
                'neighborhood' => 'Marcory',
                'rating' => 4.0,
                'reviews_count' => 78,
                'is_express' => true,
                'has_delivery' => true,
                'eco_friendly' => false,
                'delivery_time' => '24h-48h',
            ],
            [
                'name' => 'Royal Pressing',
                'slug' => 'royal-pressing',
                'address' => '56 Avenue des Palmiers, Abidjan',
                'phone' => '+225 07 16 17 18',
                'email' => 'info@royal-pressing.com',
                'description' => 'Service de pressing de luxe pour vos vêtements précieux',
                'opening_hours' => 'Lun-Ven: 8h-18h, Sam: 9h-17h, Dim: Fermé',
                'is_active' => true,
                'commission_rate' => 20.0,
                'neighborhood' => 'Riviera',
                'rating' => 4.9,
                'reviews_count' => 112,
                'is_express' => false,
                'has_delivery' => true,
                'eco_friendly' => true,
                'delivery_time' => '48h-72h',
            ],
        ];

        foreach ($pressings as $pressingData) {
            Pressing::create($pressingData);
        }
    }
}
