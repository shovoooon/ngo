<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Settings are already shared via HandleInertiaRequests, 
        //, but we can also pass them explicitly if preferred, though Inertia handles them globally.
        return inertia('Settings/Index');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        $settingService = app(\App\Services\SettingService::class);
        
        foreach ($data['settings'] as $key => $value) {
            $settingService->set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
