<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'visitor_name' => 'required|string',
            'phone' => 'required|string',
            'flat_number' => 'required|string',
        ]);

        DB::table('visitors')->insert([
            'visitor_name' => $request->visitor_name,
            'phone' => $request->phone,
            'vehicle_number' => $request->vehicle_number,
            'flat_number' => $request->flat_number,
            'status' => 'Checked-In',
            'check_in_time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Visitor Checked-In Successfully!');
    }
}
