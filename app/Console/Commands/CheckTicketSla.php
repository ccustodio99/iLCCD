<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class CheckTicketSla extends Command
{
    protected $signature = 'tickets:check-sla';

    protected $description = 'Escalate tickets that exceed SLA deadlines';

    public function handle(): int
    {
        $affected = Ticket::where('status', 'open')
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->update(['status' => 'escalated']);

        $this->info("Escalated {$affected} tickets");
        return Command::SUCCESS;
    }
}
