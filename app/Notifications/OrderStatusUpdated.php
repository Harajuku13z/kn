<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var \App\Models\Order
     */
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
        $statusText = match($this->order->status) {
            'pending' => 'en attente',
            'collected' => 'collecté',
            'washing' => 'en lavage',
            'ironing' => 'en repassage',
            'ready_for_delivery' => 'prêt pour livraison',
            'delivering' => 'en cours de livraison',
            'delivered' => 'livré',
            'cancelled' => 'annulé',
            default => $this->order->status
        };
        
        $paymentStatusText = match($this->order->payment_status) {
            'pending' => 'en attente',
            'paid' => 'payée',
            'failed' => 'échouée',
            'refunded' => 'remboursée',
            default => $this->order->payment_status
        };

        return (new MailMessage)
            ->subject('Mise à jour de votre commande #' . $this->order->id . ' - KLINKLIN')
            ->view('emails.order-status-updated', [
                'notifiable' => $notifiable,
                'order' => $this->order,
                'statusText' => $statusText,
                'paymentStatusText' => $paymentStatusText
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
            'title' => 'Mise à jour de commande',
            'order_id' => $this->order->id,
            'message' => 'Le statut de votre commande #' . $this->order->id . ' a été mis à jour.',
            'details' => 'Nouveau statut: ' . ucfirst($this->order->status) . ' | Paiement: ' . ucfirst($this->order->payment_status),
            'icon' => 'bi-clipboard-check-fill',
            'color' => 'info',
            'type' => 'order_status_update'
        ];
    }
}
