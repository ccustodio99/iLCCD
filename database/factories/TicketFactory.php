<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'assigned_to_id' => null,
            // Use an existing ticket category instead of creating random names
            // to avoid filler words like "deserunt" appearing in the database
            'ticket_category_id' => TicketCategory::inRandomOrder()->value('id')
                ?? TicketCategory::factory()->create()->id,
            'subject' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'attachment_path' => null,
            'status' => 'open',
            'due_at' => now()->addDays(3),
            'escalated_at' => null,
            'resolved_at' => null,
        ];
    }
}
