<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pressing;
use App\Models\PressingService;
use Illuminate\Support\Facades\DB;

class PressingServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les pressings existants
        $pressings = Pressing::all();
        
        if ($pressings->isEmpty()) {
            $this->command->info('Aucun pressing trouvé dans la base de données. Veuillez d\'abord créer des pressings.');
            return;
        }
        
        $this->command->info(count($pressings) . ' pressings trouvés. Ajout de services en cours...');
        
        // Catégories de services
        $categories = [
            'Chemises' => [
                ['name' => 'Chemise / Chemisier', 'price' => 1500, 'estimated_time' => 48],
                ['name' => 'Chemise sur cintre', 'price' => 1800, 'estimated_time' => 24],
                ['name' => 'Chemise pliée', 'price' => 1600, 'estimated_time' => 48],
            ],
            'Pantalons' => [
                ['name' => 'Pantalon classique', 'price' => 2000, 'estimated_time' => 48],
                ['name' => 'Jeans', 'price' => 2200, 'estimated_time' => 48],
                ['name' => 'Pantalon de costume', 'price' => 2500, 'estimated_time' => 24],
            ],
            'Costumes' => [
                ['name' => 'Costume 2 pièces', 'price' => 5000, 'estimated_time' => 72],
                ['name' => 'Costume 3 pièces', 'price' => 6500, 'estimated_time' => 72],
                ['name' => 'Veste de costume', 'price' => 3000, 'estimated_time' => 48],
            ],
            'Robes' => [
                ['name' => 'Robe simple', 'price' => 3000, 'estimated_time' => 48],
                ['name' => 'Robe de soirée', 'price' => 5000, 'estimated_time' => 72],
                ['name' => 'Robe de cérémonie', 'price' => 7000, 'estimated_time' => 96],
            ],
            'Manteaux' => [
                ['name' => 'Manteau court', 'price' => 4000, 'estimated_time' => 72],
                ['name' => 'Manteau long', 'price' => 5000, 'estimated_time' => 72],
                ['name' => 'Imperméable', 'price' => 3500, 'estimated_time' => 48],
            ],
            'Jupes' => [
                ['name' => 'Jupe simple', 'price' => 2000, 'estimated_time' => 48],
                ['name' => 'Jupe plissée', 'price' => 2500, 'estimated_time' => 48],
            ],
            'Accessoires' => [
                ['name' => 'Cravate', 'price' => 1000, 'estimated_time' => 24],
                ['name' => 'Écharpe', 'price' => 1500, 'estimated_time' => 24],
                ['name' => 'Foulard', 'price' => 1200, 'estimated_time' => 24],
            ],
            'Linge de maison' => [
                ['name' => 'Couette 1 personne', 'price' => 4000, 'estimated_time' => 72],
                ['name' => 'Couette 2 personnes', 'price' => 5000, 'estimated_time' => 72],
                ['name' => 'Drap', 'price' => 2000, 'estimated_time' => 48],
                ['name' => 'Housse de couette', 'price' => 3000, 'estimated_time' => 48],
            ],
        ];
        
        $servicesCount = 0;
        
        // Pour chaque pressing, ajouter tous les services
        foreach ($pressings as $pressing) {
            // Supprimer les services existants pour éviter les doublons
            PressingService::where('pressing_id', $pressing->id)->delete();
            
            $this->command->info('Ajout de services pour le pressing : ' . $pressing->name);
            
            foreach ($categories as $categoryName => $services) {
                foreach ($services as $service) {
                    // Ajouter une description basée sur le type de service
                    $description = $this->generateDescription($service['name'], $categoryName);
                    
                    // Variation légère des prix pour chaque pressing (+/- 10%)
                    $priceVariation = rand(-10, 10);
                    $adjustedPrice = max(500, round($service['price'] * (1 + $priceVariation / 100), -2)); // Arrondi à la centaine
                    
                    PressingService::create([
                        'pressing_id' => $pressing->id,
                        'name' => $service['name'],
                        'description' => $description,
                        'price' => $adjustedPrice,
                        'category' => $categoryName,
                        'is_available' => true,
                        'estimated_time' => $service['estimated_time'],
                    ]);
                    
                    $servicesCount++;
                }
            }
        }
        
        $this->command->info('Terminé ! ' . $servicesCount . ' services ont été créés pour ' . count($pressings) . ' pressings.');
    }
    
    /**
     * Génère une description pour un service
     */
    private function generateDescription($serviceName, $category)
    {
        $descriptions = [
            'Chemises' => [
                'Nettoyage et repassage soignés pour une finition impeccable.',
                'Traitement délicat pour préserver la qualité du tissu.',
                'Repassage minutieux avec attention particulière aux cols et manchettes.',
            ],
            'Pantalons' => [
                'Nettoyage professionnel pour éliminer toutes les taches.',
                'Repassage avec pli parfait pour une allure soignée.',
                'Traitement adapté au tissu pour un résultat optimal.',
            ],
            'Costumes' => [
                'Nettoyage à sec professionnel pour préserver la forme et la couleur.',
                'Traitement complet avec attention aux détails et doublures.',
                'Repassage minutieux pour une allure impeccable.',
            ],
            'Robes' => [
                'Nettoyage délicat adapté au tissu et aux ornements.',
                'Traitement professionnel pour préserver la coupe et la couleur.',
                'Finition soignée avec repassage adapté au style.',
            ],
            'Manteaux' => [
                'Nettoyage à sec professionnel pour tous types de manteaux.',
                'Traitement spécial pour les tissus épais et les doublures.',
                'Finition impeccable avec attention aux détails.',
            ],
            'Jupes' => [
                'Nettoyage et repassage adaptés à tous types de jupes.',
                'Traitement délicat pour les tissus plissés ou délicats.',
                'Finition soignée pour un résultat professionnel.',
            ],
            'Accessoires' => [
                'Nettoyage délicat pour préserver les couleurs et les matières.',
                'Traitement adapté aux accessoires fragiles.',
                'Finition soignée pour un résultat impeccable.',
            ],
            'Linge de maison' => [
                'Nettoyage profond pour une fraîcheur retrouvée.',
                'Traitement adapté aux grandes pièces et tissus épais.',
                'Entretien soigné pour prolonger la durée de vie de votre linge.',
            ],
        ];
        
        // Sélectionner une description aléatoire basée sur la catégorie
        $categoryDescriptions = $descriptions[$category] ?? ['Nettoyage et entretien professionnels pour un résultat impeccable.'];
        return $categoryDescriptions[array_rand($categoryDescriptions)];
    }
}
