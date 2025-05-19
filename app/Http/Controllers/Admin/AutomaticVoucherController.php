<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\DeliveryVoucher;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AutomaticVoucherController extends Controller
{
    public function index()
    {
        // Récupérer les paramètres des règles depuis les configurations
        $settings = [
            'first_order_enabled' => config('vouchers.first_order_enabled', false),
            'first_order_deliveries' => config('vouchers.first_order_deliveries', 1),
            
            'nth_order_enabled' => config('vouchers.nth_order_enabled', false),
            'nth_order_count' => config('vouchers.nth_order_count', 10),
            'nth_order_deliveries' => config('vouchers.nth_order_deliveries', 1),
            
            'period_enabled' => config('vouchers.period_enabled', false),
            'period_start' => config('vouchers.period_start'),
            'period_end' => config('vouchers.period_end'),
            'period_deliveries' => config('vouchers.period_deliveries', 1),
        ];
        
        return view('admin.automatic-vouchers.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_order_enabled' => 'boolean',
            'first_order_deliveries' => 'required_if:first_order_enabled,1|integer|min:1',
            
            'nth_order_enabled' => 'boolean',
            'nth_order_count' => 'required_if:nth_order_enabled,1|integer|min:2',
            'nth_order_deliveries' => 'required_if:nth_order_enabled,1|integer|min:1',
            
            'period_enabled' => 'boolean',
            'period_start' => 'required_if:period_enabled,1|date',
            'period_end' => 'required_if:period_enabled,1|date|after:period_start',
            'period_deliveries' => 'required_if:period_enabled,1|integer|min:1',
        ]);
        
        // Sauvegarder les paramètres dans config ou settings table
        foreach ($validated as $key => $value) {
            config(['vouchers.' . $key => $value]);
            
            // Si vous utilisez un système de paramètres en base de données
            // Setting::updateOrCreate(['key' => 'vouchers.' . $key], ['value' => $value]);
        }
        
        // Écrire dans le fichier de configuration (si applicable)
        // $this->writeConfig($validated);
        
        return redirect()->route('admin.automatic-vouchers.index')
            ->with('success', 'Règles d\'attribution automatique mises à jour avec succès');
    }
    
    // Cette fonction gère les déclencheurs des bons automatiques et est appelée par des events/jobs
    public function processAutomaticVouchers($userId, $orderId = null)
    {
        $user = User::findOrFail($userId);
        
        // Vérifier si c'est la première commande
        if (config('vouchers.first_order_enabled')) {
            $this->processFirstOrderVoucher($user);
        }
        
        // Vérifier si c'est la Nième commande
        if (config('vouchers.nth_order_enabled') && $orderId) {
            $this->processNthOrderVoucher($user, $orderId);
        }
        
        // Vérifier si nous sommes dans la période définie
        if (config('vouchers.period_enabled')) {
            $this->processPeriodVoucher($user);
        }
    }
    
    private function processFirstOrderVoucher(User $user)
    {
        // Vérifier si c'est la première commande de l'utilisateur
        $orderCount = Order::where('user_id', $user->id)->count();
        
        if ($orderCount === 1) {
            // Créer un bon de livraison pour la première commande
            $this->createDeliveryVoucher(
                $user,
                'Livraison gratuite - Première commande',
                'Nous vous offrons une livraison gratuite pour votre première commande',
                config('vouchers.first_order_deliveries', 1)
            );
        }
    }
    
    private function processNthOrderVoucher(User $user, $orderId)
    {
        $nthCount = config('vouchers.nth_order_count', 10);
        
        // Compter le nombre de commandes de l'utilisateur
        $orderCount = Order::where('user_id', $user->id)->count();
        
        // Vérifier si c'est la Nième commande
        if ($orderCount > 0 && $orderCount % $nthCount === 0) {
            // Créer un bon de livraison pour la Nième commande
            $this->createDeliveryVoucher(
                $user,
                'Livraison gratuite - Fidélité',
                'Nous vous offrons une livraison gratuite pour votre ' . $orderCount . 'ème commande',
                config('vouchers.nth_order_deliveries', 1)
            );
        }
    }
    
    private function processPeriodVoucher(User $user)
    {
        $periodStart = Carbon::parse(config('vouchers.period_start'));
        $periodEnd = Carbon::parse(config('vouchers.period_end'));
        $now = Carbon::now();
        
        // Vérifier si nous sommes dans la période définie
        if ($now->between($periodStart, $periodEnd)) {
            // Vérifier si l'utilisateur a déjà reçu un bon pour cette période
            $existingVoucher = DeliveryVoucher::where('user_id', $user->id)
                ->where('description', 'LIKE', 'Livraison gratuite - Période promotionnelle%')
                ->where('created_at', '>=', $periodStart)
                ->where('created_at', '<=', $periodEnd)
                ->exists();
                
            if (!$existingVoucher) {
                // Créer un bon de livraison pour la période spéciale
                $this->createDeliveryVoucher(
                    $user,
                    'Livraison gratuite - Période promotionnelle',
                    'Nous vous offrons une livraison gratuite pendant notre période promotionnelle',
                    config('vouchers.period_deliveries', 1),
                    $periodEnd
                );
            }
        }
    }
    
    private function createDeliveryVoucher(User $user, $title, $description, $deliveries, $validUntil = null)
    {
        // Générer un code unique
        $code = 'DV-' . Str::upper(Str::random(6));
        
        // Créer le bon de livraison
        DeliveryVoucher::create([
            'user_id' => $user->id,
            'code' => $code,
            'description' => $title,
            'reason' => $description,
            'number_of_deliveries' => $deliveries,
            'used_deliveries' => 0,
            'valid_from' => Carbon::now(),
            'valid_until' => $validUntil,
            'is_active' => true
        ]);
        
        // Notifier l'utilisateur (à implémenter selon votre système de notification)
        // $user->notify(new DeliveryVoucherCreated($code, $title));
    }
}
