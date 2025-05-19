<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pressing;
use App\Models\PressingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PressingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pressings = Pressing::orderBy('name')->paginate(10);
        return view('admin.pressings.index', compact('pressings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pressings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pressings',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'opening_hours' => 'nullable|string',
            'is_active' => 'boolean',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'coverage_areas' => 'nullable|array',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('pressings/logos', 'public');
            $validated['logo'] = 'storage/' . $path;
        }

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle JSON fields
        if (isset($validated['coverage_areas'])) {
            $validated['coverage_areas'] = json_encode($validated['coverage_areas']);
        }

        $pressing = Pressing::create($validated);

        return redirect()->route('admin.pressings.show', $pressing)
            ->with('success', 'Pressing créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pressing $pressing)
    {
        $services = $pressing->services()->orderBy('category')->orderBy('name')->paginate(10);
        return view('admin.pressings.show', compact('pressing', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pressing $pressing)
    {
        return view('admin.pressings.edit', compact('pressing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pressing $pressing)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pressings')->ignore($pressing->id)],
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'opening_hours' => 'nullable|string',
            'is_active' => 'boolean',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'coverage_areas' => 'nullable|array',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Remove old logo if exists
            if ($pressing->logo && Storage::exists('public/' . str_replace('storage/', '', $pressing->logo))) {
                Storage::delete('public/' . str_replace('storage/', '', $pressing->logo));
            }
            
            $path = $request->file('logo')->store('pressings/logos', 'public');
            $validated['logo'] = 'storage/' . $path;
        }

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle JSON fields
        if (isset($validated['coverage_areas'])) {
            $validated['coverage_areas'] = json_encode($validated['coverage_areas']);
        }

        $pressing->update($validated);

        return redirect()->route('admin.pressings.show', $pressing)
            ->with('success', 'Pressing modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pressing $pressing)
    {
        // Check if this pressing has associated services
        if ($pressing->services()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce pressing car il possède des services associés.');
        }

        // Check if this pressing has orders
        if ($pressing->orders()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce pressing car il possède des commandes associées.');
        }

        // Delete logo if exists
        if ($pressing->logo && Storage::exists('public/' . str_replace('storage/', '', $pressing->logo))) {
            Storage::delete('public/' . str_replace('storage/', '', $pressing->logo));
        }

        $pressing->delete();

        return redirect()->route('admin.pressings.index')
            ->with('success', 'Pressing supprimé avec succès.');
    }
}
