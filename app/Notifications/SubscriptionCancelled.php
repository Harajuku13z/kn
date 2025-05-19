<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCancelled extends Notification implements ShouldQueue
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
            ->subject('Abonnement annulé - KLINKLIN')
            ->view('emails.subscriptions.subscription-cancelled', [
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
            'title' => 'Abonnement annulé',
            'message' => 'Votre abonnement de ' . $this->subscription->quota_purchased . ' kg a été annulé',
            'subscription_id' => $this->subscription->id,
            'amount' => $this->subscription->amount_paid,
            'icon' => 'fa-times-circle',
            'color' => 'danger'
        ];
    }
}
