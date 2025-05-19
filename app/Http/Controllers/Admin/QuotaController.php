<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotas = \App\Models\Quota::with('user')->latest()->paginate(15);
        return view('admin.quotas.index', compact('quotas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.quotas.create', compact('users'));
    }

    /**
     * Show the form for creating a new quota for a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function createForm($userId)
    {
        $user = User::findOrFail($userId);
        $currentPrice = \App\Models\PriceConfiguration::getCurrentPricePerKg(1000);
        
        return view('admin.quotas.create_form', compact('user', 'currentPrice'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kilograms' => 'required|numeric|min:1',
            'expiration_date' => 'required|date|after:today',
            'status' => 'required|in:active,inactive,expired',
        ]);
        
        // Création du quota
        $quota = new \App\Models\Quota();
        $quota->user_id = $request->user_id;
        $quota->kilograms = $request->kilograms;
        $quota->kilograms_used = 0;
        $quota->expiration_date = $request->expiration_date;
        $quota->status = $request->status;
        $quota->notes = $request->notes;
        $quota->save();
        
        return redirect()->route('admin.quotas.index')->with('success', 'Quota créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quota = \App\Models\Quota::with('user')->findOrFail($id);
        return view('admin.quotas.show', compact('quota'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quota = \App\Models\Quota::findOrFail($id);
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.quotas.edit', compact('quota', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kilograms' => 'required|numeric|min:1',
            'kilograms_used' => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'status' => 'required|in:active,inactive,expired',
        ]);
        
        // Mise à jour du quota
        $quota = \App\Models\Quota::findOrFail($id);
        $quota->user_id = $request->user_id;
        $quota->kilograms = $request->kilograms;
        $quota->kilograms_used = $request->kilograms_used;
        $quota->expiration_date = $request->expiration_date;
        $quota->status = $request->status;
        $quota->notes = $request->notes;
        $quota->save();
        
        return redirect()->route('admin.quotas.index')->with('success', 'Quota mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quota = \App\Models\Quota::findOrFail($id);
        $quota->delete();
        
        return redirect()->route('admin.quotas.index')->with('success', 'Quota supprimé avec succès.');
    }
} 