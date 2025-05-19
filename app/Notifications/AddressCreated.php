<?php

namespace App\Notifications;

use App\Models\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddressCreated extends Notification
{
    use Queueable;

    protected $address;

    /**
     * Create a new notification instance.
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
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
            ->subject('Nouvelle adresse ajoutée - KLINKLIN')
            ->view('emails.address-created', [
                'notifiable' => $notifiable,
                'address' => $this->address
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
            'title' => 'Nouvelle adresse',
            'message' => 'Nouvelle adresse ajoutée: ' . $this->address->name,
            'address_id' => $this->address->id,
            'address_name' => $this->address->name,
            'address_type' => $this->address->type,
            'icon' => 'bi-geo-alt-fill',
            'color' => 'primary',
            'type' => 'primary'
        ];
    }
}
