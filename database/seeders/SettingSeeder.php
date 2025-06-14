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
        Setting::set('institution_address', 'Gov. Panotes Avenue, Daet, Camarines Norte 4600');
        Setting::set('institution_phone', '(054) 571-3456');
        Setting::set('helpdesk_email', 'helpdesk@lccd.edu.ph');
        Setting::set('header_text', 'La Consolacion College Daet');
        Setting::set('footer_text', 'Empowering Christ-centered digital transformation');
        Setting::set('timezone', 'Asia/Manila');
        Setting::set('date_format', 'Y-m-d');
    }
}
