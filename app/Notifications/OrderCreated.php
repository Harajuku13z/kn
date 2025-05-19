<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreated extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
            ->subject('Confirmation de commande #' . $this->order->id . ' - KLINKLIN')
            ->view('emails.order-created', [
                'notifiable' => $notifiable,
                'order' => $this->order
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
            'title' => 'Nouvelle commande',
            'order_id' => $this->order->id,
            'message' => 'Votre commande #' . $this->order->id . ' a été créée avec succès.',
            'details' => 'Collecte le ' . $this->order->pickup_date->format('d/m/Y') . ' - Livraison le ' . $this->order->delivery_date->format('d/m/Y'),
            'icon' => 'bi-bag-check-fill',
            'color' => 'success',
            'type' => 'success'
        ];
    }
} 