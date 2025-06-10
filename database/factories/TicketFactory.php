<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
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
            'category' => fake()->randomElement(['IT', 'Facilities', 'Documents']),
            'subject' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => 'open',
            'due_at' => now()->addDays(3),
        ];
    }
}
