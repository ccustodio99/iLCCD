<?php

use App\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Console\Kernel as KernelContract;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        if (setting('sla_enabled', true)) {
            $interval = setting('sla_interval', 1);
            $interval = $interval > 0 ? $interval : 1;
            $schedule->command('tickets:check-sla')->cron("*/{$interval} * * * *");
        }
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => App\Http\Middleware\RoleMiddleware::class,
        ]);

        app()->afterBootstrapping(LoadConfiguration::class, function () use ($middleware) {
            if (config('license.enabled')) {
                $middleware->web(append: App\Http\Middleware\CheckLicense::class);
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->singleton(KernelContract::class, ConsoleKernel::class);

return $app;
