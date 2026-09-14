<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public function dashboard()
    {
        $residentId = auth()->id();

        $bookings = DB::table('bookings')
            ->where('resident_id', $residentId)
            ->orderByDesc('booking_date')
            ->orderByDesc('created_at')
            ->get();

        $serviceRequests = DB::table('service_requests')
            ->where('resident_id', $residentId)
            ->orderByDesc('created_at')
            ->get();

        $visitorCount = DB::table('visitors')
            ->where('resident_id', $residentId)
            ->count();

        $visitors = DB::table('visitors')
            ->where('resident_id', $residentId)
            ->orderByDesc('created_at')
            ->get();

        return view('resident.dashboard', compact('bookings', 'serviceRequests', 'visitorCount', 'visitors'));
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
            'resident_id' => auth()->id(),
            'expected_date' => $validated['expected_date'],
            'vehicle_number' => $request->vehicle_number ?? null,
            'status' => 'Pre-registered',
            'check_in_time' => null,
            'check_out_time' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Visitor pre-registered successfully!');
    }

    public function destroyVisitor($id)
    {
        $deleted = DB::table('visitors')
            ->where('id', $id)
            ->where('resident_id', auth()->id())
            ->delete();

        return redirect()->back()->with(
            $deleted ? 'success' : 'error',
            $deleted ? 'Visitor removed successfully.' : 'Visitor could not be found.'
        );
    }

    public function updateVisitor(Request $request, $id)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'vehicle_number' => 'nullable|string|max:50',
            'expected_date' => 'required|date',
        ]);

        $updated = DB::table('visitors')
            ->where('id', $id)
            ->where('resident_id', auth()->id())
            ->update(array_merge($validated, ['updated_at' => now()]));

        return redirect()->back()->with($updated ? 'success' : 'error', $updated ? 'Visitor updated successfully.' : 'Visitor could not be found.');
    }

    public function checkAvailability(Request $request)
    {
        $facilityName = $request->query('facility_name');
        $bookingDate = $request->query('booking_date');

        if (!$facilityName || !$bookingDate) {
            return response()->json([]);
        }

        $bookedSlots = DB::table('bookings')
            ->where('facility_name', $facilityName)
            ->where('booking_date', $bookingDate)
            ->pluck('time_slot')
            ->all();

        return response()->json($bookedSlots);
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string',
            'booking_date' => 'required|date',
            'time_slot' => 'required|string',
        ]);

        $alreadyBooked = DB::table('bookings')
            ->where('facility_name', $validated['facility_name'])
            ->where('booking_date', $validated['booking_date'])
            ->where('time_slot', $validated['time_slot'])
            ->exists();

        if ($alreadyBooked) {
            return redirect()->back()->with('error', 'This facility is already booked for the selected date and time slot!');
        }

        DB::table('bookings')->insert([
            'facility_name' => $validated['facility_name'],
            'booking_date' => $validated['booking_date'],
            'time_slot' => $validated['time_slot'],
            'resident_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('booking_success', 'Facility booked successfully!')->with('active_tab', 'booking');
    }

    public function updateBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string',
            'booking_date' => 'required|date',
            'time_slot' => 'required|string',
        ]);

        $updated = DB::table('bookings')->where('id', $id)->where('resident_id', auth()->id())->update(array_merge($validated, ['updated_at' => now()]));

        return redirect()->back()->with($updated ? 'booking_success' : 'error', $updated ? 'Facility booking updated successfully.' : 'Booking could not be found.');
    }

    public function destroyBooking($id)
    {
        $deleted = DB::table('bookings')->where('id', $id)->where('resident_id', auth()->id())->delete();

        return redirect()->back()->with($deleted ? 'booking_success' : 'error', $deleted ? 'Facility booking removed successfully.' : 'Booking could not be found.');
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

    public function updateServiceRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
        ]);

        $updated = DB::table('service_requests')->where('id', $id)->where('resident_id', auth()->id())->update(array_merge($validated, ['updated_at' => now()]));

        return redirect()->back()->with($updated ? 'service_success' : 'error', $updated ? 'Service request updated successfully.' : 'Service request could not be found.');
    }

    public function destroyServiceRequest($id)
    {
        $deleted = DB::table('service_requests')->where('id', $id)->where('resident_id', auth()->id())->delete();

        return redirect()->back()->with($deleted ? 'service_success' : 'error', $deleted ? 'Service request removed successfully.' : 'Service request could not be found.');
    }
}
