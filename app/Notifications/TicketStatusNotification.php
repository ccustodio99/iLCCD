<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use League\CommonMark\CommonMarkConverter;

class TicketStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public string $message) {}

    public function via(object $notifiable): array
    {
        return setting('notify_ticket_updates', true) ? ['mail'] : [];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $template = setting('template_ticket_updates', '{{ message }}');
        $content = str_replace('{{ message }}', $this->message, $template);

        $converter = new CommonMarkConverter;
        $html = $converter->convert($content)->getContent();

        return (new MailMessage)
            ->view(fn () => new HtmlString($html));
    }
}
