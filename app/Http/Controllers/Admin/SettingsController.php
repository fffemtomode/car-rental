<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = [
            'telegram_link' => Setting::get('telegram_link', 'https://t.me/your_username'),
            'viber_number' => Setting::get('viber_number', '+380000000000'),
            'contact_phone' => Setting::get('contact_phone', '+380 00 000 00 00'),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'telegram_link' => 'nullable|string|max:255',
            'viber_number' => 'nullable|string|max:50',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Контакти оновлено.');
    }
}
