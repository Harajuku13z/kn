<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RevenueExport;

class RevenueController extends Controller
{
    /**
     * Affiche la page des chiffres d'affaires avec les filtres
     */
    public function index(Request $request)
    {
        // Période par défaut: mois en cours
        $period = $request->period ?? 'month';
        $startDate = null;
        $endDate = null;
        
        // Déterminer les dates de début et de fin en fonction de la période sélectionnée
        switch ($period) {
            case 'day':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'current_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'previous_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'last_3_months':
                $startDate = Carbon::now()->subMonths(3)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_6_months':
                $startDate = Carbon::now()->subMonths(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'custom':
                $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
                $endDate = $request->has('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
                break;
        }
        
        // Chiffre d'affaires des commandes livrées
        $deliveredRevenue = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
        
        // Chiffre d'affaires des commandes en attente
        $pendingRevenue = Order::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
        
        // Chiffre d'affaires des commandes payées
        $paidOrdersRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
        
        // Chiffre d'affaires des commandes en attente de paiement
        $unpaidOrdersRevenue = Order::where('payment_status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
        
        // Chiffre d'affaires des abonnements payés
        $paidSubscriptionsRevenue = Subscription::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount_paid');
        
        // Chiffre d'affaires des abonnements en attente de paiement
        $unpaidSubscriptionsRevenue = Subscription::where('payment_status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount_paid');
            
        // Chiffre d'affaires des paiements en cash (commandes sans quota d'abonnement)
        $cashPaymentRevenue = Order::whereNull('quota_id')
            ->where('payment_method', 'cash')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
        
        // Données pour les graphiques
        $revenueByDay = [];
        $currentDate = clone $startDate;
        
        while ($currentDate <= $endDate) {
            $dayRevenue = Order::where('status', 'delivered')
                ->whereDate('created_at', $currentDate)
                ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
                
            $subscriptionRevenue = Subscription::where('payment_status', 'paid')
                ->whereDate('created_at', $currentDate)
                ->sum('amount_paid');
                
            $cashRevenue = Order::whereNull('quota_id')
                ->where('payment_method', 'cash')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $currentDate)
                ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
                
            $revenueByDay[] = [
                'date' => $currentDate->format('d/m/Y'),
                'orders' => $dayRevenue,
                'subscriptions' => $subscriptionRevenue,
                'cash' => $cashRevenue,
                'total' => $dayRevenue + $subscriptionRevenue + $cashRevenue
            ];
            
            $currentDate->addDay();
        }
        
        return view('admin.revenue.index', [
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'deliveredRevenue' => $deliveredRevenue,
            'pendingRevenue' => $pendingRevenue,
            'paidOrdersRevenue' => $paidOrdersRevenue,
            'unpaidOrdersRevenue' => $unpaidOrdersRevenue,
            'paidSubscriptionsRevenue' => $paidSubscriptionsRevenue,
            'unpaidSubscriptionsRevenue' => $unpaidSubscriptionsRevenue,
            'cashPaymentRevenue' => $cashPaymentRevenue,
            'revenueByDay' => $revenueByDay
        ]);
    }
    
    /**
     * Exporte les données de chiffre d'affaires au format Excel
     */
    public function export(Request $request)
    {
        $period = $request->period ?? 'month';
        $startDate = null;
        $endDate = null;
        
        switch ($period) {
            case 'day':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'current_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'previous_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'last_3_months':
                $startDate = Carbon::now()->subMonths(3)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_6_months':
                $startDate = Carbon::now()->subMonths(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'custom':
                $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
                $endDate = $request->has('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
                break;
        }
        
        $filename = 'chiffre-affaires-' . $startDate->format('d-m-Y') . '-au-' . $endDate->format('d-m-Y') . '.xlsx';
        
        return Excel::download(new RevenueExport($startDate, $endDate), $filename);
    }
} 