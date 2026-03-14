<?php

use App\Services\SettingService;

if (! function_exists('setting')) {
    /**
     * Get a setting value by its key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null)
    {
        return app(SettingService::class)->get($key, $default);
    }
}
