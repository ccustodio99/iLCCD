<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use Illuminate\Database\Seeder;

class InventoryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics' => [
                'Computers & Laptops',
                'Tablets & Chromebooks',
                'Smartphones & Mobile Devices',
                'Networking Gear (Routers, Switches)',
                'AV Equipment (Projectors, Microphones)',
            ],
            'Furniture & Fixtures' => [
                'Desks & Chairs',
                'Cabinets & Shelving',
                'Laboratory Benches',
                'Classroom Fixtures (Podiums, Whiteboards)',
            ],
            'Office Supplies' => [
                'Paper & Stationery',
                'Printing Consumables (Toner, Ink)',
                'Desk Accessories (Pens, Clips)',
            ],
            'Laboratory Equipment' => [
                'Instruments & Sensors',
                'Calibration Tools (links Calibration & Testing)',
                'Safety Gear (Goggles, Gloves)',
            ],
            'Educational Materials' => [
                'Textbooks & Reference Books',
                'AV Media (DVDs, Slides)',
                'Teaching Aids (Models, Charts)',
            ],
            'Maintenance & Cleaning' => [
                'HVAC & Electrical Parts',
                'Plumbing Supplies',
                'Cleaning Chemicals & Tools',
            ],
            'Safety & First Aid' => [
                'Fire Extinguishers (cross-links Safety Audits)',
                'First-Aid Kits',
                'Emergency Signage',
            ],
            'Vehicles & Grounds' => [
                'Campus Vehicles',
                'Grounds Equipment (Mowers, Trimmers)',
                'Outdoor Furniture',
            ],
            'Consumables & Perishables' => [
                'Lab Reagents & Chemicals',
                'Printer Paper Rolls',
                'Batteries & Bulbs',
            ],
            'Miscellaneous' => [],
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = InventoryCategory::create([
                'name' => $parent,
                'is_active' => true,
            ]);

            foreach ($children as $child) {
                InventoryCategory::create([
                    'name' => $child,
                    'parent_id' => $parentCategory->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
