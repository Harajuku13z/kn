<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use App\Models\SubscriptionType;
use App\Notifications\SubscriptionPurchased;
use App\Notifications\PaymentConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of all subscriptions for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $activeSubscriptions = $user->activeSubscriptions()
            ->with('subscriptionType')
            ->orderBy('created_at', 'desc')
            ->get();
        $expiredSubscriptions = $user->subscriptions()
            ->with('subscriptionType')
            ->where(function($query) {
                $query->where('payment_status', '!=', 'paid')
                    ->orWhere(function($q) {
                        $q->whereNotNull('expiration_date')->where('expiration_date', '<=', now());
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Si c'est une requête AJAX, retourner seulement la vue partielle
        if ($request->ajax() || $request->wantsJson()) {
            return view('subscriptions.index', [
                'activeSubscriptions' => $activeSubscriptions,
                'expiredSubscriptions' => $expiredSubscriptions,
                'totalQuota' => $user->getTotalAvailableQuota(),
                'paidQuota' => $user->getPaidSubscriptionQuota(),
                'pendingQuota' => $user->getPendingSubscriptionQuota()
            ]);
        }
        
        return view('subscriptions.index', [
            'activeSubscriptions' => $activeSubscriptions,
            'expiredSubscriptions' => $expiredSubscriptions,
            'totalQuota' => $user->getTotalAvailableQuota(),
            'paidQuota' => $user->getPaidSubscriptionQuota(),
            'pendingQuota' => $user->getPendingSubscriptionQuota()
        ]);
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create()
    {
        return view('subscriptions.create', [
            'subscriptionTypes' => SubscriptionType::all(),
            'totalQuota' => Auth::user()->getTotalAvailableQuota()
        ]);
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subscription_type_id' => 'required|exists:subscription_types,id',
            'payment_method' => 'required|string|in:mobile_money,cash,bank_transfer',
        ]);

        $user = Auth::user();
        $subscriptionType = SubscriptionType::findOrFail($request->subscription_type_id);
        $paymentMethod = $request->input('payment_method');
        
        // Calculate expiration date based on subscription type duration
        $expirationDate = now()->addDays($subscriptionType->duration);
        
        // Create subscription record
        $subscription = new Subscription([
            'user_id' => $user->id,
            'subscription_type_id' => $subscriptionType->id,
            'purchase_date' => now(),
            'expiration_date' => $expirationDate,
            'quota_purchased' => $subscriptionType->quota,
            'amount_paid' => $subscriptionType->price,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentMethod === 'cash' ? 'pending' : 'paid',
            'notes' => 'Abonnement ' . $subscriptionType->name
        ]);
        
        $subscription->save();
        
        // Send notification
        $user->notify(new SubscriptionPurchased($subscription));
        
        if ($paymentMethod === 'cash') {
            return redirect()->route('subscriptions.index')
                ->with('success', 'Votre abonnement a été créé avec succès. Le paiement sera confirmé lors de la collecte/livraison.');
        }
        
        return redirect()->route('subscriptions.index')
            ->with('success', 'Votre achat de quota a été effectué avec succès!');
    }

    /**
     * Display the specified subscription.
     */
    public function show(Subscription $subscription)
    {
        $this->authorizeSubscription($subscription);
        
        $quotaUsages = $subscription->quotaUsages()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('subscriptions.show', [
            'subscription' => $subscription,
            'quotaUsages' => $quotaUsages
        ]);
    }

    /**
     * Show payment success page
     */
    public function paymentSuccess(Subscription $subscription)
    {
        $this->authorizeSubscription($subscription);
        
        return view('subscriptions.payment-success', [
            'subscription' => $subscription
        ]);
    }

    /**
     * Confirm payment for a subscription (admin function)
     */
    public function confirmPayment(Subscription $subscription)
    {
        // This would typically be protected by an admin middleware
        // For now, we'll just check if the user is the owner of the subscription
        $this->authorizeSubscription($subscription);
        
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
     * Cancel a subscription (set status to cancelled)
     */
    public function cancel(Subscription $subscription)
    {
        $this->authorizeSubscription($subscription);
        
        if ($subscription->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Cet abonnement ne peut pas être annulé.');
        }
        
        $subscription->payment_status = 'cancelled';
        $subscription->save();
        
        return redirect()->back()->with('success', 'Abonnement annulé avec succès.');
    }

    /**
     * Update the specified subscription.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $this->authorizeSubscription($subscription);
        
        // Vérifier que l'abonnement est en statut "pending"
        if ($subscription->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Seuls les abonnements en attente peuvent être modifiés.');
        }
        
        $request->validate([
            'notes' => 'required|string|max:255',
            'payment_method' => 'required|string|in:cash,mobile_money,bank_transfer',
        ]);
        
        $subscription->notes = $request->input('notes');
        $subscription->payment_method = $request->input('payment_method');
        $subscription->save();
        
        return redirect()->route('subscriptions.show', $subscription)
            ->with('success', 'Abonnement modifié avec succès.');
    }
    
    /**
     * Authorize access to a subscription
     */
    private function authorizeSubscription(Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cet abonnement.');
        }
    }

    /**
     * Affiche le formulaire de paiement pour un abonnement.
     */
    public function payementForm(SubscriptionType $subscriptionType)
    {
        $user = Auth::user();
        return view('subscriptions.payement', [
            'subscriptionType' => $subscriptionType,
            'user' => $user
        ]);
    }

    /**
     * Traite la soumission du formulaire de paiement (simulation).
     */
    public function payementStore(Request $request)
    {
        $request->validate([
            'subscription_type_id' => 'required|exists:subscription_types,id',
            'payment_method' => 'required|string|in:mobile_money,cash,bank_transfer',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $user = Auth::user();
        $subscriptionType = SubscriptionType::findOrFail($request->subscription_type_id);
        $paymentMethod = $request->input('payment_method');
        $expirationDate = now()->addDays($subscriptionType->duration);

        $subscription = new Subscription([
            'user_id' => $user->id,
            'subscription_type_id' => $subscriptionType->id,
            'purchase_date' => now(),
            'expiration_date' => $expirationDate,
            'quota_purchased' => $subscriptionType->quota,
            'amount_paid' => $subscriptionType->price,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentMethod === 'cash' ? 'pending' : 'paid',
            'notes' => 'Abonnement ' . $subscriptionType->name
        ]);
        $subscription->save();

        $user->notify(new SubscriptionPurchased($subscription));

        return redirect()->route('subscriptions.index')
            ->with('success', 'Votre abonnement a été créé avec succès. Paiement simulé.');
    }
}
