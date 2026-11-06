<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Role;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function callback(Request $request): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        // 1. Validate Google's email verification.
        if (
            ! ($googleUser->user['verified_email'] ?? false) ||
            ! $googleUser->getEmail()
        ) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Email Google belum terverifikasi.',
                ]);
        }

        // 2. Check whether this Google account is already connected.
        $socialAccount = SocialAccount::query()
            ->where('provider', 'google')
            ->where('provider_user_id', $googleUser->getId())
            ->with('user.role')
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;

            // 3. Validate account status and role.
            if (
                ! $user ||
                ! $user->is_active ||
                $user->role?->name !== 'Customer'
            ) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'Akun tidak dapat digunakan untuk login.',
                    ]);
            }

            // 4. Login existing Google account.
            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->route('customer.dashboard');
        }

        // 5. Prevent automatic linking with an existing local account.
        $existingUser = User::query()
            ->where('email', $googleUser->getEmail())
            ->first();

        if ($existingUser) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Email sudah terdaftar. Silakan login menggunakan akun yang sudah ada.',
                ]);
        }

        // 6. Get Customer role.
        $customerRole = Role::query()
            ->where('name', 'Customer')
            ->firstOrFail();

        // 7. Avoid duplicate customer email with guest booking records.
        $customerEmail = Customer::query()
            ->where('email', $googleUser->getEmail())
            ->exists()
            ? null
            : $googleUser->getEmail();

        // 8. Create User, SocialAccount, and Customer atomically.
        $user = DB::transaction(function () use (
            $googleUser,
            $customerRole,
            $customerEmail
        ) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'email_verified_at' => now(),
                'phone' => null,
                'password' => null,
                'role_id' => $customerRole->id,
            ]);

            SocialAccount::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_user_id' => $googleUser->getId(),
            ]);

            Customer::create([
                'name' => $googleUser->getName(),
                'email' => $customerEmail,
                'phone' => null,
                'user_id' => $user->id,
            ]);

            return $user;
        });

        // 9. Login newly registered user.
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('customer.dashboard');
    }
}
