<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->notifications();
        
        // Filter by read status
        if ($request->has('status') && $request->status != 'all') {
            if ($request->status == 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status == 'unread') {
                $query->whereNull('read_at');
            }
        }
        
        // Toujours trier par ordre chronologique inverse (plus récentes d'abord)
        $query->orderBy('created_at', 'desc');
        
        $notifications = $query->paginate(10);
        
        // Correction pour les liens de pagination
        $notifications->appends($request->except('page'));
        
        // Si c'est une requête AJAX, retourner la vue complète
        if ($request->ajax() || $request->wantsJson()) {
            return view('notifications.index', [
                'notifications' => $notifications
            ]);
        }
        
        return view('notifications.index', [
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Mark a notification as read and redirect to relevant page.
     * 
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function read($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
        
            $returnUrl = $notification->data['action_url'] ?? route('notifications.index');
            
            return redirect($returnUrl)->with('success', 'Notification marquée comme lue');
        } catch (\Exception $e) {
            \Log::error('Erreur lors du marquage de la notification: ' . $e->getMessage());
            return redirect()->route('notifications.index')->with('error', 'Erreur lors du marquage de la notification');
        }
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllRead(Request $request)
    {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Toutes les notifications ont été marquées comme lues',
                    'unread_count' => 0,
                    'read_count' => auth()->user()->notifications()->count()
                ]);
            }
            
            return redirect()->route('notifications.index')
                ->with('success', 'Toutes les notifications ont été marquées comme lues');
        } catch (\Exception $e) {
            \Log::error('Erreur lors du marquage des notifications: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du marquage des notifications'
                ], 500);
            }
            
            return redirect()->route('notifications.index')
                ->with('error', 'Erreur lors du marquage des notifications');
        }
    }
    
    /**
     * Mark a notification as read via AJAX.
     */
    public function readAjax($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            
            $unreadCount = auth()->user()->unreadNotifications()->count();
            $readCount = auth()->user()->readNotifications()->count();
            
            return response()->json([
                'success' => true, 
                'message' => 'Notification marquée comme lue',
                'unread_count' => $unreadCount,
                'read_count' => $readCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du marquage AJAX de la notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage de la notification'
            ], 500);
        }
    }
    
    /**
     * Get the latest notifications for the authenticated user via AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestAjax(Request $request)
    {
        $user = Auth::user();
        $limit = $request->input('limit', 5);
        
        // Récupérer les notifications non lues
        $notifications = $user->unreadNotifications()
                              ->orderBy('created_at', 'desc')
                              ->limit($limit)
                              ->get();
        
        $formattedNotifications = [];
        
        foreach ($notifications as $notification) {
            $data = $notification->data;
            
            // Déterminer le type de notification
            $type = $this->determineNotificationType($data);
            
            // Déterminer l'URL d'action
            $actionUrl = $data['action_url'] ?? null;
            
            // Si pas d'URL d'action, créer une URL en fonction du type
            if (!$actionUrl || $actionUrl === '#') {
                if ($type === 'address') {
                    $actionUrl = route('addresses.index');
                } elseif ($type === 'order' && isset($data['order_id'])) {
                    $actionUrl = route('orders.show', $data['order_id']);
                } elseif ($type === 'order') {
                    $actionUrl = route('orders.index');
                } elseif ($type === 'profile') {
                    $actionUrl = route('profile.index');
                } elseif ($type === 'subscription') {
                    $actionUrl = route('subscriptions.index');
                } else {
                    $actionUrl = route('notifications.read', $notification->id);
                }
            }
            
            $formattedNotifications[] = [
                'id' => $notification->id,
                'type' => $type,
                'title' => $data['title'] ?? 'Notification',
                'message' => $data['message'] ?? 'Vous avez une nouvelle notification.',
                'icon' => $data['icon'] ?? null,
                'action_url' => $actionUrl,
                'action_text' => $data['action_text'] ?? 'Voir détails',
                'created_at' => $notification->created_at->diffForHumans(),
                'created_at_timestamp' => $notification->created_at->timestamp
            ];
        }
        
        return response()->json([
            'success' => true,
            'notifications' => $formattedNotifications,
            'unread_count' => $user->unreadNotifications->count()
        ]);
    }
    
    /**
     * Determine the notification type based on its data.
     *
     * @param array $data The notification data
     * @return string The notification type
     */
    private function determineNotificationType($data)
    {
        // Si le type est explicitement défini dans les données
        if (isset($data['type'])) {
            return $data['type'];
        }
        
        // Sinon déterminer le type à partir des champs disponibles
        if (isset($data['order_id'])) {
            return 'order';
        } elseif (isset($data['address_id']) || isset($data['address_name'])) {
            return 'address';
        } elseif (isset($data['subscription_id'])) {
            return 'subscription';
        } elseif (isset($data['changed_fields'])) {
            return 'profile';
        } elseif (isset($data['weight_used']) || isset($data['remaining_quota'])) {
            return 'quota';
        } elseif (isset($data['payment_id']) || isset($data['payment_status'])) {
            return 'payment';
        } elseif (isset($data['title']) && (
            strpos(strtolower($data['title']), 'profil') !== false ||
            strpos(strtolower($data['title']), 'email') !== false ||
            strpos(strtolower($data['title']), 'mot de passe') !== false ||
            strpos(strtolower($data['title']), 'nom') !== false
        )) {
            return 'profile';
        }
        
        // Type par défaut
        return 'klin-primary';
    }
    
    /**
     * Dismiss a notification via AJAX.
     */
    public function dismissAjax($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification supprimée'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression de la notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la notification'
            ], 500);
        }
    }
}
