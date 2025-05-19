<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EmailChangeRequested extends Notification implements ShouldQueue
{
    use Queueable;

    private $oldEmail;
    private $newEmail;
    private $verificationUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $oldEmail, string $newEmail, string $verificationUrl)
    {
        $this->oldEmail = $oldEmail;
        $this->newEmail = $newEmail;
        $this->verificationUrl = $verificationUrl;
        Log::info('EmailChangeRequested notification created', ['old' => $oldEmail, 'new' => $newEmail]);
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
            ->subject('Confirmation de modification d\'adresse email - KLINKLIN')
            ->view('emails.email-change-verification', [
                'user' => $notifiable, 
                'oldEmail' => $this->oldEmail,
                'verificationUrl' => $this->verificationUrl
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        Log::info('EmailChangeRequested toArray called');
        
        // Données pour la notification
        $data = [
            'title' => 'Modification d\'adresse email',
            'message' => 'Vous avez demandé à changer votre adresse email de ' . $this->oldEmail . ' à ' . $this->newEmail,
            'icon' => 'bi-envelope-fill',
            'color' => 'primary',
            'type' => 'email_change',
            'action_url' => $this->verificationUrl,
            'action_text' => 'Vérifier l\'email'
        ];
        
        Log::info('EmailChangeRequested toArray returning', $data);
        
        return $data;
    }
} 