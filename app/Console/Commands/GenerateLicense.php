<?php

namespace App\Console\Commands;

use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateLicense extends Command
{
    protected $signature = 'license:generate {--days=30 : Number of days the license is valid}';

    protected $description = 'Generate a signed license key';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $key = (string) Str::uuid();
        $generated = now();
        $expires = now()->addDays($days);
        $data = $key.'|'.$expires->timestamp;
        $signature = hash_hmac('sha256', $data, config('app.key'));
        $licenseString = base64_encode($data.'|'.$signature);

        License::create([
            'key' => $key,
            'signature' => $signature,
            'expires_at' => $expires,
            'active' => true,
        ]);

        $filename = $generated->format('Ymd').'-'.$expires->format('Ymd').'.lic';
        Storage::disk('local')->put("licenses/{$filename}", $licenseString);

        $this->info('License file created:');
        $this->line(Storage::disk('local')->path("licenses/{$filename}"));

        return self::SUCCESS;
    }
}
