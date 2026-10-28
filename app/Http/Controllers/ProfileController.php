<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $contactSettings = null;

        if (in_array($request->user()->role, ['manager', 'admin'])) {
            $contactSettings = [
                'telegram_link' => Setting::get('telegram_link', 'https://t.me/your_username'),
                'viber_number' => Setting::get('viber_number', '+380000000000'),
                'contact_phone' => Setting::get('contact_phone', '+380 00 000 00 00'),
            ];
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'contactSettings' => $contactSettings,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['phone'] = $request->input('phone');

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
