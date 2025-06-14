<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public string $message)
    {
    }

    public function via(object $notifiable): array
    {
        return setting('notify_requisition_status', true) ? ['mail'] : [];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $template = setting('template_requisition_status', '{{ message }}');
        $content = str_replace('{{ message }}', $this->message, $template);

        return (new MailMessage)
            ->markdown('mail::message', ['slot' => $content]);
    }
}
