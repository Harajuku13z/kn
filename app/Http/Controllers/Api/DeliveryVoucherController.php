<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryVoucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryVoucherController extends Controller
{
    /**
     * Vérifie si un bon de livraison est valide
     */
    public function checkVoucher(Request $request)
    {
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
        if ($voucher->user_id != Auth::id()) {
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
        if ($voucher->valid_from && $voucher->valid_from > Carbon::now()) {
            return response()->json([
                'valid' => false,
                'message' => 'Ce bon de livraison n\'est pas encore valable.'
            ]);
        }
        
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
    }
    
    /**
     * Récupérer les bons de livraison disponibles pour l'utilisateur
     */
    public function getAvailableVouchers()
    {
        // Vérifier l'authentification
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }
        
        // Récupérer les bons de livraison disponibles pour l'utilisateur connecté
        $vouchers = DeliveryVoucher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('valid_from')
                      ->orWhere('valid_from', '<=', Carbon::now());
            })
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
    }
} 