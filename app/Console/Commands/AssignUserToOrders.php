<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;

class AssignUserToOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:assign-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a user to all orders that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to assign users to orders...');
        
        // Trouver le premier utilisateur (ou admin) à attribuer aux commandes
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found in the database!');
            return 1;
        }
        
        $ordersWithoutUser = Order::whereNull('user_id')->get();
        $count = $ordersWithoutUser->count();
        
        $this->info("Found {$count} orders without a user.");
        
        if ($count === 0) {
            $this->info('No orders need to be updated.');
            return 0;
        }
        
        // Mettre à jour toutes les commandes
        foreach ($ordersWithoutUser as $order) {
            $order->user_id = $user->id;
            $order->save();
            $this->line("Updated order #{$order->id}");
        }
        
        $this->info("Successfully assigned user ID {$user->id} to {$count} orders.");
        return 0;
    }
} 