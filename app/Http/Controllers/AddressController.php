<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\DeliveryFee;
use App\Notifications\AddressCreated;
use App\Notifications\AddressDeleted;
use App\Notifications\AddressSetDefault;
use App\Notifications\AddressUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AddressController extends Controller
{
    /**
     * Display a listing of the user's addresses.
     */
    public function index(Request $request)
    {
        $addresses = Address::with('city')->where('user_id', Auth::id())->get();
        
        // Si c'est une requête AJAX, retourner seulement la vue partielle
        if ($request->ajax() || $request->wantsJson()) {
            return view('addresses.index', [
                'addresses' => $addresses
            ]);
        }
        
        return view('addresses.index', [
            'addresses' => $addresses
        ]);
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        $cities = \App\Models\City::where('is_active', true)->orderBy('name')->get();
        
        // Récupérer tous les quartiers avec leurs frais de livraison, organisés par ville
        $allDistrictsByCity = $this->getAllDistrictsGroupedByCities();
        
        return view('addresses.create', compact('cities', 'allDistrictsByCity'));
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'district' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'contact_name' => 'required|string|max:255',
            'type' => 'required|in:both,pickup,delivery',
            'is_default' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        // Si c'est l'adresse par défaut, désactiver les autres adresses par défaut
        if (isset($validated['is_default']) && $validated['is_default']) {
            Address::where('user_id', Auth::id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $address = Address::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'address' => $validated['address'],
            'city_id' => $validated['city_id'],
            'district' => $validated['district'],
            'landmark' => $validated['landmark'] ?? null,
            'phone' => $validated['phone'],
            'contact_name' => $validated['contact_name'],
            'type' => $validated['type'],
            'is_default' => $validated['is_default'] ?? false,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null
        ]);

        // Envoyer la notification à l'utilisateur
        $user = Auth::user();
        $user->notify(new AddressCreated($address));
        
        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address)
    {
        // Check if address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cities = \App\Models\City::where('is_active', true)->orderBy('name')->get();
        
        // Récupérer tous les quartiers avec leurs frais de livraison, organisés par ville
        $allDistrictsByCity = $this->getAllDistrictsGroupedByCities();
        
        return view('addresses.edit', [
            'address' => $address,
            'cities' => $cities,
            'allDistrictsByCity' => $allDistrictsByCity
        ]);
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, Address $address)
    {
        // Check if address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'district' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'contact_name' => 'required|string|max:255',
            'type' => 'required|in:both,pickup,delivery',
            'is_default' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        // Si c'est l'adresse par défaut, désactiver les autres adresses par défaut
        if (isset($validated['is_default']) && $validated['is_default']) {
            Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        // Envoyer la notification à l'utilisateur
        $user = Auth::user();
        $user->notify(new AddressUpdated($address));
        
        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }

    /**
     * Remove the specified address.
     */
    public function destroy(Address $address)
    {
        // Check if address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $addressName = $address->address;
        $address->delete();
        
        // Envoyer la notification à l'utilisateur
        $user = Auth::user();
        $user->notify(new AddressDeleted($addressName));
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        // Redirection classique pour les requêtes non-AJAX
        return redirect()->route('addresses.index')->with('success', 'Adresse supprimée avec succès !');
    }
    
    /**
     * Set an address as default for pickup or delivery.
     */
    public function setDefault(Request $request, Address $address)
    {
        // Check if address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Get the type (pickup or delivery)
        $type = $request->input('type', 'pickup');
        
        // Validate type is either pickup or delivery
        if (!in_array($type, ['pickup', 'delivery'])) {
            return redirect()->route('addresses.index')
                ->with('error', 'Type invalide. Utilisez "pickup" ou "delivery".');
        }
        
        // Determine which field to update
        $field = $type === 'pickup' ? 'is_default_pickup' : 'is_default_delivery';
        
        // Unset any existing default address for this type
        Address::where('user_id', Auth::id())
            ->where($field, true)
            ->update([$field => false]);
        
        // Set this address as default for the specified type
        $address->update([$field => true]);
        
        // Generate the success message
        $typeLabel = $type === 'pickup' ? 'collecte' : 'livraison';
        $successMessage = "Adresse définie comme adresse par défaut pour la {$typeLabel}.";
        
        // Envoyer la notification à l'utilisateur
        $user = Auth::user();
        $user->notify(new AddressSetDefault($address, $type));
        
        return redirect()->route('addresses.index')
            ->with('success', $successMessage);
    }
    
    /**
     * Get the list of neighborhoods in Brazzaville.
     */
    private function getNeighborhoods()
    {
        return [
            'Bacongo' => 'Bacongo',
            'Makélékélé' => 'Makélékélé',
            'Poto-Poto' => 'Poto-Poto',
            'Moungali' => 'Moungali',
            'Ouenzé' => 'Ouenzé',
            'Talangaï' => 'Talangaï',
            'Mfilou' => 'Mfilou',
            'Madibou' => 'Madibou',
            'Djiri' => 'Djiri',
        ];
    }

    /**
     * Return the address form for AJAX requests
     */
    public function ajaxCreate()
    {
        $cities = \App\Models\City::where('is_active', true)->orderBy('name')->get();
        
        // Récupérer tous les quartiers avec leurs frais de livraison, organisés par ville
        $allDistrictsByCity = $this->getAllDistrictsGroupedByCities();
        
        return view('addresses.ajax-create', [
            'cities' => $cities,
            'allDistrictsByCity' => $allDistrictsByCity
        ]);
    }

    /**
     * Store a new address from AJAX request and return it
     */
    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city_id' => ['required', 'exists:cities,id'],
            'district' => ['required', 'string'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'contact_name' => ['required', 'string', 'max:255'],
            'is_default' => ['boolean'],
            'type' => ['required', Rule::in(['pickup', 'delivery', 'both'])],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
        
        $validated['user_id'] = Auth::id();
        
        // If this is set as default, unset any existing defaults
        if (isset($validated['is_default']) && $validated['is_default']) {
            Address::where('user_id', Auth::id())
                  ->where('is_default', true)
                  ->update(['is_default' => false]);
        }
        
        $address = Address::create($validated);
        
        // Load city relationship
        $address->load('city');
        
        // Envoyer la notification à l'utilisateur
        $user = Auth::user();
        $user->notify(new AddressCreated($address));
        
        // Return the address with formatted display
        return response()->json([
            'success' => true,
            'message' => 'Adresse créée avec succès.',
            'address' => $address,
            'address_display' => $address->name . ' - ' . ($address->city ? $address->city->name . ', ' : '') . $address->district,
            'address_id' => $address->id
        ]);
    }

    /**
     * Get user addresses as JSON
     */
    public function getAddressesJson()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return response()->json($addresses);
    }
    
    /**
     * Helper method to get all districts organized by city
     * 
     * @return array
     */
    private function getAllDistrictsGroupedByCities()
    {
        $deliveryFees = DeliveryFee::with('city')
            ->where('is_active', true)
            ->orderBy('city_id')
            ->orderBy('district')
            ->get();
            
        $allDistrictsByCity = [];
        
        // Liste des principaux arrondissements de Brazzaville (pour la ville de Brazzaville)
        $mainArrondissements = [
            'Makélékélé', 'Bacongo', 'Poto-Poto', 'Moungali', 'Ouenzé', 
            'Talangaï', 'Mfilou', 'Madibou', 'Djiri'
        ];
        
        foreach ($deliveryFees as $fee) {
            if (!$fee->city) continue;
            
            $cityId = $fee->city_id;
            $cityName = $fee->city->name;
            
            // Initialiser la structure si nécessaire
            if (!isset($allDistrictsByCity[$cityId])) {
                $allDistrictsByCity[$cityId] = [
                    'city_name' => $cityName,
                    'districts' => []
                ];
                
                // Structure spéciale pour Brazzaville
                if ($cityName === 'Brazzaville') {
                    $allDistrictsByCity[$cityId]['is_brazzaville'] = true;
                    $allDistrictsByCity[$cityId]['arrondissements'] = [];
                    $allDistrictsByCity[$cityId]['quartiers'] = [];
                }
            }
            
            // Cas spécial pour Brazzaville
            if ($cityName === 'Brazzaville') {
                if (in_array($fee->district, $mainArrondissements)) {
                    $allDistrictsByCity[$cityId]['arrondissements'][] = [
                        'name' => $fee->district,
                        'fee' => $fee->fee,
                        'formatted_fee' => $fee->formatted_fee
                    ];
                } else {
                    $allDistrictsByCity[$cityId]['quartiers'][] = [
                        'name' => $fee->district,
                        'fee' => $fee->fee,
                        'formatted_fee' => $fee->formatted_fee
                    ];
                }
            } else {
                // Pour les autres villes, structure standard
                $allDistrictsByCity[$cityId]['districts'][] = [
                    'name' => $fee->district,
                    'fee' => $fee->fee,
                    'formatted_fee' => $fee->formatted_fee
                ];
            }
        }
        
        return $allDistrictsByCity;
    }
} 