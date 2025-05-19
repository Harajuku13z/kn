<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryFee;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeliveryFeeController extends Controller
{
    /**
     * Display a listing of the delivery fees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveryFees = DeliveryFee::with('city')
            ->orderBy('city_id')
            ->orderBy('district')
            ->get();
        
        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.delivery-fees.index', compact('deliveryFees', 'cities'));
    }

    /**
     * Show the form for creating a new delivery fee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.delivery-fees.create', compact('cities'));
    }

    /**
     * Store a newly created delivery fee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'district' => 'required|string|max:255',
            'fee' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.delivery-fees.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if district already exists in this city
        $existingFee = DeliveryFee::where('city_id', $request->city_id)
            ->where('district', $request->district)
            ->first();
            
        if ($existingFee) {
            return redirect()
                ->route('admin.delivery-fees.create')
                ->withErrors(['district' => 'Ce quartier existe déjà dans cette ville.'])
                ->withInput();
        }

        $deliveryFee = DeliveryFee::create([
            'city_id' => $request->city_id,
            'district' => $request->district,
            'fee' => $request->fee,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()
            ->route('admin.delivery-fees.index')
            ->with('success', 'Frais de livraison pour le quartier ' . $deliveryFee->district . ' créé avec succès');
    }

    /**
     * Display the specified delivery fee.
     *
     * @param  \App\Models\DeliveryFee  $deliveryFee
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryFee $deliveryFee)
    {
        $deliveryFee->load('city');
        return view('admin.delivery-fees.show', compact('deliveryFee'));
    }

    /**
     * Show the form for editing the specified delivery fee.
     *
     * @param  \App\Models\DeliveryFee  $deliveryFee
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryFee $deliveryFee)
    {
        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.delivery-fees.edit', compact('deliveryFee', 'cities'));
    }

    /**
     * Update the specified delivery fee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryFee  $deliveryFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryFee $deliveryFee)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'district' => 'required|string|max:255',
            'fee' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.delivery-fees.edit', $deliveryFee)
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if district already exists in this city (excluding current record)
        $existingFee = DeliveryFee::where('city_id', $request->city_id)
            ->where('district', $request->district)
            ->where('id', '!=', $deliveryFee->id)
            ->first();
            
        if ($existingFee) {
            return redirect()
                ->route('admin.delivery-fees.edit', $deliveryFee)
                ->withErrors(['district' => 'Ce quartier existe déjà dans cette ville.'])
                ->withInput();
        }

        $deliveryFee->update([
            'city_id' => $request->city_id,
            'district' => $request->district,
            'fee' => $request->fee,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()
            ->route('admin.delivery-fees.index')
            ->with('success', 'Frais de livraison pour le quartier ' . $deliveryFee->district . ' mis à jour avec succès');
    }

    /**
     * Remove the specified delivery fee from storage.
     *
     * @param  \App\Models\DeliveryFee  $deliveryFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryFee $deliveryFee)
    {
        $district = $deliveryFee->district;
        
        $deliveryFee->delete();

        return redirect()
            ->route('admin.delivery-fees.index')
            ->with('success', 'Frais de livraison pour le quartier ' . $district . ' supprimé avec succès');
    }
    
    /**
     * Bulk update delivery fees
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkUpdate(Request $request)
    {
        \Log::info('Bulk update request received', ['request' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'fees' => 'required|array',
            'fees.*.id' => 'required|exists:delivery_fees,id',
            'fees.*.fee' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
            return redirect()
                ->route('admin.delivery-fees.index')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            foreach ($request->fees as $feeData) {
                $deliveryFee = DeliveryFee::find($feeData['id']);
                if ($deliveryFee) {
                    \Log::info('Updating delivery fee', [
                        'id' => $deliveryFee->id,
                        'old_fee' => $deliveryFee->fee,
                        'new_fee' => $feeData['fee'],
                        'old_status' => $deliveryFee->is_active,
                        'new_status' => isset($feeData['is_active']) ? true : false
                    ]);
                    
                    $deliveryFee->update([
                        'fee' => $feeData['fee'],
                        'is_active' => isset($feeData['is_active']) ? true : false,
                    ]);
                }
            }
            
            DB::commit();
            \Log::info('Bulk update successful');
            
            return redirect()
                ->route('admin.delivery-fees.index')
                ->with('success', 'Tous les frais de livraison ont été mis à jour avec succès');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk update failed', ['exception' => $e->getMessage()]);
            
            return redirect()
                ->route('admin.delivery-fees.index')
                ->with('error', 'Une erreur est survenue lors de la mise à jour des frais de livraison: ' . $e->getMessage());
        }
    }
    
    /**
     * Get delivery fees for a specific city
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFeesForCity(Request $request)
    {
        $cityId = $request->city_id;
        $fees = [];
        
        if ($cityId) {
            $fees = DeliveryFee::where('city_id', $cityId)
                ->where('is_active', true)
                ->orderBy('district')
                ->get()
                ->map(function ($fee) {
                    return [
                        'id' => $fee->id,
                        'district' => $fee->district,
                        'fee' => $fee->fee,
                        'formatted_fee' => $fee->formatted_fee
                    ];
                });
        }
        
        return response()->json([
            'success' => true,
            'fees' => $fees
        ]);
    }
} 