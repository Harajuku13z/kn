<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\SubscriptionType;
use App\Notifications\PaymentConfirmed;
use App\Notifications\SubscriptionCancelled;
use App\Notifications\SubscriptionUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions.
     */
    public function index(Request $request)
    {
        $query = Subscription::with('user');
        
        // Filtrage par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('payment_status', $request->status);
        }
        
        // Filtrage par date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Recherche par texte (ID ou nom d'utilisateur)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.subscriptions.index', [
            'subscriptions' => $subscriptions,
            'pendingCount' => Subscription::where('payment_status', 'pending')->count(),
            'paidCount' => Subscription::where('payment_status', 'paid')->count(),
            'cancelledCount' => Subscription::where('payment_status', 'cancelled')->count()
        ]);
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        $subscriptionTypes = SubscriptionType::where('is_active', true)->get();
        
        return view('admin.subscriptions.create', [
            'users' => $users,
            'subscriptionTypes' => $subscriptionTypes
        ]);
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_type_id' => 'required|exists:subscription_types,id',
            'quota_purchased' => 'required|numeric|min:1',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:card,cash,mobile_money,bank_transfer',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'expiration_date' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:255',
        ]);
        
        if (!empty($validatedData['expiration_date'])) {
            $validatedData['expiration_date'] = Carbon::parse($validatedData['expiration_date']);
        }
        
        $subscription = Subscription::create($validatedData);
        
        if ($validatedData['payment_status'] === 'paid') {
            // Notifier l'utilisateur que son paiement est confirmé
            $user = User::find($validatedData['user_id']);
            $user->notify(new PaymentConfirmed($subscription));
        }
        
        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Abonnement créé avec succès!');
    }

    /**
     * Show the details of a subscription.
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'quotaUsages.order']);
        
        return view('admin.subscriptions.show', [
            'subscription' => $subscription,
            'quotaUsages' => $subscription->quotaUsages()->paginate(10)
        ]);
    }

    /**
     * Show the form for editing a subscription.
     */
    public function edit(Subscription $subscription)
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        $subscriptionTypes = SubscriptionType::where('is_active', true)->get();
        
        return view('admin.subscriptions.edit', [
            'subscription' => $subscription,
            'users' => $users,
            'subscriptionTypes' => $subscriptionTypes
        ]);
    }

    /**
     * Update a subscription in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_type_id' => 'required|exists:subscription_types,id',
            'quota_purchased' => 'required|numeric|min:' . $subscription->quotaUsages()->sum('amount_used'),
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:card,cash,mobile_money,bank_transfer',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'expiration_date' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:255',
        ]);
        
        if (!empty($validatedData['expiration_date'])) {
            $validatedData['expiration_date'] = Carbon::parse($validatedData['expiration_date']);
        }
        
        $oldStatus = $subscription->payment_status;
        $newStatus = $validatedData['payment_status'];
        
        $subscription->update($validatedData);
        
        // Notifications en fonction des changements de statut
        if ($oldStatus !== $newStatus) {
            $user = $subscription->user;
            
            if ($newStatus === 'paid' && $oldStatus === 'pending') {
                $user->notify(new PaymentConfirmed($subscription));
            } elseif ($newStatus === 'cancelled' && ($oldStatus === 'pending' || $oldStatus === 'paid')) {
                $user->notify(new SubscriptionCancelled($subscription));
            } else {
                $user->notify(new SubscriptionUpdated($subscription));
            }
        }
        
        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Abonnement mis à jour avec succès!');
    }
    
    /**
     * Confirm payment for a subscription.
     */
    public function confirmPayment(Subscription $subscription)
    {
        if ($subscription->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être confirmé.');
        }
        
        $subscription->payment_status = 'paid';
        $subscription->save();
        
        // Notify the user
        $subscription->user->notify(new PaymentConfirmed($subscription));
        
        return redirect()->back()->with('success', 'Paiement confirmé avec succès.');
    }

    /**
     * Cancel a subscription.
     */
    public function cancel(Subscription $subscription)
    {
        if ($subscription->payment_status === 'cancelled') {
            return redirect()->back()->with('error', 'Cet abonnement est déjà annulé.');
        }
        
        $oldStatus = $subscription->payment_status;
        $subscription->payment_status = 'cancelled';
        $subscription->save();
        
        // Notify the user
        $subscription->user->notify(new SubscriptionCancelled($subscription));
        
        return redirect()->back()->with('success', 'Abonnement annulé avec succès.');
    }

    /**
     * Remove a subscription (soft delete).
     */
    public function destroy(Subscription $subscription)
    {
        // Vérifier si l'abonnement a été utilisé
        $usedQuota = $subscription->quotaUsages()->sum('amount_used');
        
        if ($usedQuota > 0) {
            return redirect()->back()->with('error', 'Cet abonnement ne peut pas être supprimé car il a déjà été utilisé.');
        }
        
        $subscription->delete();
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Abonnement supprimé avec succès.');
    }
}
