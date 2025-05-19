<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\DeliveryFee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CitiesAndDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we need to convert old neighborhood data
        if (Schema::hasColumn('delivery_fees', 'neighborhood') && !Schema::hasColumn('delivery_fees', 'district')) {
            $this->convertOldData();
            return;
        }
        
        // Disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Delete existing fees if they exist
        DeliveryFee::query()->delete();
        
        // Delete existing cities if they exist
        City::query()->delete();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Create cities
        $brazzaville = City::create([
            'name' => 'Brazzaville',
            'description' => 'Capitale de la République du Congo',
            'is_active' => true,
        ]);

        $pointeNoire = City::create([
            'name' => 'Pointe-Noire',
            'description' => 'Capitale économique de la République du Congo',
            'is_active' => true,
        ]);

        // Brazzaville districts with delivery fees - organized by arrondissements and popular neighborhoods
        // Fees based on distance from center: 750-900 (central), 900-1200 (mid distance), 1200-1500 (far)
        $brazzavilleDistricts = [
            // Arrondissements (Administrative districts)
            'Makélékélé' => 850,
            'Bacongo' => 750,
            'Poto-Poto' => 750,
            'Moungali' => 800,
            'Ouenzé' => 850,
            'Talangaï' => 1000,
            'Mfilou' => 1100,
            'Madibou' => 1200,
            'Djiri' => 1500,
            
            // Popular neighborhoods and areas
            'Plateau des 15 ans' => 850,
            'Dix Maisons' => 850,
            'Sukissa' => 800,
            'Kingouari' => 900,
            'Dragage (Bernard Kolelas)' => 900,
            'La Tsiemé' => 1100,
            'Matour' => 950,
            'Dahomey' => 850,
            'Congo-Japon' => 950,
            'Boubissi' => 1000,
            'Wenzé' => 850,
            'Marché Total' => 800,
            'OCH Moungali' => 800,
            'Texaco' => 900,
            'Bifouiti' => 950,
            'Manzanza' => 1000,
            'Soprogi' => 1050,
            'Cinq sur Cinq (5/5)' => 1000,
            'Ngamakosso' => 1200,
            'Brasco' => 900,
            'Ngangouoni' => 1100,
            'Kilomètre 6 (KM6)' => 1100,
            'Mikalou' => 1200,
            'Ngamaba' => 1100,
            'Patte d\'Oie' => 900,
            'Nkombo' => 1150,
            'Kinsoundi-Barrage' => 950,
            'Kintélé' => 1500,
            'Lifoula' => 1300,
            'Case PMP' => 1200,
            'Marché de la Glacière' => 850,
            'Avenue de la Paix' => 800,
            'Rond-point de la Gare' => 800,
            'Moungali Gendarmerie' => 850,
            'Talangaï-Gaîté' => 1050,
            'Talangaï-Plateau' => 1050,
            'Cosmos' => 900,
            'Maya-Maya' => 950,
            'Cité des Anciens Combattants' => 850,
            'Camp Clairon' => 900,
        ];

        foreach ($brazzavilleDistricts as $district => $fee) {
            DeliveryFee::create([
                'city_id' => $brazzaville->id,
                'district' => $district,
                'fee' => $fee,
                'description' => 'Frais de livraison pour ' . $district . ' à Brazzaville',
                'is_active' => true,
            ]);
        }

        // Pointe-Noire districts with delivery fees
        $pointeNoireDistricts = [
            'Lumumba' => 650,
            'Plateau' => 700,
            'Tié-Tié' => 750,
            'Loandjili' => 800,
            'Mongo-Mpoukou' => 850,
            'Ngoyo' => 900,
            'Mvou-Mvou' => 700,
            'Tchibamba' => 750,
            'Saint-Pierre' => 800,
            'Grand Marché' => 650,
            'Voungou' => 850,
            'Matende' => 780,
            'Songolo' => 850,
            'Fond Tié-Tié' => 780,
            'Vindoulou' => 900,
        ];

        foreach ($pointeNoireDistricts as $district => $fee) {
            DeliveryFee::create([
                'city_id' => $pointeNoire->id,
                'district' => $district,
                'fee' => $fee,
                'description' => 'Frais de livraison pour ' . $district . ' à Pointe-Noire',
                'is_active' => true,
            ]);
        }
        
        $this->command->info('Added 2 cities with ' . (count($brazzavilleDistricts) + count($pointeNoireDistricts)) . ' districts');
    }
    
    /**
     * Convert old neighborhood data to new city-district structure
     */
    private function convertOldData(): void
    {
        $this->command->info('Converting old neighborhood data to new city-district structure...');
        
        // Create a default city if none exists
        $defaultCity = City::firstOrCreate(
            ['name' => 'Ville par défaut'],
            [
                'description' => 'Ville créée automatiquement pour les quartiers existants',
                'is_active' => true
            ]
        );
        
        // Get all existing neighborhoods
        $neighborhoods = DB::table('delivery_fees')->get();
        
        // For each neighborhood, create a district record with the city_id
        foreach ($neighborhoods as $neighborhood) {
            DB::table('delivery_fees')
                ->where('id', $neighborhood->id)
                ->update([
                    'city_id' => $defaultCity->id,
                    'district' => $neighborhood->neighborhood
                ]);
        }
        
        $this->command->info('Converted ' . $neighborhoods->count() . ' neighborhoods to districts.');
    }
}
