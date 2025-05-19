<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DeliveryFeeSeeder;
use Database\Seeders\SupportTicketSeeder;
use Database\Seeders\ArticleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Run the service-related seeders
        $this->call([
            PressingSeeder::class,
            ServiceCategorySeeder::class,
            ServicesSeeder::class,
            SupportTicketSeeder::class,
        ]);

        // Ajout du seeder pour les frais de livraison
        $this->call(DeliveryFeeSeeder::class);

        // Add the ArticleSeeder to the run method
        $this->call(ArticleSeeder::class);
    }
}
