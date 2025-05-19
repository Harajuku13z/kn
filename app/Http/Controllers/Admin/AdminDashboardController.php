<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Article;
use App\Models\PriceConfiguration;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord d'administration.
     */
    public function index()
    {
        try {
            // Récupérer les statistiques
            $stats = [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'total_users' => User::where('is_admin', false)->count(),
                'total_articles' => Article::count(),
                'current_price' => PriceConfiguration::getCurrentPrice() ?? PriceConfiguration::create([
                    'price_per_kg' => 1000,
                    'last_update_reason' => 'Configuration initiale',
                    'effective_date' => now(),
                    'created_by_user_id' => auth()->id(),
                ]),
            ];

            // Récupérer les commandes récentes
            $recent_orders = Order::with(['user', 'items'])
                ->latest()
                ->take(5)
                ->get();

            // Passer les variables à la vue
            return view('admin.dashboard', [
                'stats' => $stats,
                'recent_orders' => $recent_orders
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dans AdminDashboardController: ' . $e->getMessage());
            
            // En cas d'erreur, passer des valeurs par défaut
            return view('admin.dashboard', [
                'stats' => [
                    'total_orders' => 0,
                    'pending_orders' => 0,
                    'total_users' => 0,
                    'total_articles' => 0,
                    'current_price' => null,
                ],
                'recent_orders' => collect(),
            ]);
        }
    }
} 