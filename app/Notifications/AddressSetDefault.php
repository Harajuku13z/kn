<?php

namespace App\Notifications;

use App\Models\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddressSetDefault extends Notification
{
    use Queueable;

    protected $address;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(Address $address, string $type = 'pickup')
    {
        $this->address = $address;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Ajout de 'mail' pour envoyer également par email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $typeLabel = $this->type === 'pickup' ? 'collecte' : 'livraison';
        
        return (new MailMessage)
            ->subject("Adresse par défaut de {$typeLabel} modifiée - KLINKLIN")
            ->view('emails.address-default', [
                'notifiable' => $notifiable,
                'address' => $this->address,
                'type' => $this->type
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $typeLabel = $this->type === 'pickup' ? 'collecte' : 'livraison';
        
        return [
            'title' => "Adresse par défaut modifiée",
            'message' => "L'adresse \"{$this->address->name}\" est maintenant votre adresse par défaut pour la {$typeLabel}.",
            'address_id' => $this->address->id,
            'address_name' => $this->address->name,
            'type' => 'address',
            'icon' => 'bi-geo-alt-fill',
            'action_url' => route('addresses.index'),
            'action_text' => 'Voir mes adresses'
        ];
    }
}
