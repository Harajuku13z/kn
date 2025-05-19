<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotaUsed extends Notification
{
    use Queueable;

    protected $weightUsed;
    protected $remainingQuota;

    /**
     * Create a new notification instance.
     */
    public function __construct(float $weightUsed, float $remainingQuota)
    {
        $this->weightUsed = $weightUsed;
        $this->remainingQuota = $remainingQuota;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Only send database notification for normal usage
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Utilisation de quota - KLINKLIN')
            ->greeting('Bonjour ' . $notifiable->name . '!')
            ->line('Un quota de ' . $this->weightUsed . ' kg a été utilisé pour votre commande.')
            ->line('Quota restant: ' . $this->remainingQuota . ' kg')
            ->action('Voir l\'historique d\'utilisation', url('/quota-usages'))
            ->line('Merci d\'utiliser les services de KLINKLIN!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Utilisation de quota',
            'message' => $this->weightUsed . ' kg de quota utilisés',
            'weight_used' => $this->weightUsed,
            'remaining_quota' => $this->remainingQuota,
            'icon' => 'bi-box-seam-fill',
            'color' => 'info',
            'type' => 'info'
        ];
    }
}
