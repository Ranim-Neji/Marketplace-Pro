<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $oldStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order #{$this->order->order_number} — Status Updated")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your order status has changed from **{$this->oldStatus}** to **{$this->order->status}**.")
            ->action('View Order', route('orders.show', $this->order));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'    => 'Order Status Updated',
            'message'  => "Order #{$this->order->order_number} is now {$this->order->status}.",
            'url'      => route('orders.show', $this->order),
            'type'     => 'order_status',
            'order_id' => $this->order->id,
        ];
    }
}
