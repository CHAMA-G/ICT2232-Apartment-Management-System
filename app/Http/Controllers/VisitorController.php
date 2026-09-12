<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function index()
    {
        return view('security.dashboard');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'flat_number' => 'required|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
        ]);

        DB::table('visitors')->insert([
            'visitor_name' => $validated['visitor_name'],
            'phone' => $validated['phone'],
            'flat_number' => $validated['flat_number'],
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'status' => 'Checked-In',
            'check_in_time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Visitor checked in successfully.');
    }
}
