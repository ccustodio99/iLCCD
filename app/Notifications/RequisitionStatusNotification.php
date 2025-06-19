<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use League\CommonMark\CommonMarkConverter;

class RequisitionStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public int $requisitionId, public string $message) {}

    public function via(object $notifiable): array
    {
        return setting('notify_requisition_status', true) ? ['mail'] : [];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $template = setting('template_requisition_status', '{{ message }}');
        $content = str_replace('{{ message }}', $this->message, $template);

        $converter = new CommonMarkConverter;
        $html = $converter->convert($content)->getContent();

        return (new MailMessage)
            ->subject("Requisition #{$this->requisitionId} Update")
            ->view(fn () => new HtmlString($html));
    }
}
