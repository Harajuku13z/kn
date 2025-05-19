<?php

namespace App\Notifications;

use App\Models\LoginHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $loginHistory;

    /**
     * Create a new notification instance.
     */
    public function __construct(LoginHistory $loginHistory)
    {
        $this->loginHistory = $loginHistory;
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
            ->subject('Nouvelle connexion détectée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle connexion a été détectée sur votre compte KLINKLIN.')
            ->line('Détails de la connexion :')
            ->line('- Date : ' . $this->loginHistory->login_at->format('d/m/Y à H:i'))
            ->line('- Appareil : ' . $this->loginHistory->device_type)
            ->line('- Adresse IP : ' . $this->loginHistory->ip_address)
            ->line('Si vous ne reconnaissez pas cette connexion, nous vous recommandons de sécuriser votre compte immédiatement.')
            ->action('Vérifier mon compte', url('/profile'))
            ->line('Merci d\'utiliser nos services !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nouvelle connexion',
            'message' => 'Nouvelle connexion détectée',
            'login_history_id' => $this->loginHistory->id,
            'login_at' => $this->loginHistory->login_at,
            'device_type' => $this->loginHistory->device_type,
            'ip_address' => $this->loginHistory->ip_address,
            'icon' => 'bi-shield-lock-fill',
            'type' => 'security',
            'color' => 'info'
        ];
    }
}