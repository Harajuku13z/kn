<?php

namespace App\Http\Controllers;

use App\Models\PriceConfiguration;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    /**
     * Display the abonnements page
     */
    public function index()
    {
        // Récupérer le prix actuel par kg
        $currentPrice = PriceConfiguration::getCurrentPrice();
        $pricePerKg = $currentPrice ? $currentPrice->price_per_kg : 1000;
        
        // Récupérer les types d'abonnement actifs
        $subscriptionTypes = SubscriptionType::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();
        
        return view('pages.abonnements', compact('pricePerKg', 'subscriptionTypes'));
    }
} 