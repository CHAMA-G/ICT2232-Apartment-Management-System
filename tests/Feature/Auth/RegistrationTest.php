<?php

namespace Tests\Feature\Auth;

use App\Mail\RegistrationVerificationCode;
use App\Models\Flat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Mail::fake();

        Flat::create([
            'flat_number' => 'A-101',
            'block' => 'A',
            'floor' => '1',
        ]);

        $this->post(route('register.email-code'), [
            'email' => 'test@gmail.com',
        ])->assertRedirect(route('register.verify'));

        Mail::assertSent(RegistrationVerificationCode::class, function (RegistrationVerificationCode $mail): bool {
            return $mail->hasTo('test@gmail.com');
        });

        $mail = Mail::sent(RegistrationVerificationCode::class)->first();

        $this->post(route('register.verify.submit'), [
            'code' => $mail->code,
        ])->assertRedirect(route('register.complete'));

        $response = $this->post('/register', [
            'name' => 'Test User',
            'flat_number' => 'A-101',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertSame('A-101', User::where('email', 'test@gmail.com')->value('flat_number'));
        $this->assertNotNull(User::where('email', 'test@gmail.com')->value('email_verified_at'));
        $this->assertDatabaseMissing('registration_email_verifications', ['email' => 'test@gmail.com']);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_non_gmail_addresses_cannot_start_registration(): void
    {
        Mail::fake();

        $this->post(route('register.email-code'), [
            'email' => 'test@example.com',
        ])->assertSessionHasErrors('email');

        Mail::assertNothingSent();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_registration_cannot_be_submitted_without_a_verified_gmail(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'flat_number' => 'A-101',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_incorrect_verification_codes_are_rejected(): void
    {
        Mail::fake();

        $this->post(route('register.email-code'), [
            'email' => 'test@gmail.com',
        ]);

        $mail = Mail::sent(RegistrationVerificationCode::class)->first();
        $wrongCode = $mail->code === '000000' ? '000001' : '000000';

        $this->post(route('register.verify.submit'), [
            'code' => $wrongCode,
        ])->assertSessionHasErrors('code');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_expired_verification_codes_cannot_be_used(): void
    {
        Mail::fake();

        $this->post(route('register.email-code'), [
            'email' => 'test@gmail.com',
        ]);

        $mail = Mail::sent(RegistrationVerificationCode::class)->first();
        $this->travel(11)->minutes();

        $this->post(route('register.verify.submit'), [
            'code' => $mail->code,
        ])->assertSessionHasErrors('code');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_verification_codes_are_limited_to_five_attempts(): void
    {
        Mail::fake();

        $this->post(route('register.email-code'), [
            'email' => 'test@gmail.com',
        ]);

        $mail = Mail::sent(RegistrationVerificationCode::class)->first();
        $wrongCode = $mail->code === '000000' ? '000001' : '000000';

        foreach (range(1, 5) as $attempt) {
            $this->post(route('register.verify.submit'), [
                'code' => $wrongCode,
            ])->assertSessionHasErrors('code');
        }

        $this->post(route('register.verify.submit'), [
            'code' => $mail->code,
        ])->assertSessionHasErrors('code');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_verification_codes_cannot_be_resent_within_one_minute(): void
    {
        Mail::fake();

        $this->post(route('register.email-code'), [
            'email' => 'test@gmail.com',
        ])->assertRedirect(route('register.verify'));

        $this->post(route('register.email-code'), [
            'email' => 'test@gmail.com',
        ])->assertSessionHasErrors('email');

        Mail::assertSent(RegistrationVerificationCode::class, 1);
    }
}
