<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user === null) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google Resident',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
            ]);

            $user->forceFill(['google_id' => $googleUser->getId()])->save();
        } elseif ($user->google_id !== $googleUser->getId()) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        if ($user->role === 'security') {
            return redirect()->intended(route('security.dashboard', absolute: false));
        }

        if ($user->role === 'resident') {
            return redirect()->intended(route('resident.dashboard', absolute: false));
        }

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (auth()->user()->role === 'security') {
            return redirect()->intended(route('security.dashboard', absolute: false));
        }

        if (auth()->user()->role === 'resident') {
            return redirect()->intended(route('resident.dashboard', absolute: false));
        }

        if (auth()->user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
