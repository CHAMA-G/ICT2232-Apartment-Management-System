<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationVerificationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class RegistrationEmailVerificationController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $email = strtolower(trim((string) $request->input('email')));
        $request->merge(['email' => $email]);

        $request->validate([
            'email' => [
                'required',
                'string',
                'email:rfc',
                'ends_with:@gmail.com',
                'max:255',
                'unique:'.User::class,
            ],
        ], [
            'email.ends_with' => 'Use a Gmail address ending in @gmail.com.',
        ]);

        if (! app()->environment('testing')
            && (config('mail.default') !== 'smtp'
                || ! config('mail.mailers.smtp.username')
                || ! config('mail.mailers.smtp.password'))) {
            throw ValidationException::withMessages([
                'email' => 'Gmail verification email is not configured yet. Please try again later.',
            ]);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $codeHash = Hash::make($code);
        $now = now();

        DB::transaction(function () use ($email, $codeHash, $now): void {
            $verification = DB::table('registration_email_verifications')
                ->where('email', $email)
                ->lockForUpdate()
                ->first();

            if ($verification && Carbon::parse($verification->last_sent_at)->gt($now->copy()->subMinute())) {
                throw ValidationException::withMessages([
                    'email' => 'Please wait one minute before requesting another verification code.',
                ]);
            }

            $windowStartedAt = $verification
                ? Carbon::parse($verification->window_started_at)
                : null;
            $sendCount = $windowStartedAt && $windowStartedAt->gt($now->copy()->subHour())
                ? (int) $verification->send_count
                : 0;

            if ($sendCount >= 5) {
                throw ValidationException::withMessages([
                    'email' => 'Too many verification codes were requested. Try again in an hour.',
                ]);
            }

            DB::table('registration_email_verifications')->updateOrInsert(
                ['email' => $email],
                [
                    'code_hash' => $codeHash,
                    'expires_at' => $now->copy()->addMinutes(10),
                    'last_sent_at' => $now,
                    'window_started_at' => $sendCount === 0 ? $now : $verification->window_started_at,
                    'send_count' => $sendCount + 1,
                    'attempts' => 0,
                    'verified_at' => null,
                    'created_at' => $verification?->created_at ?? $now,
                    'updated_at' => $now,
                ],
            );
        });

        try {
            Mail::to($email)->send(new RegistrationVerificationCode($code));
        } catch (TransportExceptionInterface $exception) {
            DB::table('registration_email_verifications')
                ->where('email', $email)
                ->where('code_hash', $codeHash)
                ->delete();

            throw ValidationException::withMessages([
                'email' => 'The verification email could not be sent. Check the Gmail mail settings and try again.',
            ]);
        }

        $request->session()->put('registration.pending_email', $email);
        $request->session()->forget('registration.verified_email');

        return redirect()->route('register.verify')
            ->with('status', 'A six-digit verification code was sent to your Gmail address.');
    }

    public function show(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('registration.pending_email');

        if (! $email) {
            return redirect()->route('register');
        }

        if (DB::table('registration_email_verifications')
            ->where('email', $email)
            ->whereNotNull('verified_at')
            ->exists()) {
            $request->session()->put('registration.verified_email', $email);

            return redirect()->route('register.complete');
        }

        return view('auth.verify-registration-email', compact('email'));
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $email = $request->session()->get('registration.pending_email');

        if (! $email) {
            return redirect()->route('register');
        }

        $result = DB::transaction(function () use ($email, $request): string {
            $verification = DB::table('registration_email_verifications')
                ->where('email', $email)
                ->lockForUpdate()
                ->first();

            if (! $verification || Carbon::parse($verification->expires_at)->isPast()) {
                return 'expired';
            }

            if ($verification->verified_at) {
                return 'verified';
            }

            if ((int) $verification->attempts >= 5) {
                return 'locked';
            }

            if (! Hash::check((string) $request->input('code'), $verification->code_hash)) {
                DB::table('registration_email_verifications')
                    ->where('email', $email)
                    ->increment('attempts', 1, ['updated_at' => now()]);

                return 'invalid';
            }

            DB::table('registration_email_verifications')
                ->where('email', $email)
                ->update([
                    'verified_at' => now(),
                    'updated_at' => now(),
                ]);

            return 'verified';
        });

        if ($result !== 'verified') {
            $message = match ($result) {
                'expired' => 'That verification code has expired. Request a new code.',
                'locked' => 'Too many incorrect codes were entered. Request a new code.',
                default => 'The verification code is incorrect.',
            };

            throw ValidationException::withMessages(['code' => $message]);
        }

        $request->session()->put('registration.verified_email', $email);
        $request->session()->forget('registration.pending_email');

        return redirect()->route('register.complete')
            ->with('status', 'Your Gmail address has been verified. Complete your registration.');
    }
}
