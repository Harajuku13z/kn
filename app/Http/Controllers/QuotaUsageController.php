<?php

namespace App\Http\Controllers;

use App\Models\QuotaUsage;
use App\Models\User;
use App\Models\Subscription;
use App\Notifications\QuotaUsed;
use App\Notifications\LowQuotaAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotaUsageController extends Controller
{
    /**
     * Display a listing of quota usages for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $quotaUsages = $user->quotaUsages()->with(['subscription', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('quota-usages.index', [
            'quotaUsages' => $quotaUsages,
            'totalQuota' => $user->getTotalAvailableQuota()
        ]);
    }

    /**
     * Record a new quota usage.
     * This would typically be called from the OrderController when an order is processed.
     */
    public function recordUsage(User $user, float $weight, $orderId = null, $description = null)
    {
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            // Find active subscriptions with remaining quota
            $subscriptions = $user->activeSubscriptions()->get();
            $remainingWeight = $weight;
            
            foreach ($subscriptions as $subscription) {
                if ($remainingWeight <= 0) break;
                
                $availableInSubscription = $subscription->remaining_quota;
                
                if ($availableInSubscription <= 0) continue;
                
                $usedFromThisSubscription = min($availableInSubscription, $remainingWeight);
                
                // Create quota usage record
                QuotaUsage::create([
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                    'order_id' => $orderId,
                    'usage_date' => now(),
                    'amount_used' => $usedFromThisSubscription,
                    'description' => $description
                ]);
                
                $remainingWeight -= $usedFromThisSubscription;
            }
            
            // If there's still weight to account for and there are old-style quotas
            if ($remainingWeight > 0 && $user->availableQuota() > 0) {
                $oldStyleQuotas = $user->activeQuotas()->get();
                
                foreach ($oldStyleQuotas as $quota) {
                    if ($remainingWeight <= 0) break;
                    
                    $availableInQuota = $quota->total_kg - $quota->used_kg;
                    
                    if ($availableInQuota <= 0) continue;
                    
                    $usedFromThisQuota = min($availableInQuota, $remainingWeight);
                    
                    // Update legacy quota
                    $quota->used_kg += $usedFromThisQuota;
                    $quota->save();
                    
                    // Also create a QuotaUsage record for legacy quota usage
                    QuotaUsage::create([
                        'user_id' => $user->id,
                        'subscription_id' => null,
                        'order_id' => $orderId,
                        'usage_date' => now(),
                        'amount_used' => $usedFromThisQuota,
                        'description' => $description ? $description . ' (quota legacy)' : 'Utilisation quota legacy'
                    ]);
                    
                    $remainingWeight -= $usedFromThisQuota;
                }
            }
            
            // If we still have weight remaining, throw an exception
            if ($remainingWeight > 0) {
                throw new \Exception('Quota insuffisant pour cette commande.');
            }
            
            // Commit the transaction
            DB::commit();
            
            // Check if user now has low quota and notify if needed
            if ($user->hasLowQuota()) {
                $user->notify(new LowQuotaAlert($user->getTotalAvailableQuota()));
            }
            
            // Notify user of quota used
            $user->notify(new QuotaUsed($weight, $user->getTotalAvailableQuota()));
            
            return true;
        } catch (\Exception $e) {
            // Rollback the transaction if something fails
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Show the quota usage details.
     */
    public function show(QuotaUsage $quotaUsage)
    {
        if ($quotaUsage->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette utilisation de quota.');
        }
        
        return view('quota-usages.show', [
            'quotaUsage' => $quotaUsage
        ]);
    }
}
