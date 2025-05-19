<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceConfigurationController extends Controller
{
    public function index()
    {
        $currentPrice = PriceConfiguration::getCurrentPrice();
        $prices = PriceConfiguration::with('createdBy')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.prices.index', compact('currentPrice', 'prices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'price_per_kg' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'last_update_reason' => 'required|string',
        ]);

        $validated['created_by_user_id'] = Auth::id();

        PriceConfiguration::create($validated);

        return redirect()->route('admin.prices.index')
            ->with('success', 'Nouveau prix ajouté avec succès.');
    }

    // Suppression des méthodes edit, update et destroy car l'historique ne doit pas être modifiable
} 