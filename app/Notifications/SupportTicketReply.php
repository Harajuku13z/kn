<?php

namespace App\Notifications;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class SupportTicketReply extends Notification
{
    use Queueable;

    protected $message;
    protected $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(SupportMessage $message)
    {
        $this->message = $message;
        $this->ticket = $message->ticket;
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
        Log::info('Envoi immédiat d\'email pour le ticket #' . $this->ticket->reference_number . ' à ' . $notifiable->email);
        
        // Extraire un aperçu du message
        $excerpt = strip_tags($this->message->message);
        $excerpt = substr($excerpt, 0, 150) . (strlen($excerpt) > 150 ? '...' : '');
        
        $actionUrl = route('support.show', $this->ticket->id);
        
        return (new MailMessage)
            ->subject('Réponse à votre ticket de support #' . $this->ticket->reference_number)
            ->view('emails.support.ticket-reply', [
                'notifiable' => $notifiable,
                'ticket' => $this->ticket,
                'message_excerpt' => $excerpt,
                'ticket_closed' => $this->ticket->status === 'closed',
                'action_url' => $actionUrl
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $title = 'Réponse à votre ticket de support';
        
        return [
            'title' => $title,
            'ticket_id' => $this->ticket->id,
            'message_id' => $this->message->id,
            'reference_number' => $this->ticket->reference_number,
            'subject' => $this->ticket->subject,
            'message' => $this->ticket->status === 'closed' 
                ? 'Nous avons répondu à votre ticket de support. Le ticket a été fermé.' 
                : 'Nous avons répondu à votre ticket de support.',
            'type' => 'support_ticket_reply',
            'ticket_closed' => $this->ticket->status === 'closed',
            'url' => route('support.show', $this->ticket->id)
        ];
    }
}
