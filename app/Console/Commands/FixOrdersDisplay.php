<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Article;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class FixOrdersDisplay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:orders-display {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostique et répare les problèmes d\'affichage des commandes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        if ($orderId) {
            $this->info("Diagnostic de la commande #$orderId");
            $this->diagnoseOrder($orderId);
        } else {
            $this->info("Diagnostic de toutes les commandes");
            $orders = Order::all();
            
            if ($orders->isEmpty()) {
                $this->error("Aucune commande trouvée dans la base de données.");
                return;
            }
            
            foreach ($orders as $order) {
                $this->info("Commande #{$order->id}");
                $this->diagnoseOrder($order->id);
                $this->newLine();
            }
        }
    }
    
    /**
     * Diagnostiquer une commande spécifique
     */
    private function diagnoseOrder($orderId)
    {
        // Récupérer la commande
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->error("La commande #$orderId n'existe pas dans la base de données");
            return;
        }
        
        // Vérifier les informations de base
        $this->info("- Statut: " . ($order->status ?? 'Non défini'));
        $this->info("- Date création: " . ($order->created_at ?? 'Non définie'));
        $this->info("- Poids: " . ($order->weight ?? 'Non défini') . " kg");
        $this->info("- Prix estimé: " . ($order->estimated_price ?? 'Non défini') . " FCFA");
        
        // Vérifier les adresses
        $this->info("- Adresse collecte ID: " . ($order->pickup_address ?? 'Non définie'));
        if ($order->pickup_address) {
            $pickupAddress = Address::find($order->pickup_address);
            if ($pickupAddress) {
                $this->info("  * Adresse collecte: " . $pickupAddress->address_line1);
            } else {
                $this->error("  * L'adresse de collecte (ID: {$order->pickup_address}) n'existe pas!");
            }
        }
        
        $this->info("- Adresse livraison ID: " . ($order->delivery_address ?? 'Non définie'));
        if ($order->delivery_address) {
            $deliveryAddress = Address::find($order->delivery_address);
            if ($deliveryAddress) {
                $this->info("  * Adresse livraison: " . $deliveryAddress->address_line1);
            } else {
                $this->error("  * L'adresse de livraison (ID: {$order->delivery_address}) n'existe pas!");
            }
        }
        
        // Vérifier les articles
        $orderItems = OrderItem::where('order_id', $orderId)->get();
        $this->info("- Articles: " . count($orderItems));
        
        if ($orderItems->isEmpty()) {
            $this->error("  * Aucun article trouvé pour cette commande!");
        } else {
            foreach ($orderItems as $item) {
                $article = Article::find($item->item_type);
                $this->info("  * Article: " . ($article ? $article->name : "ID: {$item->item_type} (introuvable)"));
                $this->info("    - Quantité: " . $item->quantity);
                $this->info("    - Prix unitaire: " . $item->unit_price . " FCFA");
                $this->info("    - Poids: " . $item->weight . " kg");
            }
        }
        
        // Proposer des corrections
        if ($this->confirm('Voulez-vous réparer les problèmes détectés sur cette commande?', false)) {
            $this->repairOrder($order, $orderItems);
        }
    }
    
    /**
     * Réparer les problèmes détectés sur une commande
     */
    private function repairOrder($order, $orderItems)
    {
        try {
            DB::beginTransaction();
            
            // 1. Réparer les articles si nécessaire
            if ($orderItems->isEmpty()) {
                $this->info("Création d'un article de test pour la commande");
                $article = Article::first();
                
                if (!$article) {
                    $this->error("Aucun article trouvé dans la base de données pour créer un élément de test");
                    $articleId = 1;
                    $articleName = "Article test";
                    $price = 1000;
                } else {
                    $articleId = $article->id;
                    $articleName = $article->name;
                    $price = 1000;
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => $articleId,
                    'quantity' => 1,
                    'weight' => 1.0,
                    'unit_price' => $price
                ]);
                
                $this->info("Article créé: $articleName x 1");
            }
            
            // 2. Mettre à jour le prix estimé et le poids total si nécessaire
            if (!$order->estimated_price || !$order->weight) {
                $totalWeight = 0;
                $totalPrice = 0;
                
                foreach (OrderItem::where('order_id', $order->id)->get() as $item) {
                    $totalWeight += $item->weight;
                    $totalPrice += ($item->unit_price * $item->quantity);
                }
                
                $order->weight = $totalWeight;
                $order->estimated_price = $totalPrice;
                $order->final_price = $totalPrice;
                $order->save();
                
                $this->info("Commande mise à jour: Poids = $totalWeight kg, Prix estimé = $totalPrice FCFA");
            }
            
            // 3. Réparer les adresses si nécessaire
            $addresses = Address::all();
            
            if (!$order->pickup_address && $addresses->isNotEmpty()) {
                $firstAddress = $addresses->first();
                $order->pickup_address = $firstAddress->id;
                $this->info("Adresse de collecte mise à jour: " . $firstAddress->address_line1);
            }
            
            if (!$order->delivery_address && $addresses->isNotEmpty()) {
                $firstAddress = $addresses->first();
                $order->delivery_address = $firstAddress->id;
                $this->info("Adresse de livraison mise à jour: " . $firstAddress->address_line1);
            }
            
            $order->save();
            
            DB::commit();
            $this->info("Réparations terminées avec succès!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Erreur lors de la réparation: " . $e->getMessage());
        }
    }
}
