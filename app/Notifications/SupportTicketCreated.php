<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(SupportTicket $ticket)
    {
        $this->ticket = $ticket;
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
            ->subject('Votre ticket de support #' . $this->ticket->reference_number . ' a été créé')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Nous avons bien reçu votre demande de support.')
            ->line('Référence du ticket : ' . $this->ticket->reference_number)
            ->line('Sujet : ' . $this->ticket->subject)
            ->line('Catégorie : ' . $this->ticket->getCategoryLabel())
            ->line('Notre équipe va traiter votre demande dans les plus brefs délais.')
            ->action('Voir mon ticket', route('support.show', $this->ticket->id))
            ->line('Merci de votre confiance.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'reference_number' => $this->ticket->reference_number,
            'subject' => $this->ticket->subject,
            'message' => 'Votre ticket de support a été créé avec succès.',
            'type' => 'support_ticket_created'
        ];
    }
}
