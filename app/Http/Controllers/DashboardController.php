<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Quota;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'utilisateur
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer les commandes récentes
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Récupérer les quotas actifs
        $activeQuotas = Quota::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('expiration_date', 'asc')
            ->get();
            
        // Si c'est une requête AJAX, retourner seulement la vue partielle
        if ($request->ajax() || $request->wantsJson()) {
            return view('dashboard.home', [
                'user' => $user,
                'recentOrders' => $recentOrders,
                'activeQuotas' => $activeQuotas
            ]);
        }
            
        return view('dashboard.home', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'activeQuotas' => $activeQuotas
        ]);
    }
}
