<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Article;
use App\Models\Address;
use App\Models\User;
use App\Notifications\OrderCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CreateFixOrdersEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:order-emails {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige et renvoie les notifications par email des commandes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        if ($orderId) {
            $this->info("Traitement de la commande #$orderId");
            $this->processOrder($orderId);
        } else {
            $this->info("Traitement de toutes les commandes récentes");
            // Traiter seulement les 10 dernières commandes
            $orders = Order::orderBy('created_at', 'desc')->take(10)->get();
            
            if ($orders->isEmpty()) {
                $this->error("Aucune commande trouvée dans la base de données.");
                return;
            }
            
            foreach ($orders as $order) {
                $this->info("Commande #{$order->id}");
                $this->processOrder($order->id);
                $this->newLine();
            }
        }
    }
    
    /**
     * Traite une commande spécifique
     */
    private function processOrder($orderId)
    {
        // Récupérer la commande
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->error("La commande #$orderId n'existe pas dans la base de données");
            return;
        }
        
        // Afficher des informations sur la commande
        $this->info("- Statut: " . ($order->status ?? 'Non défini'));
        $this->info("- Date création: " . ($order->created_at ?? 'Non définie'));
        
        // Vérifier les adresses
        $pickupAddressExists = false;
        $deliveryAddressExists = false;
        
        $this->info("- Adresse collecte ID: " . ($order->pickup_address ?? 'Non définie'));
        if ($order->pickup_address) {
            $pickupAddress = Address::find($order->pickup_address);
            if ($pickupAddress) {
                $this->info("  * Adresse collecte: " . $pickupAddress->address);
                $pickupAddressExists = true;
            } else {
                $this->error("  * L'adresse de collecte (ID: {$order->pickup_address}) n'existe pas!");
            }
        }
        
        $this->info("- Adresse livraison ID: " . ($order->delivery_address ?? 'Non définie'));
        if ($order->delivery_address) {
            $deliveryAddress = Address::find($order->delivery_address);
            if ($deliveryAddress) {
                $this->info("  * Adresse livraison: " . $deliveryAddress->address);
                $deliveryAddressExists = true;
            } else {
                $this->error("  * L'adresse de livraison (ID: {$order->delivery_address}) n'existe pas!");
            }
        }
        
        // Vérifier les articles et le prix
        $orderItems = OrderItem::where('order_id', $orderId)->get();
        $this->info("- Articles: " . count($orderItems));
        
        $totalPrice = 0;
        foreach ($orderItems as $item) {
            $article = Article::find($item->item_type);
            $itemPrice = $item->unit_price * $item->quantity;
            $totalPrice += $itemPrice;
            
            $this->info("  * Article: " . ($article ? $article->name : "ID: {$item->item_type} (introuvable)"));
            $this->info("    - Quantité: " . $item->quantity);
            $this->info("    - Prix unitaire: " . $item->unit_price . " FCFA");
            $this->info("    - Total: " . $itemPrice . " FCFA");
        }
        
        $this->info("- Prix total calculé: " . $totalPrice . " FCFA");
        $this->info("- Prix estimé enregistré: " . ($order->estimated_price ?? 'Non défini') . " FCFA");
        
        // Proposer des corrections
        if (!$pickupAddressExists || !$deliveryAddressExists || $totalPrice != ($order->estimated_price ?? 0)) {
            if ($this->confirm('Voulez-vous mettre à jour la commande et renvoyer l\'email?', false)) {
                $this->updateOrderAndSendEmail($order, $totalPrice);
            }
        } else {
            if ($this->confirm('Tout semble correct. Voulez-vous quand même renvoyer l\'email?', false)) {
                $this->sendOrderEmail($order);
            }
        }
    }
    
    /**
     * Met à jour la commande et renvoie un email
     */
    private function updateOrderAndSendEmail($order, $totalPrice) 
    {
        try {
            DB::beginTransaction();
            
            // Mettre à jour le prix si nécessaire
            if ($totalPrice != ($order->estimated_price ?? 0)) {
                $order->estimated_price = $totalPrice;
                $order->final_price = $totalPrice;
                $order->save();
                $this->info("Prix mis à jour: $totalPrice FCFA");
            }
            
            DB::commit();
            
            // Envoyer l'email
            $this->sendOrderEmail($order);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Erreur lors de la mise à jour: " . $e->getMessage());
        }
    }
    
    /**
     * Envoie un email de confirmation de commande
     */
    private function sendOrderEmail($order)
    {
        try {
            $user = $order->user;
            
            if (!$user) {
                $this->error("Aucun utilisateur associé à cette commande!");
                return;
            }
            
            $user->notify(new OrderCreated($order));
            $this->info("Email envoyé avec succès à {$user->email}!");
            
        } catch (\Exception $e) {
            $this->error("Erreur lors de l'envoi de l'email: " . $e->getMessage());
        }
    }
}
