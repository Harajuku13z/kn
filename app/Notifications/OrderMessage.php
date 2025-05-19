<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderMessage extends Notification
{
    use Queueable;

    protected $order;
    protected $subject;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param Order $order
     * @param string $subject
     * @param string|mixed $message
     * @return void
     */
    public function __construct(Order $order, string $subject, $message)
    {
        $this->order = $order;
        $this->subject = $subject;
        
        // Ensure message is always a string to prevent type errors
        if (is_string($message)) {
            $this->message = $message;
        } elseif (is_object($message) && method_exists($message, '__toString')) {
            $this->message = (string) $message;
        } else {
            $this->message = 'Message concernant votre commande #' . $order->id;
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.order-message', [
                'notifiable' => $notifiable,
                'order' => $this->order,
                'subject' => $this->subject,
                'message' => $this->message
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->subject,
            'order_id' => $this->order->id,
            'message' => 'Message concernant votre commande #' . $this->order->id,
            'details' => substr($this->message, 0, 100) . (strlen($this->message) > 100 ? '...' : ''),
            'icon' => 'bi-envelope-fill',
            'color' => 'info',
            'type' => 'order_message'
        ];
    }
} 