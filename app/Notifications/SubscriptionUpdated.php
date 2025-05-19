<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionUpdated extends Notification implements ShouldQueue
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
            ->subject('Abonnement mis à jour - KLINKLIN')
            ->view('emails.subscriptions.subscription-updated', [
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
            'title' => 'Abonnement mis à jour',
            'message' => 'Votre abonnement a été mis à jour',
            'subscription_id' => $this->subscription->id,
            'amount' => $this->subscription->amount_paid,
            'icon' => 'fa-sync',
            'color' => 'info'
        ];
    }

    /**
     * Get a human-readable status text.
     *
     * @param string $status
     * @return string
     */
    private function getStatusText(string $status): string
    {
        $statusMap = [
            'pending' => 'En attente de paiement',
            'paid' => 'Payé',
            'cancelled' => 'Annulé'
        ];

        return $statusMap[$status] ?? $status;
    }
}
