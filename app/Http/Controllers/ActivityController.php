<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\QuotaUsage;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Affiche toutes les activités de l'utilisateur
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Nombre d'éléments par page
        $perPage = 15;
        $page = $request->query('page', 1);
        
        // Récupérer les commandes récentes - augmenter le nombre à 50 pour être sûr d'inclure la commande 9
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
            
        // Ajouter des logs pour le débogage
        foreach ($orders as $order) {
            \Log::info("Commande dans ActivityController", [
                'id' => $order->id,
                'status' => $order->status,
                'is_delivered_or_completed' => in_array($order->status, ['delivered', 'completed']),
                'user_id' => $order->user_id
            ]);
        }
            
        // Récupérer les abonnements récents
        $subscriptions = Subscription::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        // Récupérer les utilisations de quota
        $quotaUsages = QuotaUsage::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();
        
        // Regrouper toutes les activités et les trier par date
        $activities = collect();
        
        foreach ($orders as $order) {
            $activities->push([
                'type' => 'order',
                'data' => $order,
                'date' => $order->created_at,
                'title' => 'Commande #' . $order->id,
                'description' => 'Commande ' . $this->getOrderStatusText($order->status),
                'amount' => $order->final_price ?? $order->estimated_price,
                'icon' => 'bi-box-seam'
            ]);
        }
        
        // Ajouter un log pour voir combien de commandes livrées/terminées sont disponibles
        $completedOrders = $activities->where('type', 'order')->filter(function($activity) {
            return in_array($activity['data']->status, ['delivered', 'completed']);
        });
        
        \Log::info("Commandes terminées/livrées dans ActivityController", [
            'count' => $completedOrders->count(),
            'ids' => $completedOrders->pluck('data.id')->toArray()
        ]);
        
        foreach ($subscriptions as $subscription) {
            $activities->push([
                'type' => 'subscription',
                'data' => $subscription,
                'date' => $subscription->created_at,
                'title' => 'Abonnement #' . $subscription->id,
                'description' => 'Abonnement ' . ($subscription->subscriptionType ? $subscription->subscriptionType->name : 'non spécifié'),
                'amount' => $subscription->price,
                'icon' => 'bi-credit-card'
            ]);
        }
        
        foreach ($quotaUsages as $usage) {
            $activities->push([
                'type' => 'usage',
                'data' => $usage,
                'date' => $usage->created_at,
                'title' => 'Utilisation de quota',
                'description' => $usage->description . ' (' . $usage->used_kg . ' kg)',
                'amount' => null,
                'icon' => 'bi-arrow-down-circle'
            ]);
        }
        
        // Trier toutes les activités par date (du plus récent au plus ancien)
        $activities = $activities->sortByDesc('date');
        
        // Mettre en place une pagination manuelle sur la collection
        $activitiesCount = $activities->count();
        $totalPages = ceil($activitiesCount / $perPage);
        
        // S'assurer que la page demandée est valide
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        
        // Obtenir la portion de la collection correspondant à la page actuelle
        $offset = ($page - 1) * $perPage;
        $paginatedActivities = $activities->slice($offset, $perPage);
        
        // Créer un paginateur personnalisé
        $paginatedActivities = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedActivities,
            $activitiesCount,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Créer une variable pour les documents récents (commandes avec factures)
        $recentDocuments = $activities->where('type', 'order')
            ->filter(function($activity) {
                return in_array($activity['data']->status, ['delivered', 'completed']);
            })
            ->take(5);
        
        return view('activities.index', [
            'activities' => $paginatedActivities,
            'recentDocuments' => $recentDocuments
        ]);
    }
    
    /**
     * Obtenir la description textuelle d'un statut de commande
     */
    private function getOrderStatusText($status)
    {
        switch ($status) {
            case 'pending':
                return 'en attente';
            case 'scheduled':
                return 'planifiée';
            case 'collected':
                return 'collectée';
            case 'processing':
                return 'en traitement';
            case 'ready_for_delivery':
                return 'prête pour livraison';
            case 'delivered':
                return 'livrée';
            case 'completed':
                return 'terminée';
            case 'cancelled':
                return 'annulée';
            default:
                return $status;
        }
    }
} 