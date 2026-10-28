<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Flat;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function complete(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('registration.verified_email');

        if (! $email || ! DB::table('registration_email_verifications')
            ->where('email', $email)
            ->whereNotNull('verified_at')
            ->exists()) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Verify your Gmail address before completing registration.']);
        }

        $flats = Flat::orderBy('block')->orderBy('floor')->orderBy('flat_number')->get();

        return view('auth.register-complete', compact('email', 'flats'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $email = $request->session()->get('registration.verified_email');

        if (! $email) {
            throw ValidationException::withMessages([
                'email' => 'Verify your Gmail address before completing registration.',
            ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'flat_number' => ['required', 'string', 'exists:flats,flat_number'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request, $email): User {
            $verification = DB::table('registration_email_verifications')
                ->where('email', $email)
                ->whereNotNull('verified_at')
                ->lockForUpdate()
                ->first();

            if (! $verification) {
                throw ValidationException::withMessages([
                    'email' => 'Verify your Gmail address before completing registration.',
                ]);
            }

            if (User::where('email', $email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'An account already exists for this Gmail address. Please log in.',
                ]);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'flat_number' => $request->flat_number,
                'password' => Hash::make($request->password),
            ]);

            $user->forceFill(['email_verified_at' => now()])->save();

            DB::table('registration_email_verifications')
                ->where('email', $email)
                ->delete();

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);
        $request->session()->forget([
            'registration.pending_email',
            'registration.verified_email',
        ]);
        $request->session()->regenerate();

        return redirect(route('dashboard', absolute: false));
    }
}
