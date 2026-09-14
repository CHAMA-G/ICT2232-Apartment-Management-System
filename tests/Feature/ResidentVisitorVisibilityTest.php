<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ResidentVisitorVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_residents_can_see_visitors_registered_by_other_residents(): void
    {
        $residentA = User::factory()->create(['name' => 'Alice']);
        $residentB = User::factory()->create(['name' => 'Bob']);

        DB::table('visitors')->insert([
            'visitor_name' => 'John Doe',
            'phone' => '0771234567',
            'vehicle_number' => 'ABC-1234',
            'flat_number' => 'A-101',
            'resident_id' => $residentA->id,
            'expected_date' => '2026-09-20',
            'status' => 'Pre-registered',
            'check_in_time' => null,
            'check_out_time' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($residentB)->get(route('resident.dashboard'));

        $response->assertOk();
        $response->assertSeeText('John Doe');
        $response->assertSeeText('Alice');
        $response->assertSeeText('Resident');
    }
}
