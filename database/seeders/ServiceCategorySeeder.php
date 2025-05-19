<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Vêtements',
                'description' => 'Services pour vos vêtements quotidiens',
                'icon' => 'bi-shirt',
            ],
            [
                'name' => 'Linge de maison',
                'description' => 'Services pour le linge de maison',
                'icon' => 'bi-house-heart',
            ],
            [
                'name' => 'Costumes & Tenues de cérémonie',
                'description' => 'Services spécialisés pour vos tenues formelles',
                'icon' => 'bi-suit-heart',
            ],
            [
                'name' => 'Articles spéciaux',
                'description' => 'Services pour vos articles délicats ou spécifiques',
                'icon' => 'bi-stars',
            ],
            [
                'name' => 'Chaussures',
                'description' => 'Services pour l\'entretien de vos chaussures',
                'icon' => 'bi-bootstrap-reboot',
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}
