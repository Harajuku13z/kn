<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les articles existants
        Article::truncate();
        
        // Articles de type vêtement
        $clothingArticles = [
            [
                'name' => 'T-shirt',
                'price' => 500,
                'price_text' => '500 FCFA',
                'image_path' => 'img/articles/tshirt.jpg',
                'weight_text' => '0.2 kg',
                'type' => ['clothing', 'vêtement'],
                'usage' => ['quotidien'],
                'weight_class' => 'léger',
                'average_weight' => 0.2,
                'is_active' => true
            ],
            [
                'name' => 'Chemise',
                'price' => 800,
                'price_text' => '800 FCFA',
                'image_path' => 'img/articles/chemise.jpg',
                'weight_text' => '0.3 kg',
                'type' => ['clothing', 'vêtement'],
                'usage' => ['quotidien', 'travail'],
                'weight_class' => 'léger',
                'average_weight' => 0.3,
                'is_active' => true
            ],
            [
                'name' => 'Pantalon',
                'price' => 1000,
                'price_text' => '1000 FCFA',
                'image_path' => 'img/articles/pantalon.jpg',
                'weight_text' => '0.5 kg',
                'type' => ['clothing', 'vêtement'],
                'usage' => ['quotidien', 'travail'],
                'weight_class' => 'moyen',
                'average_weight' => 0.5,
                'is_active' => true
            ],
            [
                'name' => 'Jean',
                'price' => 1200,
                'price_text' => '1200 FCFA',
                'image_path' => 'img/articles/jean.jpg',
                'weight_text' => '0.6 kg',
                'type' => ['clothing', 'vêtement'],
                'usage' => ['quotidien'],
                'weight_class' => 'moyen',
                'average_weight' => 0.6,
                'is_active' => true
            ],
            [
                'name' => 'Robe',
                'price' => 1500,
                'price_text' => '1500 FCFA',
                'image_path' => 'img/articles/robe.jpg',
                'weight_text' => '0.6 kg',
                'type' => ['clothing', 'vêtement'],
                'usage' => ['occasion', 'quotidien'],
                'weight_class' => 'moyen',
                'average_weight' => 0.6,
                'is_active' => true
            ],
            [
                'name' => 'Veste',
                'price' => 2000,
                'price_text' => '2000 FCFA',
                'image_path' => 'img/articles/veste.jpg',
                'weight_text' => '0.8 kg',
                'type' => ['clothing', 'vêtement'],
                'usage' => ['travail', 'occasion'],
                'weight_class' => 'lourd',
                'average_weight' => 0.8,
                'is_active' => true
            ],
        ];
        
        // Articles de type linge de maison
        $householdArticles = [
            [
                'name' => 'Draps (paire)',
                'price' => 2500,
                'price_text' => '2500 FCFA',
                'image_path' => 'img/articles/draps.jpg',
                'weight_text' => '1.5 kg',
                'type' => ['household', 'linge', 'maison'],
                'usage' => ['quotidien'],
                'weight_class' => 'très lourd',
                'average_weight' => 1.5,
                'is_active' => true
            ],
            [
                'name' => 'Serviette',
                'price' => 800,
                'price_text' => '800 FCFA',
                'image_path' => 'img/articles/serviette.jpg',
                'weight_text' => '0.4 kg',
                'type' => ['household', 'linge', 'maison'],
                'usage' => ['quotidien'],
                'weight_class' => 'moyen',
                'average_weight' => 0.4,
                'is_active' => true
            ],
            [
                'name' => 'Taie d\'oreiller',
                'price' => 500,
                'price_text' => '500 FCFA',
                'image_path' => 'img/articles/taie.jpg',
                'weight_text' => '0.2 kg',
                'type' => ['household', 'linge', 'maison'],
                'usage' => ['quotidien'],
                'weight_class' => 'léger',
                'average_weight' => 0.2,
                'is_active' => true
            ],
            [
                'name' => 'Housse de couette',
                'price' => 2000,
                'price_text' => '2000 FCFA',
                'image_path' => 'img/articles/housse.jpg',
                'weight_text' => '1.0 kg',
                'type' => ['household', 'linge', 'maison'],
                'usage' => ['quotidien'],
                'weight_class' => 'lourd',
                'average_weight' => 1.0,
                'is_active' => true
            ],
        ];
        
        // Insérer tous les articles
        foreach (array_merge($clothingArticles, $householdArticles) as $article) {
            Article::create($article);
        }
    }
} 