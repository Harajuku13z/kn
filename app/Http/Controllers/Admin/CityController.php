<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the cities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::orderBy('name')->get();
        
        return view('admin.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new city.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cities.create');
    }

    /**
     * Store a newly created city in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:cities',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.cities.create')
                ->withErrors($validator)
                ->withInput();
        }

        $city = City::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'Ville ' . $city->name . ' créée avec succès');
    }

    /**
     * Display the specified city.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        return view('admin.cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified city.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    /**
     * Update the specified city in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:cities,name,' . $city->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.cities.edit', $city)
                ->withErrors($validator)
                ->withInput();
        }

        $city->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'Ville ' . $city->name . ' mise à jour avec succès');
    }

    /**
     * Remove the specified city from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $cityName = $city->name;
        
        // Check if the city has related delivery fees
        if ($city->deliveryFees()->exists()) {
            return redirect()
                ->route('admin.cities.index')
                ->with('error', 'Impossible de supprimer la ville ' . $cityName . ' car elle a des quartiers associés');
        }
        
        $city->delete();

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'Ville ' . $cityName . ' supprimée avec succès');
    }

    /**
     * Toggle the active status of a city.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(City $city)
    {
        $city->is_active = !$city->is_active;
        $city->save();
        
        $status = $city->is_active ? 'active' : 'inactive';
        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'Le statut de la ville ' . $city->name . ' a été changé à "' . $status . '"');
    }
}
