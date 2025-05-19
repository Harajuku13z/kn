<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryFee;
use Illuminate\Http\Request;

class DeliveryFeeController extends Controller
{
    /**
     * Récupère tous les frais de livraison actifs
     */
    public function getDeliveryFees()
    {
        $deliveryFees = DeliveryFee::where('is_active', true)
            ->orderBy('district')
            ->get()
            ->map(function($fee) {
                return [
                    'id' => $fee->id,
                    'district' => $fee->district,
                    'fee' => $fee->fee,
                    'formatted_fee' => $fee->formatted_fee,
                    'description' => $fee->description
                ];
            });
        
        return response()->json([
            'success' => true,
            'fees' => $deliveryFees
        ]);
    }
    
    /**
     * Calcule les frais de livraison entre deux quartiers
     */
    public function calculateFee(Request $request)
    {
        $request->validate([
            'pickup_district' => 'required|string',
            'delivery_district' => 'nullable|string',
        ]);
        
        $pickupDistrict = $request->pickup_district;
        $deliveryDistrict = $request->delivery_district ?? $pickupDistrict;
        
        // Information de debug pour tracer le problème
        $debug = [
            'pickup_district_received' => $pickupDistrict,
            'delivery_district_received' => $deliveryDistrict,
            'query_params' => $request->all(),
        ];
        
        // Récupérer les frais pour le quartier de collecte
        $pickupFee = DeliveryFee::where('district', $pickupDistrict)
            ->where('is_active', true)
            ->first();
        
        // Si aucun frais trouvé, chercher avec une correspondance partielle (contient)
        if (!$pickupFee) {
            $pickupFee = DeliveryFee::where('district', 'like', '%' . $pickupDistrict . '%')
                ->where('is_active', true)
                ->first();
            
            $debug['pickup_fee_partial_match'] = $pickupFee ? true : false;
            $debug['pickup_fee_district'] = $pickupFee ? $pickupFee->district : 'non trouvé';
        } else {
            $debug['pickup_fee_exact_match'] = true;
            $debug['pickup_fee_district'] = $pickupFee->district;
        }
        
        // Récupérer les frais pour le quartier de livraison
        $deliveryFee = DeliveryFee::where('district', $deliveryDistrict)
            ->where('is_active', true)
            ->first();
        
        // Si aucun frais trouvé, chercher avec une correspondance partielle (contient)
        if (!$deliveryFee) {
            $deliveryFee = DeliveryFee::where('district', 'like', '%' . $deliveryDistrict . '%')
                ->where('is_active', true)
                ->first();
            
            $debug['delivery_fee_partial_match'] = $deliveryFee ? true : false;
            $debug['delivery_fee_district'] = $deliveryFee ? $deliveryFee->district : 'non trouvé';
        } else {
            $debug['delivery_fee_exact_match'] = true;
            $debug['delivery_fee_district'] = $deliveryFee->district;
        }
        
        // Valeurs par défaut
        $pickupFeeAmount = 1000; // 1000 FCFA par défaut
        $deliveryFeeAmount = 1000; // 1000 FCFA par défaut
        
        // Utiliser les valeurs réelles si trouvées
        if ($pickupFee) {
            $pickupFeeAmount = $pickupFee->fee;
        }
        
        if ($deliveryFee) {
            $deliveryFeeAmount = $deliveryFee->fee;
        }
        
        // Si collecte et livraison dans le même quartier, on ne compte qu'une seule fois
        $totalFee = ($pickupDistrict === $deliveryDistrict) 
            ? max($pickupFeeAmount, $deliveryFeeAmount)
            : ($pickupFeeAmount + $deliveryFeeAmount);
        
        return response()->json([
            'success' => true,
            'pickup_fee' => $pickupFeeAmount,
            'delivery_fee' => $deliveryFeeAmount,
            'total_fee' => $totalFee,
            'formatted_total' => number_format($totalFee, 0, ',', ' ') . ' FCFA',
            'debug' => $debug
        ]);
    }
} 