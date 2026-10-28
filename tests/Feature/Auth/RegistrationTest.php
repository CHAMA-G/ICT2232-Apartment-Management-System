<?php

namespace Tests\Feature\Auth;

use App\Models\Flat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        Flat::create([
            'flat_number' => 'A-101',
            'block' => 'A',
            'floor' => '1',
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'flat_number' => 'A-101',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertSame('A-101', User::where('email', 'test@example.com')->value('flat_number'));
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
