<?php

namespace Database\Factories;

use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketCategory>
 */
class TicketCategoryFactory extends Factory
{
    protected $model = TicketCategory::class;

    public function definition(): array
    {
        $categories = [
            'Computers & Devices',
            'Software & Apps',
            'Network & Access',
            'User Accounts & Access',
            'Printing & Scanning',
            'Procurement & Inventory',
            'Facilities & Maintenance',
            'Security & Safety',
            'Training & Support',
            'Feedback & Improvement',
            'Other / General Inquiry',
        ];

        return [
            'parent_id' => null,
            'name' => $this->faker->unique()->randomElement($categories),
            'is_active' => true,
        ];
    }
}
