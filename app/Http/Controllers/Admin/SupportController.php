<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\AutoReplyTemplate;
use App\Models\User;
use App\Notifications\SupportTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * Affiche la liste des tickets de support
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with('user')
            ->orderBy('created_at', 'desc');
        
        // Filtrer par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filtrer par priorité
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }
        
        // Filtrer par catégorie
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        
        // Recherche par référence ou sujet
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        $tickets = $query->paginate(15);
        
        $statuses = [
            SupportTicket::STATUS_OPEN => 'Ouvert',
            SupportTicket::STATUS_IN_PROGRESS => 'En traitement',
            SupportTicket::STATUS_WAITING_USER => 'En attente de réponse',
            SupportTicket::STATUS_CLOSED => 'Fermé',
        ];
        
        $priorities = [
            SupportTicket::PRIORITY_LOW => 'Basse',
            SupportTicket::PRIORITY_MEDIUM => 'Moyenne',
            SupportTicket::PRIORITY_HIGH => 'Haute',
            SupportTicket::PRIORITY_URGENT => 'Urgente',
        ];
        
        $categories = [
            SupportTicket::CATEGORY_GENERAL => 'Question générale',
            SupportTicket::CATEGORY_ACCOUNT => 'Compte',
            SupportTicket::CATEGORY_ORDERS => 'Commandes',
            SupportTicket::CATEGORY_PAYMENT => 'Paiement',
            SupportTicket::CATEGORY_SUBSCRIPTION => 'Abonnement',
            SupportTicket::CATEGORY_TECHNICAL => 'Problème technique',
        ];
        
        // Return JSON response if requested
        if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
            $formattedTickets = $tickets->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'reference_number' => $ticket->reference_number,
                    'subject' => $ticket->subject,
                    'status' => [
                        'value' => $ticket->status,
                        'label' => $ticket->getStatusLabel()
                    ],
                    'priority' => [
                        'value' => $ticket->priority,
                        'label' => $ticket->getPriorityLabel()
                    ],
                    'category' => [
                        'value' => $ticket->category,
                        'label' => $ticket->getCategoryLabel()
                    ],
                    'user' => [
                        'id' => $ticket->user->id,
                        'name' => $ticket->user->name,
                        'email' => $ticket->user->email,
                        'gravatar' => md5(strtolower(trim($ticket->user->email)))
                    ],
                    'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $ticket->updated_at->format('Y-m-d H:i:s'),
                    'closed_at' => $ticket->closed_at ? $ticket->closed_at->format('Y-m-d H:i:s') : null,
                    'url' => route('admin.support.show', $ticket->id)
                ];
            });
            
            return response()->json([
                'tickets' => $formattedTickets,
                'pagination' => [
                    'total' => $tickets->total(),
                    'per_page' => $tickets->perPage(),
                    'current_page' => $tickets->currentPage(),
                    'last_page' => $tickets->lastPage(),
                    'from' => $tickets->firstItem(),
                    'to' => $tickets->lastItem()
                ],
                'filters' => [
                    'statuses' => $statuses,
                    'priorities' => $priorities,
                    'categories' => $categories
                ]
            ]);
        }
        
        return view('admin.support.index', compact('tickets', 'statuses', 'priorities', 'categories'));
    }
    
    /**
     * Affiche un ticket spécifique
     */
    public function show($id)
    {
        $ticket = SupportTicket::with('user')->findOrFail($id);
        
        $messages = $ticket->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $autoReplyTemplates = AutoReplyTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        // Passer les constantes du modèle à la vue
        $statuses = [
            'open' => SupportTicket::STATUS_OPEN,
            'in_progress' => SupportTicket::STATUS_IN_PROGRESS,
            'waiting_user' => SupportTicket::STATUS_WAITING_USER,
            'closed' => SupportTicket::STATUS_CLOSED,
        ];

        // Ajouter les statistiques nécessaires pour le dashboard
        $stats = [
            'total_orders' => \App\Models\Order::count(),
            'pending_orders' => \App\Models\Order::where('status', 'pending')->count(),
            'completed_orders' => \App\Models\Order::where('status', 'completed')->count(),
            'total_users' => \App\Models\User::count(),
            'total_tickets' => SupportTicket::count(),
            'open_tickets' => SupportTicket::whereIn('status', [SupportTicket::STATUS_OPEN, SupportTicket::STATUS_IN_PROGRESS])->count(),
            'recent_users' => \App\Models\User::latest()->take(5)->get(),
            'current_price' => null,
        ];
        
        // Récupérer les commandes récentes pour le dashboard
        $recent_orders = \App\Models\Order::with('user')->latest()->take(5)->get();
        
        return view('admin.support.show', compact('ticket', 'messages', 'autoReplyTemplates', 'statuses', 'stats', 'recent_orders'));
    }
    
    /**
     * Ajoute un message à un ticket existant
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'close_after_reply' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Créer le message
        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->user_id = Auth::id();
        $message->message = $request->message;
        $message->is_from_admin = true;
        $message->save();
        
        // Mettre à jour le statut du ticket
        if ($request->has('close_after_reply') && $request->close_after_reply == 1) {
            $ticket->status = SupportTicket::STATUS_CLOSED;
            $ticket->closed_at = now();
        } else {
            $ticket->status = SupportTicket::STATUS_WAITING_USER;
        }
        $ticket->save();
        
        // Envoyer une notification par email
        try {
            $user = $ticket->user;
            
            // Envoyer la notification par email
            \Log::info('Tentative d\'envoi de notification à l\'utilisateur: ' . $user->email);
            
            // Envoyer un email simple au lieu d'utiliser le système de notification
            \Mail::send('emails.support.ticket-reply', [
                'notifiable' => $user,
                'ticket' => $ticket,
                'message_excerpt' => $message->message,
                'ticket_closed' => $ticket->status === SupportTicket::STATUS_CLOSED,
                'action_url' => route('support.show', $ticket->id)
            ], function ($mail) use ($user, $ticket) {
                $mail->to($user->email, $user->name)
                    ->subject('Réponse à votre ticket de support #' . $ticket->reference_number);
            });
            
            \Log::info('Email envoyé avec succès');
            
            // Créer une notification dans la base de données
            $notification = $user->notifications()->create([
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'type' => 'App\Notifications\SupportTicketReply',
                'data' => [
                    'title' => 'Réponse à votre ticket de support',
                    'ticket_id' => $ticket->id,
                    'ticket_subject' => $ticket->subject,
                    'message' => $message->message,
                    'admin_name' => Auth::user()->name,
                    'url' => route('support.show', $ticket->id)
                ],
                'read_at' => null,
            ]);
            
            \Log::info('Notification créée dans la base de données avec ID: ' . $notification->id);
        } catch (\Exception $e) {
            // Log l'erreur détaillée mais continue l'exécution
            \Log::error('Erreur lors de l\'envoi de la notification: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.support.show', $ticket->id)
                ->with('error', 'Votre réponse a été enregistrée, mais l\'envoi de la notification a échoué.');
        }
        
        return redirect()->route('admin.support.show', $ticket->id)
            ->with('success', 'Votre réponse a été envoyée avec succès.');
    }
    
    /**
     * Met à jour le statut d'un ticket
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', [
                SupportTicket::STATUS_OPEN,
                SupportTicket::STATUS_IN_PROGRESS,
                SupportTicket::STATUS_WAITING_USER,
                SupportTicket::STATUS_CLOSED,
            ]),
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $oldStatus = $ticket->status;
        $ticket->status = $request->status;
        
        if ($request->status === SupportTicket::STATUS_CLOSED) {
            $ticket->closed_at = now();
        } elseif ($oldStatus === SupportTicket::STATUS_CLOSED) {
            $ticket->closed_at = null;
        }
        
        $ticket->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Le statut du ticket a été mis à jour avec succès.',
                'status' => $ticket->getStatusLabel()
            ]);
        }
        
        return redirect()->route('admin.support.show', $ticket->id)
            ->with('success', 'Le statut du ticket a été mis à jour avec succès.');
    }
    
    /**
     * Met à jour la priorité d'un ticket
     */
    public function updatePriority(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'priority' => 'required|in:' . implode(',', [
                SupportTicket::PRIORITY_LOW,
                SupportTicket::PRIORITY_MEDIUM,
                SupportTicket::PRIORITY_HIGH,
                SupportTicket::PRIORITY_URGENT,
            ]),
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $ticket->priority = $request->priority;
        $ticket->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'La priorité du ticket a été mise à jour avec succès.',
                'priority' => $ticket->getPriorityLabel()
            ]);
        }
        
        return redirect()->route('admin.support.show', $ticket->id)
            ->with('success', 'La priorité du ticket a été mise à jour avec succès.');
    }
    
    /**
     * Met à jour la catégorie d'un ticket
     */
    public function updateCategory(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'category' => 'required|in:' . implode(',', [
                SupportTicket::CATEGORY_GENERAL,
                SupportTicket::CATEGORY_ACCOUNT,
                SupportTicket::CATEGORY_ORDERS,
                SupportTicket::CATEGORY_PAYMENT,
                SupportTicket::CATEGORY_SUBSCRIPTION,
                SupportTicket::CATEGORY_TECHNICAL,
            ]),
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $ticket->category = $request->category;
        $ticket->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'La catégorie du ticket a été mise à jour avec succès.',
                'category' => $ticket->getCategoryLabel()
            ]);
        }
        
        return redirect()->route('admin.support.show', $ticket->id)
            ->with('success', 'La catégorie du ticket a été mise à jour avec succès.');
    }
    
    /**
     * Affiche les statistiques des tickets de support
     */
    public function stats()
    {
        $totalTickets = SupportTicket::count();
        $openTickets = SupportTicket::whereIn('status', [
            SupportTicket::STATUS_OPEN,
            SupportTicket::STATUS_IN_PROGRESS,
            SupportTicket::STATUS_WAITING_USER,
        ])->count();
        $closedTickets = SupportTicket::where('status', SupportTicket::STATUS_CLOSED)->count();
        
        $ticketsByCategory = SupportTicket::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category => $item->count];
            });
        
        $ticketsByStatus = SupportTicket::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });
        
        $ticketsByPriority = SupportTicket::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->priority => $item->count];
            });
        
        $recentTickets = SupportTicket::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.support.stats', compact(
            'totalTickets',
            'openTickets',
            'closedTickets',
            'ticketsByCategory',
            'ticketsByStatus',
            'ticketsByPriority',
            'recentTickets'
        ));
    }
    
    /**
     * Supprime un ticket de support
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Supprimer les messages associés
        $ticket->messages()->delete();
        
        // Supprimer le ticket
        $ticket->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Le ticket a été supprimé avec succès.'
            ]);
        }
        
        return redirect()->route('admin.support.index')
            ->with('success', 'Le ticket a été supprimé avec succès.');
    }
}
