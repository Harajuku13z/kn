<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowQuotaAlert extends Notification
{
    use Queueable;

    protected $remainingQuota;

    /**
     * Create a new notification instance.
     */
    public function __construct(float $remainingQuota)
    {
        $this->remainingQuota = $remainingQuota;
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
            ->subject('Alerte: Quota bas - KLINKLIN')
            ->greeting('Bonjour ' . $notifiable->name . '!')
            ->line('Nous vous informons que votre quota de lavage est presque épuisé.')
            ->line('Quota restant: ' . $this->remainingQuota . ' kg')
            ->line('Pour continuer à bénéficier de nos services sans interruption, nous vous recommandons d\'acheter un nouveau forfait.')
            ->action('Acheter du quota', url('/subscriptions/create'))
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
            'title' => 'Alerte de quota bas',
            'message' => 'Alerte: Quota bas (' . $this->remainingQuota . ' kg restants)',
            'remaining_quota' => $this->remainingQuota,
            'icon' => 'bi-exclamation-triangle-fill',
            'color' => 'warning'
        ];
    }
}
