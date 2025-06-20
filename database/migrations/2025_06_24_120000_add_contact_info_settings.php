<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::set('contact_email', null);
        Setting::set('contact_phone', null);
    }

    public function down(): void
    {
        Setting::query()->whereIn('key', ['contact_email', 'contact_phone'])->delete();
    }
};
