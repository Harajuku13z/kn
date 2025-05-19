<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeCoupons = Coupon::where('is_active', true)
            ->orderBy('valid_until', 'asc')
            ->get();
            
        $expiredCoupons = Coupon::where('is_active', false)
            ->orWhere(function($query) {
                $query->where('valid_until', '<', now())
                    ->whereNotNull('valid_until');
            })
            ->orderBy('valid_until', 'desc')
            ->get();
            
        return view('admin.coupons.index', [
            'activeCoupons' => $activeCoupons,
            'expiredCoupons' => $expiredCoupons
        ]);
    }

    /**
     * Show the form for creating a new coupon.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'nullable|string|max:50|unique:coupons,code',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
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
        
        // Définir l'utilisateur qui crée le coupon
        $validatedData['created_by'] = Auth::id();
        
        // Créer le coupon
        $coupon = Coupon::create($validatedData);
        
        return redirect()->route('admin.coupons.show', $coupon)
            ->with('success', 'Coupon créé avec succès !');
    }

    /**
     * Display the specified coupon.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        // Charger les utilisations du coupon
        $orders = $coupon->orders()->with('user')->latest()->paginate(10);
        
        return view('admin.coupons.show', [
            'coupon' => $coupon,
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for editing the specified coupon.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', [
            'coupon' => $coupon
        ]);
    }

    /**
     * Update the specified coupon in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
        ]);
        
        // Convertir les dates
        $validatedData['valid_from'] = Carbon::parse($validatedData['valid_from']);
        if (!empty($validatedData['valid_until'])) {
            $validatedData['valid_until'] = Carbon::parse($validatedData['valid_until']);
        }
        
        // Mettre à jour le coupon
        $coupon->update($validatedData);
        
        return redirect()->route('admin.coupons.show', $coupon)
            ->with('success', 'Coupon mis à jour avec succès !');
    }

    /**
     * Remove the specified coupon from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        // Si le coupon a été utilisé, le désactiver plutôt que le supprimer
        if ($coupon->used_count > 0) {
            $coupon->is_active = false;
            $coupon->save();
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon désactivé avec succès !');
        }
        
        // Sinon, le supprimer complètement
        $coupon->delete();
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon supprimé avec succès !');
    }
    
    /**
     * Generate a unique coupon code.
     *
     * @return string
     */
    private function generateUniqueCode()
    {
        $code = strtoupper(Str::random(8));
        
        // S'assurer que le code est unique
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }
        
        return $code;
    }
}
