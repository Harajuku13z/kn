<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'is_admin',
        'is_delivery',
        'role',
        'avatar_settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_delivery' => 'boolean',
        'avatar_settings' => 'array',
    ];

    /**
     * Check if the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Get the orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the quotas for the user.
     */
    public function quotas(): HasMany
    {
        return $this->hasMany(Quota::class);
    }

    /**
     * Get the subscriptions for the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the quota usages for the user.
     */
    public function quotaUsages(): HasMany
    {
        return $this->hasMany(QuotaUsage::class);
    }

    /**
     * Get the addresses for the user.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the active quotas for the user.
     */
    public function activeQuotas()
    {
        return $this->quotas()->where('is_active', true)->where(function($query) {
            $query->whereNull('expiration_date')->orWhere('expiration_date', '>', now());
        });
    }

    /**
     * Get the active subscriptions for the user (paid or pending).
     */
    public function activeSubscriptions()
    {
        return $this->subscriptions()
            ->where(function($query) {
                $query->where('payment_status', 'paid')
                      ->orWhere('payment_status', 'pending');
            })
            ->where(function($query) {
                $query->whereNull('expiration_date')->orWhere('expiration_date', '>', now());
            });
    }

    /**
     * Get the total available quota for the user.
     */
    public function availableQuota()
    {
        return $this->activeQuotas()->sum(\DB::raw('total_kg - used_kg'));
    }
    
    /**
     * Get the available subscription quota that is already paid.
     */
    public function getPaidSubscriptionQuota()
    {
        $subscriptions = $this->subscriptions()
            ->where('payment_status', 'paid')
            ->where(function($query) {
                $query->whereNull('expiration_date')->orWhere('expiration_date', '>', now());
            })
            ->get();
        
        $total = 0;
        
        foreach ($subscriptions as $subscription) {
            $total += $subscription->remaining_quota;
        }
        
        return $total;
    }
    
    /**
     * Get the available subscription quota that is pending payment.
     */
    public function getPendingSubscriptionQuota()
    {
        $subscriptions = $this->subscriptions()
            ->where('payment_status', 'pending')
            ->where(function($query) {
                $query->whereNull('expiration_date')->orWhere('expiration_date', '>', now());
            })
            ->get();
        
        $total = 0;
        
        foreach ($subscriptions as $subscription) {
            $total += $subscription->remaining_quota;
        }
        
        return $total;
    }
    
    /**
     * Get total available quota (from both legacy quotas and new subscriptions).
     */
    public function getTotalAvailableQuota()
    {
        return $this->availableQuota() + $this->getPaidSubscriptionQuota() + $this->getPendingSubscriptionQuota();
    }
    
    /**
     * Check if user has low quota (less than 2kg).
     */
    public function hasLowQuota()
    {
        return $this->getTotalAvailableQuota() < 2;
    }

    /**
     * Get the login history for the user.
     */
    public function loginHistory(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * Get the support tickets for the user.
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get the avatar URL based on avatar settings.
     */
    public function getAvatarUrlAttribute()
    {
        $settings = $this->avatar_settings ? $this->avatar_settings : [];
        
        if (is_string($settings)) {
            $settings = json_decode($settings, true);
        }
        
        $avatarType = $settings['avatar_type'] ?? null;
        
        if ($avatarType === 'gravatar') {
            $style = $settings['gravatar_style'] ?? 'retro';
            return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=200&d=' . $style;
        } elseif ($avatarType === 'icon') {
            return null; // Icons are rendered client-side with CSS
        } elseif ($avatarType === 'initial') {
            return null; // Initials are rendered client-side
        }
        
        // Fallback to default gravatar
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=200&d=mp';
    }
    
    /**
     * Get recent login history for the user.
     */
    public function getRecentLoginHistory($limit = 5)
    {
        return $this->loginHistory()
            ->orderBy('login_at', 'desc')
            ->take($limit)
            ->get();
    }
}
