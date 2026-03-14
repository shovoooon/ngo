<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'company_name', 'value' => 'My Microfinance NGO', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_email', 'value' => 'info@example.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_phone', 'value' => '+8801700000000', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_address', 'value' => 'Dhaka, Bangladesh', 'type' => 'text', 'group' => 'general'],
            
            // Localization Settings
            ['key' => 'currency_symbol', 'value' => '৳', 'type' => 'string', 'group' => 'localization'],
            ['key' => 'currency_code', 'value' => 'BDT', 'type' => 'string', 'group' => 'localization'],
            ['key' => 'language', 'value' => 'en', 'type' => 'string', 'group' => 'localization'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'type' => 'string', 'group' => 'localization'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'type' => 'string', 'group' => 'localization'],
            
            // System Settings
            ['key' => 'allow_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'system'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
