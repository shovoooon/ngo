<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Get all settings as a key-value associative array.
     */
    public function all(): array
    {
        return Cache::rememberForever('app_settings', function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get a specific setting by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();
        
        return $settings[$key] ?? $default;
    }

    /**
     * Update a specific setting by key.
     */
    public function set(string $key, mixed $value): void
    {
        $setting = Setting::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            Setting::create([
                'key' => $key,
                'value' => $value,
                'type' => is_bool($value) ? 'boolean' : 'string',
                'group' => 'general',
            ]);
        }
        
        $this->clearCache();
    }

    /**
     * Clear the settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget('app_settings');
    }
}
