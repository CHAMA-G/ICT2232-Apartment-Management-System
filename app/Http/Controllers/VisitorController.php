<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function dashboard(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $preRegisteredVisitors = DB::table('visitors')
            ->where('status', 'Pre-registered')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('visitor_name', 'like', "%{$search}%");
            })
            ->orderBy('expected_date')
            ->orderByDesc('created_at')
            ->get();

        $activeVisitors = DB::table('visitors')
            ->where('status', 'Checked-In')
            ->orderByDesc('check_in_time')
            ->get();

        return view('security.dashboard', compact('activeVisitors', 'preRegisteredVisitors', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'flat_number' => 'required|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
        ]);

        DB::table('visitors')->insert([
            'visitor_name' => $request->visitor_name,
            'phone' => $request->phone,
            'vehicle_number' => $request->vehicle_number,
            'flat_number' => $request->flat_number,
            'resident_id' => null,
            'expected_date' => null,
            'status' => 'Checked-In',
            'check_in_time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Visitor Checked-In Successfully!');
    }

    public function checkin($id)
    {
        DB::table('visitors')
            ->where('id', $id)
            ->where('status', 'Pre-registered')
            ->update([
                'status' => 'Checked-In',
                'check_in_time' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Pre-registered visitor checked in successfully.');
    }

    public function checkout($id)
    {
        DB::table('visitors')
            ->where('id', $id)
            ->where('status', 'Checked-In')
            ->update([
                'status' => 'Checked-Out',
                'check_out_time' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Visitor checked out successfully.');
    }

}
