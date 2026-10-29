<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function dashboard()
    {
        $this->ensureAdministrator();

        $todayVisitors = Schema::hasTable('visitors') ? DB::table('visitors')->whereDate('created_at', today())->count() : 0;
        $activeBookings = Schema::hasTable('bookings') ? DB::table('bookings')->where('status', 'Approved')->count() : 0;
        $pendingRequests = Schema::hasTable('service_requests') ? DB::table('service_requests')->where('status', 'Pending')->count() : 0;
        $pendingRequestsCount = $pendingRequests;
        $visitorsCount = Schema::hasTable('visitors') ? DB::table('visitors')->count() : 0;
        $bookingsCount = Schema::hasTable('bookings') ? DB::table('bookings')->where('status', 'Pending')->count() : 0;

        $serviceRequests = Schema::hasTable('service_requests') && Schema::hasTable('users')
            ? DB::table('service_requests')->join('users', 'users.id', '=', 'service_requests.resident_id')->select('service_requests.*', 'users.name as resident_name', 'users.flat_number as resident_flat')->orderByDesc('service_requests.created_at')->get()
            : collect();

        $visitors = Schema::hasTable('visitors') ? DB::table('visitors')->orderByDesc('created_at')->get() : collect();

        $bookings = Schema::hasTable('bookings') && Schema::hasTable('users')
            ? DB::table('bookings')->join('users', 'users.id', '=', 'bookings.resident_id')->select('bookings.*', 'users.name as resident_name')->orderByDesc('bookings.booking_date')->get()
            : collect();

        $flats = Schema::hasTable('flats') ? Flat::orderBy('block')->orderBy('floor')->orderBy('flat_number')->get() : collect();
        $residents = Schema::hasTable('users') ? User::where('role', 'resident')->orderBy('name')->get() : collect();
        $securityGuards = Schema::hasTable('users') ? User::where('role', 'security')->orderBy('name')->get() : collect();
        $activeSection = match (true) {
            request()->routeIs('admin.flats') => 'flats-section',
            request()->routeIs('admin.residents') => 'residents-section',
            request()->routeIs('admin.security') => 'security-section',
            default => 'overview-section',
        };

        return view('admin.dashboard', compact('todayVisitors', 'activeBookings', 'pendingRequests', 'pendingRequestsCount', 'serviceRequests', 'visitors', 'visitorsCount', 'bookings', 'bookingsCount', 'flats', 'residents', 'securityGuards', 'activeSection'));
    }

    public function manageSecurity()
    {
        $this->ensureAdministrator();

        return $this->dashboard();
    }

    public function storeSecurity(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $securityGuard = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);
        $securityGuard->role = 'security';
        $securityGuard->save();

        return redirect()->route('admin.security')->with('status_success', 'Security guard account created successfully.');
    }

    public function destroySecurity($id)
    {
        $this->ensureAdministrator();

        $securityGuard = User::where('role', 'security')->findOrFail($id);
        $securityGuard->delete();

        return redirect()->route('admin.security')->with('status_success', 'Security guard account deleted successfully.');
    }

    public function residents()
    {
        $this->ensureAdministrator();

        return $this->dashboard();
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
