<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\InvoiceSettings;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    /**
     * Affiche l'historique des commandes de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer toutes les commandes passées triées par date
        $orders = Order::where('user_id', $user->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('history.index', compact('orders'));
    }
    
    /**
     * Affiche les détails d'une commande passée
     */
    public function show($id)
    {
        // Rediriger vers la page de détail des commandes
        return redirect()->route('orders.show', $id);
    }
    
    /**
     * Generate PDF invoice for order
     */
    public function invoice($orderId)
    {
        // Rediriger vers la méthode de téléchargement direct
        Log::info('Redirection de invoice vers downloadInvoice', [
            'order_id' => $orderId,
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('orders.invoice.download', $orderId);
    }

    /**
     * Generate PDF invoice for order (Stream version)
     */
    public function streamInvoice($orderId)
    {
        // Augmenter le temps d'exécution à 120 secondes
        ini_set('max_execution_time', 120);
        // Augmenter la limite de mémoire
        ini_set('memory_limit', '512M');
        
        try {
            $order = Order::with(['user', 'items.service', 'items.pressingService', 'deliveryAddress'])
                ->findOrFail($orderId);
            
            // Vérifier que l'utilisateur actuel est autorisé à voir cette commande
            if (auth()->user()->id !== $order->user_id && !auth()->user()->is_admin) {
                abort(403, 'Vous n\'êtes pas autorisé à voir cette facture.');
            }
            
            // Obtenir les paramètres de facturation
            $company = InvoiceSettings::getInvoiceData();
            
            // Générer un numéro de facture s'il n'existe pas déjà
            if (!$order->invoice_number) {
                $order->invoice_number = 'KKL-' . date('Y') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                $order->save();
            }
            
            $data = [
                'order' => $order,
                'invoice_number' => $order->invoice_number,
                'invoice_date' => Carbon::parse($order->updated_at)->format('d/m/Y'),
                'company' => $company,
            ];
            
            // Enregistrement des informations de débogage
            Log::info('Génération de facture en mode aperçu', [
                'order_id' => $orderId,
                'user_id' => auth()->id()
            ]);
            
            $pdf = PDF::loadView('invoices.template', $data);
            
            // Simplifier les options du PDF
            $pdf->setPaper('a4');
            
            // Nom du fichier de facture pour l'affichage
            $filename = 'apercu-facture-'.$order->id.'.pdf';
            
            // Utiliser stream pour afficher le PDF dans le navigateur
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error("Erreur de génération de facture (stream): " . $e->getMessage(), [
                'order_id' => $orderId,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Impossible de générer la facture. Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Alternative method for PDF generation with direct file output
     */
    public function downloadInvoice($orderId)
    {
        // Augmenter le temps d'exécution à 120 secondes
        ini_set('max_execution_time', 120);
        // Augmenter la limite de mémoire
        ini_set('memory_limit', '512M');
        
        try {
            $order = Order::with(['user', 'items.service', 'items.pressingService', 'deliveryAddress'])
                ->findOrFail($orderId);
            
            // Vérifier que l'utilisateur actuel est autorisé à voir cette commande
            if (auth()->user()->id !== $order->user_id && !auth()->user()->is_admin) {
                abort(403, 'Vous n\'êtes pas autorisé à voir cette facture.');
            }
            
            // Obtenir les paramètres de facturation
            $company = InvoiceSettings::getInvoiceData();
            
            // Générer un numéro de facture s'il n'existe pas déjà
            if (!$order->invoice_number) {
                $order->invoice_number = 'KKL-' . date('Y') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                $order->save();
            }
            
            $data = [
                'order' => $order,
                'invoice_number' => $order->invoice_number,
                'invoice_date' => Carbon::parse($order->updated_at)->format('d/m/Y'),
                'company' => $company,
            ];
            
            // Enregistrer les informations de débogage
            Log::info('Génération de facture en mode téléchargement direct', [
                'order_id' => $orderId,
                'user_id' => auth()->id()
            ]);
            
            // Simplifier au maximum la génération PDF
            $pdf = PDF::loadView('invoices.template', $data);
            
            // Nom du fichier de facture
            $filename = 'facture-klinklin-'.$order->id.'.pdf';
            
            // Utiliser directement le téléchargement depuis la mémoire plutôt que de créer un fichier temporaire
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Erreur de génération de facture (download): " . $e->getMessage(), [
                'order_id' => $orderId,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Impossible de générer la facture. Erreur: ' . $e->getMessage());
        }
    }
} 