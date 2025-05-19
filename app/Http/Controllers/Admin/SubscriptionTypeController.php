<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class SubscriptionTypeController extends Controller
{
    public function index()
    {
        $subscriptionTypes = SubscriptionType::all();
        return view('admin.subscription-types.index', compact('subscriptionTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'quota' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'service_level' => 'required|string|in:standard,priority,express',
            'service_features' => 'nullable|array',
        ]);

        // Convert service features array to JSON if provided
        if (isset($validated['service_features']) && is_array($validated['service_features'])) {
            $validated['service_features'] = array_filter($validated['service_features']);
        }
        
        // Handle is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        SubscriptionType::create($validated);

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement créé avec succès.');
    }

    public function update(Request $request, SubscriptionType $subscriptionType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'quota' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'service_level' => 'required|string|in:standard,priority,express',
            'service_features' => 'nullable|array',
        ]);

        // Convert service features array to JSON if provided
        if (isset($validated['service_features']) && is_array($validated['service_features'])) {
            $validated['service_features'] = array_filter($validated['service_features']);
        }
        
        // Handle is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        $subscriptionType->update($validated);

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement mis à jour avec succès.');
    }

    public function destroy(SubscriptionType $subscriptionType)
    {
        $subscriptionType->delete();

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement supprimé avec succès.');
    }
    
    /**
     * Get the service features for a subscription type.
     */
    public function getFeatures(SubscriptionType $subscriptionType)
    {
        return response()->json([
            'features' => $subscriptionType->service_features
        ]);
    }
} 