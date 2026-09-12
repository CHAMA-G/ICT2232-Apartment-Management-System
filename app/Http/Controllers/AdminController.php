<?php

namespace App\Http\Controllers;

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

        $serviceRequests = DB::table('service_requests')
            ->join('users', 'users.id', '=', 'service_requests.resident_id')
            ->select('service_requests.*', 'users.name as resident_name')
            ->orderByDesc('service_requests.created_at')
            ->get();

        return view('admin.dashboard', compact('todayVisitors', 'activeBookings', 'pendingRequests', 'serviceRequests'));
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
}
