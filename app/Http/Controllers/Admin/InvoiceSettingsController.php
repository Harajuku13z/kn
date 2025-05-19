<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoiceSettings;

class InvoiceSettingsController extends Controller
{
    /**
     * Affiche le formulaire d'édition des paramètres de facturation.
     */
    public function index()
    {
        $settings = InvoiceSettings::getActive() ?? new InvoiceSettings();
        return view('admin.invoice_settings.index', compact('settings'));
    }

    /**
     * Met à jour les paramètres de facturation.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'invoice_notes' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'bank_iban' => 'nullable|string|max:100',
            'bank_bic' => 'nullable|string|max:50',
        ]);

        // Récupérer les paramètres existants ou en créer un nouveau
        $settings = InvoiceSettings::getActive();
        
        if (!$settings) {
            $settings = new InvoiceSettings();
            $settings->is_active = true;
        }
        
        // Mettre à jour les paramètres
        $settings->fill($validatedData);
        $settings->save();
        
        return redirect()->route('admin.invoice-settings.index')
            ->with('success', 'Les paramètres de facturation ont été mis à jour avec succès!');
    }
    
    /**
     * Aperçu de la facture avec les paramètres actuels.
     */
    public function preview()
    {
        // Récupérer une commande récente pour la prévisualisation
        $order = \App\Models\Order::with(['items', 'pickupAddress', 'deliveryAddress', 'user'])
            ->latest()
            ->first();
            
        if (!$order) {
            return redirect()->route('admin.invoice-settings.index')
                ->with('error', 'Aucune commande trouvée pour la prévisualisation de la facture.');
        }
        
        // Générer un numéro de facture d'exemple
        $invoiceNumber = 'FACT-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        
        // Préparer les données pour la facture
        $data = [
            'order' => $order,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => \Carbon\Carbon::now()->format('d/m/Y'),
            'company' => InvoiceSettings::getInvoiceData()
        ];
        
        // Générer le PDF pour prévisualisation
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.template', $data);
        
        return $pdf->stream('apercu_facture.pdf');
    }
}
