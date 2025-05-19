<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\PriceConfiguration;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Affiche la page d'accueil
     */
    public function index()
    {
        // Récupérer les types d'abonnement actifs
        $subscriptionTypes = SubscriptionType::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();
        
        // Récupérer le prix actuel par kg
        $currentPrice = PriceConfiguration::getCurrentPrice();
        $pricePerKg = $currentPrice ? $currentPrice->price_per_kg : 1000;
        
        // Récupérer les articles pour le simulateur
        $articles = Article::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('welcome', compact('subscriptionTypes', 'pricePerKg', 'articles'));
    }
} 