<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(public string $itemName, public int $quantity)
    {
    }

    public function via(object $notifiable): array
    {
        return setting('notify_low_stock', true) ? ['mail'] : [];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $template = setting('template_low_stock', '{{ message }}');
        $message = "Inventory item {$this->itemName} is low on stock. Remaining quantity: {$this->quantity}";
        $content = str_replace('{{ message }}', $message, $template);

        return (new MailMessage)
            ->markdown('mail::message', ['slot' => $content]);
    }
}
