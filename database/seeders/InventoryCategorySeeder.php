<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use Illuminate\Database\Seeder;

class InventoryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Electronics
            'Electronics',
            'Computers & Laptops',
            'Tablets & Chromebooks',
            'Smartphones & Mobile Devices',
            'Networking Gear (Routers, Switches)',
            'AV Equipment (Projectors, Microphones)',

            // Furniture & Fixtures
            'Furniture & Fixtures',
            'Desks & Chairs',
            'Cabinets & Shelving',
            'Laboratory Benches',
            'Classroom Fixtures (Podiums, Whiteboards)',

            // Office Supplies
            'Office Supplies',
            'Paper & Stationery',
            'Printing Consumables (Toner, Ink)',
            'Desk Accessories (Pens, Clips)',

            // Laboratory Equipment
            'Laboratory Equipment',
            'Instruments & Sensors',
            'Calibration Tools',
            'Safety Gear (Goggles, Gloves)',

            // Educational Materials
            'Educational Materials',
            'Textbooks & Reference Books',
            'AV Media (DVDs, Projector Slides)',
            'Teaching Aids (Models, Charts)',

            // Maintenance & Cleaning
            'Maintenance & Cleaning',
            'HVAC & Electrical Parts',
            'Plumbing Supplies',
            'Cleaning Chemicals & Tools',

            // Safety & First Aid
            'Safety & First Aid',
            'Fire Extinguishers',
            'First-Aid Kits & Consumables',
            'Emergency Signage',

            // Vehicles & Grounds
            'Vehicles & Grounds',
            'Campus Vehicles',
            'Grounds Equipment (Mowers, Trimmers)',
            'Outdoor Furniture',

            // Consumables & Perishables
            'Consumables & Perishables',
            'Lab Reagents & Chemicals',
            'Printer Paper Rolls',
            'Batteries & Bulbs',

            // Miscellaneous
            'Miscellaneous',
        ];
        foreach ($categories as $name) {
            InventoryCategory::create([
                'name' => $name,
                'is_active' => true,
            ]);
        }
    }
}
