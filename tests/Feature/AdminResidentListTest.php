<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResidentListTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_registered_residents_but_not_other_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $resident = User::factory()->create([
            'name' => 'Asha Resident',
            'email' => 'asha@example.com',
            'flat_number' => 'A-101',
            'role' => 'resident',
        ]);
        User::factory()->create([
            'name' => 'Security User',
            'role' => 'security',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.residents'))
            ->assertOk()
            ->assertSee('Registered Residents')
            ->assertSee($resident->name)
            ->assertSee($resident->email)
            ->assertSee($resident->flat_number);

        $response->assertViewHas('residents', function ($residents) use ($resident): bool {
            return $residents->contains('id', $resident->id)
                && ! $residents->contains('name', 'Security User');
        });
    }

    public function test_non_admin_cannot_view_registered_residents(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        $this->actingAs($resident)
            ->get(route('admin.residents'))
            ->assertForbidden();
    }
}
