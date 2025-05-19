<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoReplyTemplate;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AutoReplyTemplateController extends Controller
{
    /**
     * Affiche la liste des modèles de réponses automatiques
     */
    public function index()
    {
        $templates = AutoReplyTemplate::orderBy('name')->get();
        
        $categories = [
            SupportTicket::CATEGORY_GENERAL => 'Question générale',
            SupportTicket::CATEGORY_ACCOUNT => 'Compte',
            SupportTicket::CATEGORY_ORDERS => 'Commandes',
            SupportTicket::CATEGORY_PAYMENT => 'Paiement',
            SupportTicket::CATEGORY_SUBSCRIPTION => 'Abonnement',
            SupportTicket::CATEGORY_TECHNICAL => 'Problème technique',
        ];
        
        return view('admin.support.templates.index', compact('templates', 'categories'));
    }
    
    /**
     * Affiche le formulaire de création d'un modèle
     */
    public function create()
    {
        $categories = [
            SupportTicket::CATEGORY_GENERAL => 'Question générale',
            SupportTicket::CATEGORY_ACCOUNT => 'Compte',
            SupportTicket::CATEGORY_ORDERS => 'Commandes',
            SupportTicket::CATEGORY_PAYMENT => 'Paiement',
            SupportTicket::CATEGORY_SUBSCRIPTION => 'Abonnement',
            SupportTicket::CATEGORY_TECHNICAL => 'Problème technique',
        ];
        
        return view('admin.support.templates.create', compact('categories'));
    }
    
    /**
     * Enregistre un nouveau modèle
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', [
                SupportTicket::CATEGORY_GENERAL,
                SupportTicket::CATEGORY_ACCOUNT,
                SupportTicket::CATEGORY_ORDERS,
                SupportTicket::CATEGORY_PAYMENT,
                SupportTicket::CATEGORY_SUBSCRIPTION,
                SupportTicket::CATEGORY_TECHNICAL,
            ]),
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $template = new AutoReplyTemplate();
        $template->name = $request->name;
        $template->category = $request->category;
        $template->subject = $request->subject;
        $template->content = $request->content;
        $template->is_active = $request->has('is_active');
        $template->save();
        
        return redirect()->route('admin.support.templates.index')
            ->with('success', 'Le modèle de réponse automatique a été créé avec succès.');
    }
    
    /**
     * Affiche le formulaire d'édition d'un modèle
     */
    public function edit($id)
    {
        $template = AutoReplyTemplate::findOrFail($id);
        
        $categories = [
            SupportTicket::CATEGORY_GENERAL => 'Question générale',
            SupportTicket::CATEGORY_ACCOUNT => 'Compte',
            SupportTicket::CATEGORY_ORDERS => 'Commandes',
            SupportTicket::CATEGORY_PAYMENT => 'Paiement',
            SupportTicket::CATEGORY_SUBSCRIPTION => 'Abonnement',
            SupportTicket::CATEGORY_TECHNICAL => 'Problème technique',
        ];
        
        return view('admin.support.templates.edit', compact('template', 'categories'));
    }
    
    /**
     * Met à jour un modèle existant
     */
    public function update(Request $request, $id)
    {
        $template = AutoReplyTemplate::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', [
                SupportTicket::CATEGORY_GENERAL,
                SupportTicket::CATEGORY_ACCOUNT,
                SupportTicket::CATEGORY_ORDERS,
                SupportTicket::CATEGORY_PAYMENT,
                SupportTicket::CATEGORY_SUBSCRIPTION,
                SupportTicket::CATEGORY_TECHNICAL,
            ]),
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $template->name = $request->name;
        $template->category = $request->category;
        $template->subject = $request->subject;
        $template->content = $request->content;
        $template->is_active = $request->has('is_active');
        $template->save();
        
        return redirect()->route('admin.support.templates.index')
            ->with('success', 'Le modèle de réponse automatique a été mis à jour avec succès.');
    }
    
    /**
     * Supprime un modèle
     */
    public function destroy($id)
    {
        $template = AutoReplyTemplate::findOrFail($id);
        $template->delete();
        
        return redirect()->route('admin.support.templates.index')
            ->with('success', 'Le modèle de réponse automatique a été supprimé avec succès.');
    }
    
    /**
     * Récupère un modèle au format JSON
     */
    public function getTemplate($id)
    {
        $template = AutoReplyTemplate::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'template' => [
                'id' => $template->id,
                'name' => $template->name,
                'subject' => $template->subject,
                'content' => $template->content,
            ]
        ]);
    }
    
    /**
     * Modifie le statut actif/inactif d'un modèle
     */
    public function toggleActive(Request $request, $id)
    {
        $template = AutoReplyTemplate::findOrFail($id);
        $template->is_active = !$template->is_active;
        $template->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $template->is_active,
                'message' => $template->is_active 
                    ? 'Le modèle a été activé avec succès.' 
                    : 'Le modèle a été désactivé avec succès.'
            ]);
        }
        
        return redirect()->route('admin.support.templates.index')
            ->with('success', $template->is_active 
                ? 'Le modèle a été activé avec succès.' 
                : 'Le modèle a été désactivé avec succès.');
    }
}
