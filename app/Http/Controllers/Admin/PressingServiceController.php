<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pressing;
use App\Models\PressingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PressingServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pressingId = $request->pressing_id;
        
        if ($pressingId) {
            $pressing = Pressing::findOrFail($pressingId);
            $services = $pressing->services()->orderBy('category')->orderBy('name')->paginate(10);
            
            return view('admin.pressing_services.index', [
                'services' => $services,
                'pressing' => $pressing
            ]);
        }
        
        $services = PressingService::with('pressing')
            ->orderBy('pressing_id')
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(15);
        
        return view('admin.pressing_services.index', [
            'services' => $services,
            'pressing' => null
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pressingId = $request->pressing_id;
        $pressing = null;
        
        if ($pressingId) {
            $pressing = Pressing::findOrFail($pressingId);
        }
        
        $pressings = Pressing::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.pressing_services.create', [
            'pressings' => $pressings,
            'pressing' => $pressing
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pressing_id' => 'required|exists:pressings,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048', // max 2MB
            'is_available' => 'boolean',
            'estimated_time' => 'nullable|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('pressings/services', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $service = PressingService::create($validated);
        $pressing = Pressing::find($validated['pressing_id']);

        return redirect()->route('admin.pressing-services.index', ['pressing_id' => $pressing->id])
            ->with('success', 'Service créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PressingService $pressingService)
    {
        return view('admin.pressing_services.show', [
            'service' => $pressingService,
            'pressing' => $pressingService->pressing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PressingService $pressingService)
    {
        $pressings = Pressing::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.pressing_services.edit', [
            'service' => $pressingService,
            'pressing' => $pressingService->pressing,
            'pressings' => $pressings
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PressingService $pressingService)
    {
        $validated = $request->validate([
            'pressing_id' => 'required|exists:pressings,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048', // max 2MB
            'is_available' => 'boolean',
            'estimated_time' => 'nullable|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($pressingService->image && Storage::exists('public/' . str_replace('storage/', '', $pressingService->image))) {
                Storage::delete('public/' . str_replace('storage/', '', $pressingService->image));
            }
            
            $path = $request->file('image')->store('pressings/services', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $pressingService->update($validated);
        $pressing = Pressing::find($validated['pressing_id']);

        return redirect()->route('admin.pressing-services.index', ['pressing_id' => $pressing->id])
            ->with('success', 'Service modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PressingService $pressingService)
    {
        $pressingId = $pressingService->pressing_id;
        
        // Check if this service has associated order items
        $hasOrders = $pressingService->orderItems()->count() > 0;
        if ($hasOrders) {
            return back()->with('error', 'Impossible de supprimer ce service car il est utilisé dans des commandes.');
        }

        // Delete image if exists
        if ($pressingService->image && Storage::exists('public/' . str_replace('storage/', '', $pressingService->image))) {
            Storage::delete('public/' . str_replace('storage/', '', $pressingService->image));
        }

        $pressingService->delete();

        return redirect()->route('admin.pressing-services.index', ['pressing_id' => $pressingId])
            ->with('success', 'Service supprimé avec succès.');
    }
}
