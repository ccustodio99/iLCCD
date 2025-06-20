<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use League\CommonMark\CommonMarkConverter;

class TicketStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $ticketId, public string $message) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (setting('notify_ticket_updates', true)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $template = setting('template_ticket_updates', '{{ message }}');
        $content = str_replace('{{ message }}', $this->message, $template);

        $converter = new CommonMarkConverter;
        $html = $converter->convert($content)->getContent();

        return (new MailMessage)
            ->subject("Ticket #{$this->ticketId} Update")
            ->view(fn () => new HtmlString($html));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticketId,
            'message' => $this->message,
        ];
    }
}
