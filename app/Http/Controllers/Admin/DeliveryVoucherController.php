<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryVoucher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DeliveryVoucherController extends Controller
{
    /**
     * Display a listing of delivery vouchers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeVouchers = DeliveryVoucher::with('user')
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        $expiredVouchers = DeliveryVoucher::with('user')
            ->where('is_active', false)
            ->orWhere(function($query) {
                $query->where('valid_until', '<', now())
                    ->whereNotNull('valid_until');
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.delivery-vouchers.index', [
            'activeVouchers' => $activeVouchers,
            'expiredVouchers' => $expiredVouchers
        ]);
    }

    /**
     * Show the form for creating a new delivery voucher.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        
        return view('admin.delivery-vouchers.create', [
            'users' => $users
        ]);
    }

    /**
     * Store a newly created delivery voucher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'nullable|string|max:50|unique:delivery_vouchers,code',
            'description' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'number_of_deliveries' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'reason' => 'nullable|string|max:255',
        ]);
        
        // Générer un code aléatoire si non fourni
        if (empty($validatedData['code'])) {
            $validatedData['code'] = $this->generateUniqueCode();
        }
        
        // Convertir les dates
        $validatedData['valid_from'] = Carbon::parse($validatedData['valid_from']);
        if (!empty($validatedData['valid_until'])) {
            $validatedData['valid_until'] = Carbon::parse($validatedData['valid_until']);
        }
        
        // Définir l'utilisateur qui crée le bon
        $validatedData['created_by'] = Auth::id();
        
        // Créer le bon de livraison
        $voucher = DeliveryVoucher::create($validatedData);
        
        return redirect()->route('admin.delivery-vouchers.show', $voucher)
            ->with('success', 'Bon de livraison créé avec succès !');
    }

    /**
     * Display the specified delivery voucher.
     *
     * @param  \App\Models\DeliveryVoucher  $deliveryVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryVoucher $deliveryVoucher)
    {
        // Charger les utilisations du bon
        $orders = $deliveryVoucher->orders()->latest()->paginate(10);
        
        return view('admin.delivery-vouchers.show', [
            'voucher' => $deliveryVoucher,
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for editing the specified delivery voucher.
     *
     * @param  \App\Models\DeliveryVoucher  $deliveryVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryVoucher $deliveryVoucher)
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        
        return view('admin.delivery-vouchers.edit', [
            'voucher' => $deliveryVoucher,
            'users' => $users
        ]);
    }

    /**
     * Update the specified delivery voucher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryVoucher  $deliveryVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryVoucher $deliveryVoucher)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:50|unique:delivery_vouchers,code,' . $deliveryVoucher->id,
            'description' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'number_of_deliveries' => 'required|integer|min:' . $deliveryVoucher->used_deliveries,
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'reason' => 'nullable|string|max:255',
        ]);
        
        // Convertir les dates
        $validatedData['valid_from'] = Carbon::parse($validatedData['valid_from']);
        if (!empty($validatedData['valid_until'])) {
            $validatedData['valid_until'] = Carbon::parse($validatedData['valid_until']);
        }
        
        // Mettre à jour le bon de livraison
        $deliveryVoucher->update($validatedData);
        
        return redirect()->route('admin.delivery-vouchers.show', $deliveryVoucher)
            ->with('success', 'Bon de livraison mis à jour avec succès !');
    }

    /**
     * Remove the specified delivery voucher from storage.
     *
     * @param  \App\Models\DeliveryVoucher  $deliveryVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryVoucher $deliveryVoucher)
    {
        // Si le bon a été utilisé, le désactiver plutôt que le supprimer
        if ($deliveryVoucher->used_deliveries > 0) {
            $deliveryVoucher->is_active = false;
            $deliveryVoucher->save();
            return redirect()->route('admin.delivery-vouchers.index')
                ->with('success', 'Bon de livraison désactivé avec succès !');
        }
        
        // Sinon, le supprimer complètement
        $deliveryVoucher->delete();
        
        return redirect()->route('admin.delivery-vouchers.index')
            ->with('success', 'Bon de livraison supprimé avec succès !');
    }
    
    /**
     * Generate a unique voucher code.
     *
     * @return string
     */
    private function generateUniqueCode()
    {
        $code = 'DV-' . strtoupper(Str::random(6));
        
        // S'assurer que le code est unique
        while (DeliveryVoucher::where('code', $code)->exists()) {
            $code = 'DV-' . strtoupper(Str::random(6));
        }
        
        return $code;
    }
}
