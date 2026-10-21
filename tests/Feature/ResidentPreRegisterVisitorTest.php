<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentPreRegisterVisitorTest extends TestCase
{
    use RefreshDatabase;

    public function test_pre_registering_a_visitor_assigns_the_residents_flat_automatically(): void
    {
        $resident = User::factory()->create([
            'name' => 'Amal Biso',
            'flat_number' => 'A-102',
        ]);

        $response = $this->actingAs($resident)->post(route('resident.pre_register_visitor'), [
            'visitor_name' => 'John Doe',
            'phone' => '0712345674',
            'expected_date' => '2026-09-29',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Visitor pre-registered successfully!');

        $this->assertDatabaseHas('visitors', [
            'visitor_name' => 'John Doe',
            'phone' => '0712345674',
            'flat_number' => 'A-102',
            'resident_id' => $resident->id,
            'status' => 'Pre-registered',
        ]);
    }

    public function test_assigned_flat_accessor_resolves_to_flat_number(): void
    {
        $resident = User::factory()->create(['flat_number' => 'B-305']);

        $this->assertNotNull($resident->assigned_flat);
        $this->assertSame('B-305', $resident->assigned_flat);
    }
}
