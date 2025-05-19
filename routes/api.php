<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PressingServiceController;
use App\Http\Controllers\Api\ServiceController;
use App\Models\DeliveryVoucher;
use App\Models\Coupon;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route pour récupérer les services d'un pressing
Route::get('/pressings/{pressing}/services', [PressingServiceController::class, 'getServices']);
// Route alias avec paramètre pressingId
Route::get('/pressings/{pressingId}/services', [PressingServiceController::class, 'getServices']);

// Nouvelle route pour récupérer les services (nouveau modèle Service)
Route::get('/pressings/{pressingId}/new-services', [ServiceController::class, 'getPressingServices']);

// Route pour vérifier un bon de livraison
Route::post('/check-delivery-voucher', function (Request $request) {
    // Valider la requête
    $request->validate([
        'code' => 'required|string'
    ]);
    
    // Récupérer le bon de livraison
    $voucher = DeliveryVoucher::where('code', $request->code)->first();
    
    // Vérifier si le bon existe
    if (!$voucher) {
        return response()->json([
            'valid' => false,
            'message' => 'Bon de livraison introuvable.'
        ]);
    }
    
    // Vérifier si le bon est assigné à l'utilisateur connecté
    if ($voucher->user_id != auth()->id()) {
        return response()->json([
            'valid' => false,
            'message' => 'Ce bon ne vous appartient pas.'
        ]);
    }
    
    // Vérifier si le bon est actif
    if (!$voucher->is_active) {
        return response()->json([
            'valid' => false,
            'message' => 'Ce bon de livraison n\'est pas actif.'
        ]);
    }
    
    // Vérifier si le bon n'a pas expiré
    if ($voucher->valid_until && $voucher->valid_until < Carbon::now()) {
        return response()->json([
            'valid' => false,
            'message' => 'Ce bon de livraison a expiré.'
        ]);
    }
    
    // Vérifier si le bon a encore des livraisons disponibles
    if ($voucher->used_deliveries >= $voucher->number_of_deliveries) {
        return response()->json([
            'valid' => false,
            'message' => 'Ce bon ne contient plus de livraisons gratuites.'
        ]);
    }
    
    // Le bon est valide
    return response()->json([
        'valid' => true,
        'message' => 'Livraison gratuite appliquée',
        'voucher' => [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'description' => $voucher->description,
            'remaining_deliveries' => $voucher->number_of_deliveries - $voucher->used_deliveries,
        ]
    ]);
});

// Route pour récupérer les bons de livraison disponibles d'un utilisateur
Route::get('/available-delivery-vouchers', function (Request $request) {
    // Vérifier l'authentification
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Utilisateur non authentifié'
        ], 401);
    }
    
    // Récupérer les bons de livraison disponibles pour l'utilisateur connecté
    $vouchers = DeliveryVoucher::where('user_id', auth()->id())
        ->where('is_active', true)
        ->where(function($query) {
            $query->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', Carbon::now());
        })
        ->whereRaw('used_deliveries < number_of_deliveries')
        ->orderBy('valid_until')
        ->get();
    
    // Formater la réponse
    $formattedVouchers = $vouchers->map(function($voucher) {
        return [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'description' => $voucher->description,
            'remaining_deliveries' => $voucher->number_of_deliveries - $voucher->used_deliveries,
            'valid_until' => $voucher->valid_until ? $voucher->valid_until->format('Y-m-d') : null,
            'valid_until_formatted' => $voucher->valid_until ? $voucher->valid_until->format('d/m/Y') : 'Illimitée'
        ];
    });
    
    return response()->json([
        'success' => true,
        'count' => $vouchers->count(),
        'vouchers' => $formattedVouchers
    ]);
});

// Routes pour les frais de livraison
Route::prefix('delivery-fees')->group(function () {
    // Récupération de tous les frais de livraison
    Route::get('/', [\App\Http\Controllers\Api\DeliveryFeeController::class, 'getDeliveryFees']);
    
    // Calcul des frais de livraison entre deux quartiers
    Route::get('/calculate', [\App\Http\Controllers\Api\DeliveryFeeController::class, 'calculateFee']);
});

// Route pour calculer les frais de livraison entre deux quartiers (maintenue pour la compatibilité)
Route::get('/calculate-delivery-fee', [\App\Http\Controllers\Api\DeliveryFeeController::class, 'calculateFee']);

// Route pour récupérer toutes les villes actives
Route::get('/cities', function () {
    $cities = \App\Models\City::where('is_active', true)
        ->orderBy('name')
        ->get()
        ->map(function($city) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'description' => $city->description
            ];
        });
    
    return response()->json([
        'success' => true,
        'cities' => $cities
    ]);
});

// Route pour récupérer tous les quartiers d'une ville
Route::get('/city/{city_id}/districts', function ($city_id) {
    // Vérifier que la ville existe et est active
    $city = \App\Models\City::where('id', $city_id)
        ->where('is_active', true)
        ->first();
    
    if (!$city) {
        return response()->json([
            'success' => false,
            'message' => 'Ville non trouvée ou inactive'
        ], 404);
    }
    
    $districts = \App\Models\DeliveryFee::where('city_id', $city_id)
        ->where('is_active', true)
        ->orderBy('district')
        ->pluck('district')
        ->values();
    
    return response()->json([
        'success' => true,
        'city' => $city->name,
        'districts' => $districts
    ]);
});

// Route pour récupérer tous les quartiers disponibles
Route::get('/districts', function () {
    $districts = \App\Models\DeliveryFee::where('is_active', true)
        ->orderBy('district')
        ->get()
        ->map(function($fee) {
            return [
                'id' => $fee->id,
                'city_id' => $fee->city_id,
                'city' => $fee->city ? $fee->city->name : null,
                'district' => $fee->district,
                'full_name' => $fee->full_district_name
            ];
        });
    
    return response()->json([
        'success' => true,
        'districts' => $districts
    ]);
});

// Route pour récupérer les quartiers organisés par arrondissement pour Brazzaville
Route::get('/city/{city_id}/organized-districts', function ($city_id) {
    // Vérifier que la ville existe et est active
    $city = \App\Models\City::where('id', $city_id)
        ->where('is_active', true)
        ->first();
    
    if (!$city) {
        return response()->json([
            'success' => false,
            'message' => 'Ville non trouvée ou inactive'
        ], 404);
    }
    
    $deliveryFees = \App\Models\DeliveryFee::where('city_id', $city_id)
        ->where('is_active', true)
        ->orderBy('district')
        ->get();
    
    // Liste des principaux arrondissements de Brazzaville
    $mainArrondissements = [
        'Makélékélé', 'Bacongo', 'Poto-Poto', 'Moungali', 'Ouenzé', 'Talangaï', 'Mfilou', 'Madibou', 'Djiri'
    ];
    
    // Organiser les quartiers
    $organizedDistricts = [
        'arrondissements' => [],
        'quartiers' => []
    ];
    
    foreach ($deliveryFees as $fee) {
        $district = $fee->district;
        
        if (in_array($district, $mainArrondissements)) {
            $organizedDistricts['arrondissements'][] = [
                'name' => $district,
                'fee' => $fee->fee,
                'formatted_fee' => $fee->formatted_fee
            ];
        } else {
            $organizedDistricts['quartiers'][] = [
                'name' => $district,
                'fee' => $fee->fee,
                'formatted_fee' => $fee->formatted_fee
            ];
        }
    }
    
    // Trier les quartiers par ordre alphabétique
    usort($organizedDistricts['quartiers'], function($a, $b) {
        return $a['name'] <=> $b['name'];
    });
    
    return response()->json([
        'success' => true,
        'city' => $city->name,
        'districts' => $organizedDistricts
    ]);
});

// Route pour vérifier un code promo
Route::post('/check-coupon', function (Request $request) {
    // Valider la requête
    $request->validate([
        'code' => 'required|string'
    ]);
    
    // Vérifier l'authentification
    if (!auth()->check()) {
        return response()->json([
            'valid' => false,
            'message' => 'Utilisateur non authentifié'
        ], 401);
    }
    
    // Récupérer le code promo
    $coupon = \App\Models\Coupon::where('code', $request->code)
        ->where('is_active', true)
        ->first();
    
    // Vérifier si le code existe
    if (!$coupon) {
        return response()->json([
            'valid' => false,
            'message' => 'Code promo introuvable ou inactif.'
        ]);
    }
    
    // Vérifier si le code n'a pas expiré
    $now = Carbon::now();
    if ($coupon->valid_until && $coupon->valid_until < $now) {
        return response()->json([
            'valid' => false,
            'message' => 'Ce code promo a expiré.'
        ]);
    }
    
    // Vérifier si le code n'est pas encore valide
    if ($coupon->valid_from && $coupon->valid_from > $now) {
        return response()->json([
            'valid' => false,
            'message' => 'Ce code promo n\'est pas encore valide.'
        ]);
    }
    
    // Vérifier si le code a atteint son nombre maximum d'utilisations
    if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
        return response()->json([
            'valid' => false,
            'message' => 'Ce code promo a atteint son nombre maximum d\'utilisations.'
        ]);
    }
    
    // Le code est valide
    return response()->json([
        'valid' => true,
        'message' => 'Code promo valide',
        'coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'min_order_amount' => $coupon->min_order_amount,
            'max_discount_amount' => $coupon->max_discount_amount,
            'formatted_value' => $coupon->type === 'percentage' 
                ? number_format($coupon->value, 0) . '%' 
                : number_format($coupon->value, 0) . ' FCFA',
            'description' => $coupon->description
        ]
    ]);
});

// Route pour récupérer les codes promo disponibles pour l'utilisateur connecté
Route::get('/available-coupons', function () {
    // Vérifier l'authentification
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Utilisateur non authentifié'
        ], 401);
    }
    
    // Utilisez la méthode getAvailableForUser du modèle Coupon
    $coupons = \App\Models\Coupon::getAvailableForUser(auth()->id());
    
    return response()->json([
        'success' => true,
        'count' => count($coupons),
        'coupons' => $coupons
    ]);
});