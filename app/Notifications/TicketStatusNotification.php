<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public string $message)
    {
    }

    public function via(object $notifiable): array
    {
        return setting('notify_ticket_updates', true) ? ['mail'] : [];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $template = setting('template_ticket_updates', '{{ message }}');
        $content = str_replace('{{ message }}', $this->message, $template);

        return (new MailMessage)
            ->markdown('mail::message', ['slot' => $content]);
    }
}
