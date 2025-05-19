<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddressDeleted extends Notification
{
    use Queueable;

    protected $addressName;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $addressName)
    {
        $this->addressName = $addressName;
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
            ->subject('Adresse supprimée - KLINKLIN')
            ->view('emails.address-deleted', [
                'notifiable' => $notifiable,
                'addressName' => $this->addressName
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
            'title' => 'Adresse supprimée',
            'message' => 'Adresse supprimée: ' . $this->addressName,
            'address_name' => $this->addressName,
            'icon' => 'bi-trash-fill',
            'color' => 'danger',
            'type' => 'danger'
        ];
    }
}
