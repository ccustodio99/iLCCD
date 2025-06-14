<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('color_primary', '#1B2660');
        Setting::set('color_accent', '#FFCD38');
        Setting::set('font_primary', 'Poppins');
        Setting::set('font_secondary', 'Roboto');
        Setting::set('home_heading', 'Welcome to the LCCD Integrated Information System (CMS)');
        Setting::set('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.');
    }
}
