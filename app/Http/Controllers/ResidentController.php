<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public function dashboard()
    {
        return view('resident.dashboard');
    }

    public function storePreRegisteredVisitor(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'expected_date' => 'required|date',
        ]);

        DB::table('visitors')->insert([
            'visitor_name' => $validated['visitor_name'],
            'phone' => $validated['phone'],
            'flat_number' => auth()->user()->flat_number ?? 'N/A',
            'vehicle_number' => $request->vehicle_number ?? null,
            'status' => 'Pre-registered',
            'check_in_time' => null,
            'check_out_time' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Visitor pre-registered successfully!');
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string',
            'booking_date' => 'required|date',
            'time_slot' => 'required|string',
        ]);

        DB::table('bookings')->insert([
            'facility_name' => $validated['facility_name'],
            'booking_date' => $validated['booking_date'],
            'time_slot' => $validated['time_slot'],
            'resident_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('booking_success', 'Facility booked successfully!');
    }

    public function storeServiceRequest(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
        ]);

        DB::table('service_requests')->insert([
            'resident_id' => auth()->id(),
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('service_success', 'Service request submitted successfully!');
    }
}
