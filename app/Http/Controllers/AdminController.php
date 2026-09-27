<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $todayVisitors = DB::table('visitors')
            ->whereDate('created_at', today())
            ->count();

        $activeBookings = DB::table('bookings')
            ->where('status', 'Approved')
            ->count();

        $pendingRequests = DB::table('service_requests')
            ->where('status', 'Pending')
            ->count();

        $pendingRequestsCount = $pendingRequests;
        $visitorsCount = DB::table('visitors')->count();
        $bookingsCount = DB::table('bookings')
            ->where('status', 'Pending')
            ->count();

        $serviceRequests = DB::table('service_requests')
            ->join('users', 'users.id', '=', 'service_requests.resident_id')
            ->select('service_requests.*', 'users.name as resident_name')
            ->orderByDesc('service_requests.created_at')
            ->get();

        $visitors = DB::table('visitors')
            ->orderByDesc('created_at')
            ->get();

        $bookings = DB::table('bookings')
            ->join('users', 'users.id', '=', 'bookings.resident_id')
            ->select('bookings.*', 'users.name as resident_name')
            ->orderByDesc('bookings.booking_date')
            ->get();

        $flats = Flat::orderBy('block')->orderBy('floor')->orderBy('flat_number')->get();
        $activeSection = request()->routeIs('admin.flats') ? 'flats-section' : 'overview-section';

        return view('admin.dashboard', compact('todayVisitors', 'activeBookings', 'pendingRequests', 'pendingRequestsCount', 'serviceRequests', 'visitors', 'visitorsCount', 'bookings', 'bookingsCount', 'flats', 'activeSection'));
    }

    public function manageFlats()
    {
        $this->ensureAdministrator();

        return $this->dashboard();
    }

    public function storeFlat(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'flat_number' => 'required|string|max:50|unique:flats,flat_number',
            'block' => 'required|string|max:50',
            'floor' => 'required|string|max:50',
        ]);

        Flat::create($validated);

        return redirect()->route('admin.flats')->with('status_success', 'Flat added successfully.');
    }

    public function destroyFlat($id)
    {
        $this->ensureAdministrator();

        $flat = Flat::findOrFail($id);

        if (DB::table('users')->where('flat_number', $flat->flat_number)->exists()
            || DB::table('visitors')->where('flat_number', $flat->flat_number)->exists()) {
            return redirect()->route('admin.flats')->with('status_error', 'This flat is assigned to residents or visitor records and cannot be deleted.');
        }

        $flat->delete();

        return redirect()->route('admin.flats')->with('status_success', 'Flat deleted successfully.');
    }

    private function ensureAdministrator(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }

    public function storeAdminRequestStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,In Progress,Resolved',
        ]);

        DB::table('service_requests')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('status_success', 'Service request status updated successfully.');
    }

    public function updateVisitor(Request $request, $id)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'vehicle_number' => 'nullable|string|max:50',
            'flat_number' => 'required|string|exists:flats,flat_number',
            'status' => 'required|string|in:Pre-registered,Checked-In,Checked-Out',
        ]);

        DB::table('visitors')->where('id', $id)->update(array_merge($validated, ['updated_at' => now()]));

        return redirect()->back()->with('status_success', 'Visitor updated successfully.');
    }

    public function destroyVisitor($id)
    {
        DB::table('visitors')->where('id', $id)->delete();

        return redirect()->back()->with('status_success', 'Visitor removed successfully.');
    }

    public function updateBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string',
            'booking_date' => 'required|date',
            'time_slot' => 'required|string',
            'status' => 'required|string|in:Pending,Approved,Cancelled',
        ]);

        DB::table('bookings')->where('id', $id)->update(array_merge($validated, ['updated_at' => now()]));

        return redirect()->back()->with('status_success', 'Facility booking updated successfully.');
    }

    public function destroyBooking($id)
    {
        DB::table('bookings')->where('id', $id)->delete();

        return redirect()->back()->with('status_success', 'Facility booking removed successfully.');
    }

    public function updateServiceRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string|in:Pending,In Progress,Resolved',
        ]);

        DB::table('service_requests')->where('id', $id)->update(array_merge($validated, ['updated_at' => now()]));

        return redirect()->back()->with('status_success', 'Service request updated successfully.');
    }

    public function destroyServiceRequest($id)
    {
        DB::table('service_requests')->where('id', $id)->delete();

        return redirect()->back()->with('status_success', 'Service request removed successfully.');
    }
}
