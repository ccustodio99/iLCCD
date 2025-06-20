<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PDOException;

class MailSettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                Config::set('mail.mailers.smtp.host', Setting::get('mail_host', Config::get('mail.mailers.smtp.host')));
                Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', Config::get('mail.mailers.smtp.port')));
                Config::set('mail.mailers.smtp.username', Setting::get('mail_username', Config::get('mail.mailers.smtp.username')));
                Config::set('mail.mailers.smtp.password', Setting::get('mail_password', Config::get('mail.mailers.smtp.password')));
                Config::set('mail.mailers.smtp.scheme', Setting::get('mail_encryption', Config::get('mail.mailers.smtp.scheme')));
                Config::set('mail.from.address', Setting::get('mail_from_address', Config::get('mail.from.address')));
                Config::set('mail.from.name', Setting::get('mail_from_name', Config::get('mail.from.name')));
            }
        } catch (QueryException|PDOException $e) {
            // Database is unreachable; fall back to config values
        }
    }
}
