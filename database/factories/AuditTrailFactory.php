<?php

namespace Database\Factories;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditTrail>
 */
class AuditTrailFactory extends Factory
{
    protected $model = AuditTrail::class;

    public function definition(): array
    {
        return [
            'auditable_id' => 1,
            'auditable_type' => 'App\\Models\\Ticket',
            'user_id' => User::factory(),
            'action' => 'created',
        ];
    }
}
