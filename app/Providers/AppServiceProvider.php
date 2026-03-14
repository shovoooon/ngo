<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Vite::prefetch(concurrency: 3);

        try {
            if (Schema::hasTable('settings')) {
                $timezone = setting('timezone', config('app.timezone'));
                Config::set('app.timezone', $timezone);
                date_default_timezone_set($timezone);

                $locale = setting('language', config('app.locale'));
                App::setLocale($locale);
            }
        } catch (\Exception $e) {
            // Ignore during migrations or when DB is not ready
        }
    }
}
