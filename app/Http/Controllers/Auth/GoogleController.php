<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();

        // Find existing user by email (if they registered normally) or create a new one
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            // New user coming from Google – automatically verify email and set as Operator (role_id = 1)
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(uniqid()), // random password, not used for Google login
                'email_verified_at' => now(),
                'role_id' => 1,           // default Operator role
                'is_active' => true,      // ensure the user is active
            ]);
        } else {
            // Existing user logging in with Google
            $updates = [];

            if (empty($user->google_id)) {
                $updates['google_id'] = $googleUser->getId();
            }

            // Auto-verify email if not yet verified
            if (! $user->hasVerifiedEmail()) {
                $updates['email_verified_at'] = now();
            }

            // Ensure Operator role by default if no role has been assigned
            if (empty($user->role_id)) {
                $updates['role_id'] = 1;
            }

            if (! $user->is_active) {
                $updates['is_active'] = true;
            }

            if (! empty($updates)) {
                $user->fill($updates);
                $user->save();
            }
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('operator.create'));
    }
}
