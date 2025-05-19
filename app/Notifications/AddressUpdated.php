<?php

namespace App\Notifications;

use App\Models\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddressUpdated extends Notification
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
            ->subject('Adresse mise à jour - KLINKLIN')
            ->view('emails.address-updated', [
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
            'title' => 'Adresse mise à jour',
            'message' => 'Adresse mise à jour: ' . $this->address->name,
            'address_id' => $this->address->id,
            'address_name' => $this->address->name,
            'address_type' => $this->address->type,
            'icon' => 'fa-edit',
            'color' => 'info'
        ];
    }
}
