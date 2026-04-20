<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order Confirmed: #{$this->order->order_number}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your order **#{$this->order->order_number}** has been placed successfully.")
            ->line("Total: **\${$this->order->total}**")
            ->action('View Order', route('orders.show', $this->order))
            ->line('Thank you for shopping with us!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'    => 'Order Placed Successfully',
            'message'  => "Your order #{$this->order->order_number} has been confirmed.",
            'url'      => route('orders.show', $this->order),
            'type'     => 'order_placed',
            'order_id' => $this->order->id,
        ];
    }
}
