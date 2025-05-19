<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Article;
use App\Models\PriceConfiguration;
use Illuminate\Support\Facades\Log;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
                'delivered_orders' => Order::where('status', 'delivered')->count(),
                'total_users' => User::count(),
                'total_articles' => Article::count(),
                'current_price' => PriceConfiguration::getCurrentPrice() ?? PriceConfiguration::create([
                    'price_per_kg' => 1000,
                    'last_update_reason' => 'Configuration initiale',
                    'effective_date' => now(),
                    'created_by_user_id' => auth()->id(),
                ]),
                'total_support_tickets' => SupportTicket::count(),
                'open_tickets' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            ];

            // Récupérer les commandes récentes
            $recent_orders = Order::with(['user', 'items'])
                ->latest()
                ->take(5)
                ->get();

            // Récupérer les tickets de support récents
            $recent_tickets = SupportTicket::with('user')
                ->whereIn('status', ['open', 'in_progress'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Calculer le chiffre d'affaires du jour
            $today = Carbon::today();
            $dailyRevenue = Order::whereDate('created_at', $today)
                ->where('status', 'delivered')
                ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));

            // Calculer le chiffre d'affaires des 7 derniers jours
            $last7Days = [];
            $last7DaysLabels = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $revenue = Order::whereDate('created_at', $date)
                    ->where('status', 'delivered')
                    ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
                
                $last7Days[] = $revenue;
                $last7DaysLabels[] = $date->format('d/m');
            }

            // Calculer le chiffre d'affaires des 12 derniers mois
            $last12Months = [];
            $last12MonthsLabels = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::today()->subMonths($i);
                $revenue = Order::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('status', 'delivered')
                    ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
                
                $last12Months[] = $revenue;
                $last12MonthsLabels[] = $month->format('M Y');
            }

            // Calculer le chiffre d'affaires total
            $totalRevenue = Order::where('status', 'delivered')
                ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));

            // Passer les variables à la vue
            return view('admin.dashboard', [
                'stats' => $stats,
                'recent_orders' => $recent_orders,
                'recent_tickets' => $recent_tickets,
                'dailyRevenue' => $dailyRevenue,
                'last7Days' => $last7Days,
                'last7DaysLabels' => $last7DaysLabels,
                'last12Months' => $last12Months,
                'last12MonthsLabels' => $last12MonthsLabels,
                'totalRevenue' => $totalRevenue
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dans DashboardController: ' . $e->getMessage());
            
            // En cas d'erreur, passer des valeurs par défaut
            return view('admin.dashboard', [
                'stats' => [
                    'total_orders' => 0,
                    'pending_orders' => 0,
                    'delivered_orders' => 0,
                    'total_users' => 0,
                    'total_articles' => 0,
                    'current_price' => null,
                    'total_support_tickets' => 0,
                    'open_tickets' => 0,
                ],
                'recent_orders' => collect(),
                'recent_tickets' => collect(),
                'dailyRevenue' => 0,
                'last7Days' => [],
                'last7DaysLabels' => [],
                'last12Months' => [],
                'last12MonthsLabels' => [],
                'totalRevenue' => 0
            ]);
        }
    }
} 