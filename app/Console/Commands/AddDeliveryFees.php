<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeliveryFee;

class AddDeliveryFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-delivery-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add sample delivery fees for different neighborhoods';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $neighborhoods = [
            'Akwa' => 500,
            'Bonanjo' => 600,
            'Bonapriso' => 700,
            'Bonaberi' => 900,
            'Deido' => 800,
            'Bali' => 550,
            'Makepe' => 850,
            'Ndokoti' => 750,
            'New Bell' => 700,
            'Bonamoussadi' => 950,
            'Logbessou' => 1100,
            'Logpom' => 1000,
            'Kotto' => 950,
            'Mboppi' => 800,
            'PK14' => 1200,
            'PK12' => 1100,
            'PK10' => 1000,
            'PK8' => 900,
            'Youpwe' => 850,
            'Bepanda' => 750,
            'Village' => 650,
        ];
        
        $this->info('Adding delivery fees for different neighborhoods...');
        $this->newLine();
        
        $count = 0;
        $updated = 0;
        
        foreach ($neighborhoods as $neighborhood => $fee) {
            // Check if the neighborhood already exists
            $existingFee = DeliveryFee::where('neighborhood', $neighborhood)->first();
            
            if ($existingFee) {
                // Update existing fee
                $existingFee->update([
                    'fee' => $fee,
                    'is_active' => true,
                    'description' => 'Frais de livraison pour le quartier ' . $neighborhood,
                ]);
                $updated++;
                $this->line("Updated: <fg=yellow>{$neighborhood}</> = {$fee} FCFA");
            } else {
                // Create new fee
                DeliveryFee::create([
                    'neighborhood' => $neighborhood,
                    'fee' => $fee,
                    'is_active' => true,
                    'description' => 'Frais de livraison pour le quartier ' . $neighborhood,
                ]);
                $count++;
                $this->line("Added: <fg=green>{$neighborhood}</> = {$fee} FCFA");
            }
        }
        
        $this->newLine();
        $this->info("Added {$count} new neighborhoods and updated {$updated} existing ones.");
        
        return Command::SUCCESS;
    }
}
