<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSecurityManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_delete_a_security_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.security.store'), [
                'name' => 'Sam Guard',
                'email' => 'sam.guard@example.com',
                'phone' => '0712345678',
                'password' => 'SecurePassword123!',
                'password_confirmation' => 'SecurePassword123!',
            ])
            ->assertRedirect(route('admin.security'));

        $guard = User::where('email', 'sam.guard@example.com')->firstOrFail();

        $this->assertSame('security', $guard->role);
        $this->assertSame('0712345678', $guard->phone);
        $this->assertTrue(Hash::check('SecurePassword123!', $guard->password));

        $this->actingAs($admin)
            ->get(route('admin.security'))
            ->assertOk()
            ->assertSee('Manage Security Guards')
            ->assertSee($guard->name)
            ->assertSee($guard->email)
            ->assertSee($guard->phone)
            ->assertSee('Delete Account');

        $this->actingAs($admin)
            ->delete(route('admin.security.destroy', $guard->id))
            ->assertRedirect(route('admin.security'));

        $this->assertDatabaseMissing('users', ['id' => $guard->id]);
    }

    public function test_security_management_is_only_available_to_administrators(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        $this->actingAs($resident)
            ->get(route('admin.security'))
            ->assertForbidden();

        $this->actingAs($resident)
            ->post(route('admin.security.store'), [
                'name' => 'Unauthorized Guard',
                'email' => 'unauthorized.guard@example.com',
                'phone' => '0712345678',
                'password' => 'SecurePassword123!',
                'password_confirmation' => 'SecurePassword123!',
            ])
            ->assertForbidden();
    }

    public function test_admin_cannot_delete_a_non_security_account_through_security_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $resident = User::factory()->create(['role' => 'resident']);

        $this->actingAs($admin)
            ->delete(route('admin.security.destroy', $resident->id))
            ->assertNotFound();

        $this->assertDatabaseHas('users', ['id' => $resident->id]);
    }
}
