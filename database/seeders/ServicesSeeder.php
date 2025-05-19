<?php

namespace Database\Seeders;

use App\Models\Pressing;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all pressings and categories
        $pressings = Pressing::all();
        $categories = ServiceCategory::all();
        
        if ($pressings->isEmpty()) {
            $this->command->info('No pressings found. Please run the pressing seeder first.');
            return;
        }
        
        if ($categories->isEmpty()) {
            $this->command->info('No service categories found. Please run the service category seeder first.');
            return;
        }
        
        // Sample services by category
        $servicesByCategory = [
            'Vêtements' => [
                ['name' => 'Chemise', 'price' => 1500, 'description' => 'Lavage et repassage de chemise'],
                ['name' => 'Pantalon', 'price' => 1800, 'description' => 'Nettoyage et repassage de pantalon'],
                ['name' => 'T-shirt', 'price' => 1000, 'description' => 'Lavage de t-shirt'],
                ['name' => 'Robe simple', 'price' => 2500, 'description' => 'Nettoyage et repassage de robe simple'],
                ['name' => 'Jupe', 'price' => 1500, 'description' => 'Nettoyage et repassage de jupe'],
            ],
            'Linge de maison' => [
                ['name' => 'Drap', 'price' => 2000, 'description' => 'Lavage et repassage de drap'],
                ['name' => 'Housse de couette', 'price' => 3000, 'description' => 'Lavage de housse de couette'],
                ['name' => 'Taie d\'oreiller', 'price' => 800, 'description' => 'Lavage de taie d\'oreiller'],
                ['name' => 'Nappe', 'price' => 2500, 'description' => 'Lavage et repassage de nappe'],
                ['name' => 'Serviette', 'price' => 1000, 'description' => 'Lavage de serviette'],
            ],
            'Costumes & Tenues de cérémonie' => [
                ['name' => 'Costume 2 pièces', 'price' => 5000, 'description' => 'Nettoyage à sec et repassage de costume 2 pièces'],
                ['name' => 'Costume 3 pièces', 'price' => 6500, 'description' => 'Nettoyage à sec et repassage de costume 3 pièces'],
                ['name' => 'Robe de soirée', 'price' => 7000, 'description' => 'Nettoyage à sec et repassage de robe de soirée'],
                ['name' => 'Tenue traditionnelle', 'price' => 6000, 'description' => 'Nettoyage et repassage de tenue traditionnelle'],
                ['name' => 'Cravate', 'price' => 1000, 'description' => 'Nettoyage de cravate'],
            ],
            'Articles spéciaux' => [
                ['name' => 'Manteau', 'price' => 4500, 'description' => 'Nettoyage à sec de manteau'],
                ['name' => 'Couette', 'price' => 6000, 'description' => 'Lavage de couette'],
                ['name' => 'Oreiller', 'price' => 3500, 'description' => 'Lavage d\'oreiller'],
                ['name' => 'Rideau (m²)', 'price' => 2000, 'description' => 'Nettoyage et repassage de rideau au m²'],
                ['name' => 'Tapis (m²)', 'price' => 3000, 'description' => 'Nettoyage de tapis au m²'],
            ],
            'Chaussures' => [
                ['name' => 'Chaussures en cuir', 'price' => 3500, 'description' => 'Nettoyage et cirage de chaussures en cuir'],
                ['name' => 'Baskets', 'price' => 3000, 'description' => 'Nettoyage de baskets'],
                ['name' => 'Bottes', 'price' => 4000, 'description' => 'Nettoyage et entretien de bottes'],
                ['name' => 'Chaussures en daim', 'price' => 4500, 'description' => 'Nettoyage spécial pour chaussures en daim'],
                ['name' => 'Sandales', 'price' => 2500, 'description' => 'Nettoyage de sandales'],
            ],
        ];
        
        // Create services for each pressing with some price variation
        foreach ($pressings as $pressing) {
            foreach ($categories as $category) {
                // Find services for this category
                $services = $servicesByCategory[$category->name] ?? [];
                
                foreach ($services as $serviceData) {
                    // Add some price variation between pressings (±20%)
                    $priceVariation = rand(-20, 20) / 100;
                    $price = $serviceData['price'] * (1 + $priceVariation);
                    $price = ceil($price / 100) * 100; // Round to nearest 100
                    
                    Service::create([
                        'name' => $serviceData['name'],
                        'description' => $serviceData['description'],
                        'price' => $price,
                        'pressing_id' => $pressing->id,
                        'category_id' => $category->id,
                        'is_active' => true,
                        'estimated_time' => rand(1, 3) . ' jour(s)',
                    ]);
                }
            }
        }
    }
}
