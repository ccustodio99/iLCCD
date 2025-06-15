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
        Setting::set('home_heading', 'Welcome to the LCCD Integrated Information System');
        Setting::set('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.');
        Setting::set('header_text', 'La Consolacion College Daet');
        Setting::set('footer_text', 'Empowering Christ-centered digital transformation');
        Setting::set('show_footer', true);
        Setting::set('timezone', 'Asia/Manila');
        Setting::set('date_format', 'Y-m-d');
        Setting::set('notify_ticket_updates', true);
        Setting::set('notify_job_order_status', true);
        Setting::set('notify_requisition_status', true);
        Setting::set('notify_low_stock', true);
        Setting::set('template_ticket_updates', '{{ message }}');
        Setting::set('template_job_order_status', '{{ message }}');
        Setting::set('template_requisition_status', '{{ message }}');
        Setting::set('template_low_stock', '{{ message }}');
    }
}
