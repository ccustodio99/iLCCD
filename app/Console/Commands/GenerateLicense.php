<?php

namespace App\Console\Commands;

use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateLicense extends Command
{
    protected $signature = 'license:generate
        {--days=30 : Number of days the license is valid}
        {--months=0 : Number of months the license is valid}
        {--years=0 : Number of years the license is valid}';

    protected $description = 'Generate a signed license key';

    protected $help = 'Create a license file in storage/app/licenses. You may
specify its validity using --days, --months, or --years.';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $months = (int) $this->option('months');
        $years = (int) $this->option('years');

        if ($days < 0 || $months < 0 || $years < 0 || ($days === 0 && $months === 0 && $years === 0)) {
            $this->error('Specify a positive value for days, months, or years.');

            return self::FAILURE;
        }
        $key = (string) Str::uuid();
        $generated = now();
        $expires = now()->addDays($days)->addMonths($months)->addYears($years);
        $data = $key.'|'.$expires->timestamp;
        $signature = hash_hmac('sha256', $data, config('license.secret'));
        $licenseString = base64_encode($data.'|'.$signature);

        DB::transaction(function () use ($key, $signature, $expires) {
            License::query()->update(['active' => false]);
            License::create([
                'key' => $key,
                'signature' => $signature,
                'expires_at' => $expires,
                'active' => true,
            ]);
        });

        $filename = $generated->format('Ymd').'-'.$expires->format('Ymd').'.lic';
        Storage::disk('local')->put("licenses/{$filename}", $licenseString);

        $this->info('License file created:');
        $this->line(Storage::disk('local')->path("licenses/{$filename}"));
        $this->line($licenseString);

        return self::SUCCESS;
    }
}
