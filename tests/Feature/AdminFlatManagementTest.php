<?php

namespace Tests\Feature;

use App\Models\Flat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFlatManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_delete_a_flat(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.flats.store'), [
                'flat_number' => 'A-101',
                'block' => 'A',
                'floor' => '1',
            ])
            ->assertRedirect(route('admin.flats'));

        $flat = Flat::where('flat_number', 'A-101')->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.flats.destroy', $flat->id))
            ->assertRedirect(route('admin.flats'));

        $this->assertDatabaseMissing('flats', ['id' => $flat->id]);
    }

    public function test_only_administrators_can_manage_flats(): void
    {
        $resident = User::factory()->create();

        $this->actingAs($resident)
            ->get(route('admin.flats'))
            ->assertForbidden();

        $this->actingAs($resident)
            ->post(route('admin.flats.store'), [
                'flat_number' => 'A-101',
                'block' => 'A',
                'floor' => '1',
            ])
            ->assertForbidden();
    }

    public function test_assigned_flats_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $flat = Flat::create(['flat_number' => 'A-101', 'block' => 'A', 'floor' => '1']);
        User::factory()->create(['flat_number' => $flat->flat_number]);

        $this->actingAs($admin)
            ->delete(route('admin.flats.destroy', $flat->id))
            ->assertRedirect(route('admin.flats'));

        $this->assertDatabaseHas('flats', ['id' => $flat->id]);
    }
}
