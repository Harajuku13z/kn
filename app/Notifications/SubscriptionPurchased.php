<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionPurchased extends Notification
{
    use Queueable;

    protected $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation d\'achat de quota - KLINKLIN')
            ->view('emails.subscriptions.subscription-purchased', [
                'notifiable' => $notifiable,
                'subscription' => $this->subscription
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Achat de quota',
            'message' => 'Nouvel abonnement: ' . $this->subscription->quota_purchased . ' kg achetés',
            'subscription_id' => $this->subscription->id,
            'amount' => $this->subscription->amount_paid,
            'payment_status' => $this->subscription->payment_status,
            'icon' => 'bi-cart-check-fill',
            'color' => 'success',
            'type' => 'success'
        ];
    }
}
