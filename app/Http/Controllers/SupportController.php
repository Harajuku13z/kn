<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\AutoReplyTemplate;
use App\Notifications\SupportTicketCreated;
use App\Notifications\SupportTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    /**
     * Affiche la page d'aide et support
     */
    public function index()
    {
        // Récupérer les tickets avec leur dernier message en utilisant une sous-requête
        $latestMessageDates = DB::table('support_messages')
            ->select('support_ticket_id', DB::raw('MAX(created_at) as latest_message_date'))
            ->groupBy('support_ticket_id');
            
        $tickets = SupportTicket::with(['latestMessage'])
            ->where('support_tickets.user_id', Auth::id())
            ->joinSub($latestMessageDates, 'latest_messages', function($join) {
                $join->on('support_tickets.id', '=', 'latest_messages.support_ticket_id');
            })
            ->orderBy('latest_messages.latest_message_date', 'desc')
            ->get();
        
        $categories = [
            SupportTicket::CATEGORY_GENERAL => 'Question générale',
            SupportTicket::CATEGORY_ACCOUNT => 'Compte',
            SupportTicket::CATEGORY_ORDERS => 'Commandes',
            SupportTicket::CATEGORY_PAYMENT => 'Paiement',
            SupportTicket::CATEGORY_SUBSCRIPTION => 'Abonnement',
            SupportTicket::CATEGORY_TECHNICAL => 'Problème technique',
        ];
        
        return view('support.index', compact('tickets', 'categories'));
    }
    
    /**
     * Affiche la FAQ
     */
    public function faq()
    {
        return view('support.faq');
    }
    
    /**
     * Affiche le formulaire de création d'un ticket
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
        
        return view('support.create', compact('categories'));
    }
    
    /**
     * Enregistre un nouveau ticket
     */
    public function store(Request $request)
    {
        \Log::info('Début de la méthode store', [
            'request' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'is_ajax' => $request->ajax(),
            'has_file' => $request->hasFile('file'),
            'user_id' => Auth::id(),
            'user_authenticated' => Auth::check()
        ]);
        
        try {
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
                'category' => 'required|string|in:' . implode(',', [
                    SupportTicket::CATEGORY_GENERAL,
                    SupportTicket::CATEGORY_ACCOUNT,
                    SupportTicket::CATEGORY_ORDERS,
                    SupportTicket::CATEGORY_PAYMENT,
                    SupportTicket::CATEGORY_SUBSCRIPTION,
                    SupportTicket::CATEGORY_TECHNICAL,
                ]),
            ]);
            
            \Log::info('Validation réussie', ['validated' => $validated]);
            
            // Créer le ticket
            $ticket = new SupportTicket();
            $ticket->user_id = Auth::id();
            $ticket->subject = $request->subject;
            $ticket->category = $request->category;
            $ticket->status = SupportTicket::STATUS_OPEN;
            $ticket->priority = SupportTicket::PRIORITY_MEDIUM;
            $ticket->reference_number = SupportTicket::generateReferenceNumber();
            $ticket->save();
            
            \Log::info('Ticket créé', ['ticket_id' => $ticket->id, 'ticket' => $ticket->toArray()]);
            
            // Créer le premier message
            $message = new SupportMessage();
            $message->support_ticket_id = $ticket->id;
            $message->user_id = Auth::id();
            $message->message = $request->message;
            $message->is_from_admin = false;
            $message->save();
            
            \Log::info('Message créé', ['message_id' => $message->id, 'message' => $message->toArray()]);
            
            // Envoyer une notification à l'utilisateur
            $user = Auth::user();
            $user->notify(new SupportTicketCreated($ticket));
            
            \Log::info('Notification envoyée à l\'utilisateur');
            
            // Vérifier s'il y a un modèle de réponse automatique pour cette catégorie
            $autoReplyTemplate = AutoReplyTemplate::forCategory($request->category)->first();
            
            if ($autoReplyTemplate) {
                // Créer une réponse automatique
                $autoReply = new SupportMessage();
                $autoReply->support_ticket_id = $ticket->id;
                $autoReply->user_id = 1; // ID de l'administrateur système
                $autoReply->message = $autoReplyTemplate->parseContent([
                    'user_name' => Auth::user()->name,
                    'ticket_reference' => $ticket->reference_number,
                    'ticket_subject' => $ticket->subject,
                ]);
                $autoReply->is_from_admin = true;
                $autoReply->is_auto_reply = true;
                $autoReply->save();
                
                // Notifier l'utilisateur de la réponse automatique
                $user->notify(new SupportTicketReply($autoReply));
                
                \Log::info('Réponse automatique créée', ['auto_reply_id' => $autoReply->id]);
            }
            
            \Log::info('Redirection vers la page du ticket', ['route' => route('support.show', $ticket->id)]);
            
            return redirect()->route('support.show', $ticket->id)
                ->with('success', 'Votre ticket a été créé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du ticket', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du ticket: ' . $e->getMessage());
        }
    }
    
    /**
     * Affiche un ticket spécifique
     */
    public function show($id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $messages = $ticket->messages()
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Marquer les messages non lus comme lus
        foreach ($messages as $message) {
            if ($message->is_from_admin && !$message->isRead()) {
                $message->markAsRead();
            }
        }
        
        return view('support.show', compact('ticket', 'messages'));
    }
    
    /**
     * Ajoute un message à un ticket existant
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Si le ticket est fermé, le rouvrir
        if ($ticket->isClosed()) {
            $ticket->reopen();
        }
        
        // Créer le message
        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->user_id = Auth::id();
        $message->message = $request->message;
        $message->is_from_admin = false;
        $message->save();
        
        // Mettre à jour le statut du ticket
        $ticket->status = SupportTicket::STATUS_WAITING_USER;
        $ticket->save();
        
        // Notifier les administrateurs (à implémenter plus tard)
        // Pour l'instant, nous supposons que les administrateurs verront les tickets en attente dans leur interface
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre réponse a été envoyée avec succès.',
                'html' => view('support.partials.message', compact('message'))->render()
            ]);
        }
        
        return redirect()->route('support.show', $ticket->id)
            ->with('success', 'Votre réponse a été envoyée avec succès.');
    }
    
    /**
     * Ferme un ticket
     */
    public function close(Request $request, $id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $ticket->close();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Le ticket a été fermé avec succès.',
                'status' => $ticket->getStatusLabel()
            ]);
        }
        
        return redirect()->route('support.show', $ticket->id)
            ->with('success', 'Le ticket a été fermé avec succès.');
    }
    
    /**
     * Rouvre un ticket
     */
    public function reopen(Request $request, $id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $ticket->reopen();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Le ticket a été rouvert avec succès.',
                'status' => $ticket->getStatusLabel()
            ]);
        }
        
        return redirect()->route('support.show', $ticket->id)
            ->with('success', 'Le ticket a été rouvert avec succès.');
    }
}
