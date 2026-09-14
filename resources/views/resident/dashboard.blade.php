<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard - Apartment Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        details form[class*="absolute"] {
            position: static;
            width: 100%;
            max-width: 26rem;
            margin-top: 0.75rem;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">
    <div class="flex min-h-screen">
        <aside class="fixed left-0 top-0 z-20 flex min-h-screen w-64 flex-col justify-between bg-slate-900 text-white shadow-xl">
            <div>
                <div class="border-b border-slate-800 bg-slate-950 px-6 py-5">
                    <span class="block text-md font-black tracking-widest text-indigo-400">APARTMENT RESIDENT</span>
                    <span class="text-xs font-medium text-slate-500">Resident Portal</span>
                </div>

                <nav class="mt-6 space-y-2 px-4">
                    <button type="button" data-tab-target="overview" class="resident-tab flex w-full items-center rounded-lg bg-indigo-600 px-4 py-3 text-left text-sm font-semibold text-white shadow-md transition">
                        <span class="mr-3">📊</span> Overview Dashboard
                    </button>
                    <button type="button" data-tab-target="pre-register" class="resident-tab flex w-full items-center rounded-lg px-4 py-3 text-left text-sm font-semibold text-slate-300 transition hover:bg-slate-800 hover:text-white">
                        <span class="mr-3">👤</span> Pre-Register Visitor
                    </button>
                    <button type="button" data-tab-target="facility-booking" class="resident-tab flex w-full items-center rounded-lg px-4 py-3 text-left text-sm font-semibold text-slate-300 transition hover:bg-slate-800 hover:text-white">
                        <span class="mr-3">🏊</span> Book a Facility
                    </button>
                    <button type="button" data-tab-target="service-request" class="resident-tab flex w-full items-center rounded-lg px-4 py-3 text-left text-sm font-semibold text-slate-300 transition hover:bg-slate-800 hover:text-white">
                        <span class="mr-3">🛠️</span> Submit Service Request
                    </button>
                    <button type="button" data-tab-target="manage-records" class="resident-tab flex w-full items-center rounded-lg px-4 py-3 text-left text-sm font-semibold text-slate-300 transition hover:bg-slate-800 hover:text-white">
                        <span class="mr-3">✏️</span> Manage My Records
                    </button>
                </nav>
            </div>

            <div class="border-t border-slate-800 bg-slate-950 p-4">
                <div class="mb-2 flex items-center justify-between">
                    <span class="max-w-[120px] truncate text-xs font-bold tracking-wide text-slate-400">{{ auth()->user()->name }}</span>
                    <span class="rounded bg-indigo-900 px-2 py-0.5 text-[10px] font-extrabold uppercase text-indigo-200">Resident</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded px-3 py-1.5 text-left text-xs font-bold text-rose-400 transition hover:bg-rose-950/30">🚪 Log Out</button>
                </form>
            </div>
        </aside>

        <main class="min-h-screen flex-1 bg-gray-50 p-8 md:ml-64">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between border-b pb-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Resident Services</p>
                        <h1 class="mt-0.5 text-2xl font-black tracking-tight text-slate-800">Resident Dashboard</h1>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-right shadow-sm">
                        <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Apartment resident</p>
                    </div>
                </div>

                <section data-tab-panel="overview" class="resident-panel block">
                    <div class="mb-8 grid max-w-6xl grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div><p class="text-xs font-bold uppercase tracking-wider text-gray-400">Visitors Registered</p><h3 class="mt-2 text-3xl font-black text-gray-900">{{ $visitorCount }}</h3></div>
                            <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">● Live</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div><p class="text-xs font-bold uppercase tracking-wider text-gray-400">My Bookings</p><h3 class="mt-2 text-3xl font-black text-gray-900">{{ $bookings->count() }}</h3></div>
                            <span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">● Active</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div><p class="text-xs font-bold uppercase tracking-wider text-gray-400">Open Requests</p><h3 class="mt-2 text-3xl font-black text-gray-900">{{ $serviceRequests->where('status', 'Pending')->count() }}</h3></div>
                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">● Review</span>
                        </div>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                            <h2 class="mb-4 border-b pb-2 text-base font-black tracking-tight text-gray-800">My Facility Bookings</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-slate-50"><tr><th class="px-3 py-3 text-left font-bold text-slate-600">Facility</th><th class="px-3 py-3 text-left font-bold text-slate-600">Date & Time</th><th class="px-3 py-3 text-left font-bold text-slate-600">Status</th></tr></thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($bookings as $booking)
                                            <tr><td class="px-3 py-3 font-semibold text-slate-700">{{ $booking->facility_name }}</td><td class="px-3 py-3 text-slate-600">{{ $booking->booking_date }}<br><span class="text-xs">{{ $booking->time_slot }}</span></td><td class="px-3 py-3"><span class="rounded-full bg-indigo-100 px-2 py-1 text-xs font-bold text-indigo-700">{{ $booking->status }}</span></td></tr>
                                        @empty
                                            <tr><td colspan="3" class="px-3 py-6 text-center text-slate-500">No facility bookings yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                            <h2 class="mb-4 border-b pb-2 text-base font-black tracking-tight text-gray-800">My Service Requests</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-slate-50"><tr><th class="px-3 py-3 text-left font-bold text-slate-600">Category</th><th class="px-3 py-3 text-left font-bold text-slate-600">Submitted</th><th class="px-3 py-3 text-left font-bold text-slate-600">Status</th></tr></thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($serviceRequests as $request)
                                            <tr><td class="px-3 py-3 font-semibold text-slate-700">{{ $request->category }}</td><td class="px-3 py-3 text-slate-600">{{ \Carbon\Carbon::parse($request->created_at)->format('Y-m-d') }}</td><td class="px-3 py-3"><span class="rounded-full px-2 py-1 text-xs font-bold @if($request->status === 'Pending') bg-amber-100 text-amber-700 @elseif($request->status === 'In Progress') bg-blue-100 text-blue-700 @else bg-green-100 text-green-700 @endif">{{ $request->status }}</span></td></tr>
                                        @empty
                                            <tr><td colspan="3" class="px-3 py-6 text-center text-slate-500">No service requests yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="mb-4 border-b pb-2 text-base font-black tracking-tight text-gray-800">All Visitors</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Visitor</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Resident</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Phone</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Expected</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Status</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Check-In</th>
                                        <th class="px-3 py-3 text-right font-bold text-slate-600">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($visitors as $visitor)
                                        <tr>
                                            <td class="px-3 py-3 font-semibold text-slate-700">{{ $visitor->visitor_name }}</td>
                                            <td class="px-3 py-3 text-slate-600">{{ $visitor->resident_name ?? 'Security' }}</td>
                                            <td class="px-3 py-3 text-slate-600">{{ $visitor->phone }}</td>
                                            <td class="px-3 py-3 text-slate-600">{{ $visitor->expected_date ?? 'N/A' }}</td>
                                            <td class="px-3 py-3">
                                                <span class="rounded-full px-2 py-1 text-xs font-bold @if($visitor->status === 'Checked-In') bg-green-100 text-green-700 @elseif($visitor->status === 'Checked-Out') bg-gray-100 text-gray-700 @else bg-blue-100 text-blue-700 @endif">
                                                    {{ $visitor->status }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-slate-600">{{ $visitor->check_in_time ?? 'Not checked in' }}</td>
                                            <td class="px-3 py-3 text-right">
                                                <form action="{{ route('resident.destroy_visitor', $visitor->id) }}" method="POST" onsubmit="return confirm('Remove this visitor?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg bg-red-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-red-700">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-3 py-6 text-center text-slate-500">No visitors have been registered yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section data-tab-panel="manage-records" class="resident-panel hidden">
                    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                            <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">Account Management</p><h2 class="mt-1 text-2xl font-black tracking-tight text-gray-900">Manage My Records</h2><p class="mt-1 text-sm text-gray-500">Keep your apartment activity accurate and up to date.</p></div>
                            <div class="flex gap-3 text-center"><div class="rounded-xl bg-indigo-50 px-4 py-2"><p class="text-xl font-black text-indigo-700">{{ $bookings->count() }}</p><p class="text-[10px] font-bold uppercase tracking-wide text-indigo-500">Bookings</p></div><div class="rounded-xl bg-amber-50 px-4 py-2"><p class="text-xl font-black text-amber-700">{{ $serviceRequests->count() }}</p><p class="text-[10px] font-bold uppercase tracking-wide text-amber-500">Requests</p></div><div class="rounded-xl bg-emerald-50 px-4 py-2"><p class="text-xl font-black text-emerald-700">{{ $visitors->count() }}</p><p class="text-[10px] font-bold uppercase tracking-wide text-emerald-500">Visitors</p></div></div>
                        </div>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3"><div><p class="text-xs font-bold uppercase tracking-wider text-indigo-500">Amenities</p><h3 class="mt-1 text-lg font-black text-gray-800">Facility Bookings</h3></div><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $bookings->count() }} total</span></div>
                            <div class="space-y-3">
                                @forelse($bookings as $booking)
                                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-indigo-200 hover:bg-white"><div class="flex items-start justify-between gap-3"><div><p class="font-bold text-gray-800">{{ $booking->facility_name }}</p><p class="mt-1 text-xs text-gray-500">{{ $booking->booking_date }} · {{ $booking->time_slot }}</p></div><span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $booking->status }}</span></div><div class="mt-3 flex gap-2"><details class="relative"><summary class="cursor-pointer list-none rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-xs font-bold text-indigo-700 shadow-sm hover:bg-indigo-50">Edit record</summary><form action="{{ route('resident.update_booking', $booking->id) }}" method="POST" class="absolute right-0 z-30 mt-2 w-64 space-y-2 rounded-xl border border-slate-200 bg-white p-4 shadow-xl">@csrf @method('PATCH')<p class="text-xs font-bold uppercase tracking-wide text-slate-500">Update booking</p><select name="facility_name" class="w-full rounded-lg border border-slate-300 p-2 text-xs"><option {{ $booking->facility_name === 'Gym' ? 'selected' : '' }}>Gym</option><option {{ $booking->facility_name === 'Swimming Pool' ? 'selected' : '' }}>Swimming Pool</option><option {{ $booking->facility_name === 'Event Hall' ? 'selected' : '' }}>Event Hall</option></select><input type="date" name="booking_date" value="{{ $booking->booking_date }}" required class="w-full rounded-lg border border-slate-300 p-2 text-xs"><input name="time_slot" value="{{ $booking->time_slot }}" required class="w-full rounded-lg border border-slate-300 p-2 text-xs"><button class="w-full rounded-lg bg-blue-600 px-2 py-2 text-xs font-bold text-white hover:bg-blue-700">Save Changes</button></form></details><form action="{{ route('resident.destroy_booking', $booking->id) }}" method="POST" onsubmit="return confirm('Remove this booking?');">@csrf @method('DELETE')<button class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">Remove</button></form></div></div>
                                @empty
                                    <p class="text-sm text-gray-500">No facility bookings yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3"><div><p class="text-xs font-bold uppercase tracking-wider text-amber-500">Maintenance</p><h3 class="mt-1 text-lg font-black text-gray-800">Service Requests</h3></div><span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">{{ $serviceRequests->count() }} total</span></div>
                            <div class="space-y-3">
                                @forelse($serviceRequests as $request)
                                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-amber-200 hover:bg-white"><div class="flex items-start justify-between gap-3"><div><p class="font-bold text-gray-800">{{ $request->category }}</p><p class="mt-1 text-xs text-gray-500">{{ \Carbon\Carbon::parse($request->created_at)->format('Y-m-d') }}</p></div><span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">{{ $request->status }}</span></div><div class="mt-3 flex gap-2"><details class="relative"><summary class="cursor-pointer list-none rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-xs font-bold text-indigo-700 shadow-sm hover:bg-indigo-50">Edit record</summary><form action="{{ route('resident.update_service_request', $request->id) }}" method="POST" class="absolute right-0 z-30 mt-2 w-64 space-y-2 rounded-xl border border-slate-200 bg-white p-4 shadow-xl">@csrf @method('PATCH')<p class="text-xs font-bold uppercase tracking-wide text-slate-500">Update request</p><select name="category" class="w-full rounded-lg border border-slate-300 p-2 text-xs"><option {{ $request->category === 'Plumbing' ? 'selected' : '' }}>Plumbing</option><option {{ $request->category === 'Electrical' ? 'selected' : '' }}>Electrical</option><option {{ $request->category === 'Carpentry' ? 'selected' : '' }}>Carpentry</option><option {{ $request->category === 'Cleaning' ? 'selected' : '' }}>Cleaning</option><option {{ $request->category === 'Security' ? 'selected' : '' }}>Security</option></select><textarea name="description" required class="w-full rounded-lg border border-slate-300 p-2 text-xs">{{ $request->description }}</textarea><button class="w-full rounded-lg bg-blue-600 px-2 py-2 text-xs font-bold text-white hover:bg-blue-700">Save Changes</button></form></details><form action="{{ route('resident.destroy_service_request', $request->id) }}" method="POST" onsubmit="return confirm('Remove this service request?');">@csrf @method('DELETE')<button class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">Remove</button></form></div></div>
                                @empty
                                    <p class="text-sm text-gray-500">No service requests yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
                            <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3"><div><p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Access Control</p><h3 class="mt-1 text-lg font-black text-gray-800">Visitors</h3></div><span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">{{ $visitors->count() }} total</span></div>
                            <div class="space-y-3">
                                @forelse($visitors as $visitor)
                                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-emerald-200 hover:bg-white"><div><p class="font-bold text-gray-800">{{ $visitor->visitor_name }}</p><p class="mt-1 text-xs text-gray-500">{{ $visitor->phone }} · {{ $visitor->expected_date ?? 'No expected date' }}</p></div><div class="flex gap-2"><details class="relative"><summary class="cursor-pointer list-none rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-xs font-bold text-indigo-700 shadow-sm hover:bg-indigo-50">Edit record</summary><form action="{{ route('resident.update_visitor', $visitor->id) }}" method="POST" class="absolute right-0 z-30 mt-2 w-64 space-y-2 rounded-xl border border-slate-200 bg-white p-4 shadow-xl">@csrf @method('PATCH')<p class="text-xs font-bold uppercase tracking-wide text-slate-500">Update visitor</p><input name="visitor_name" value="{{ $visitor->visitor_name }}" required class="w-full rounded-lg border border-slate-300 p-2 text-xs"><input name="phone" value="{{ $visitor->phone }}" required class="w-full rounded-lg border border-slate-300 p-2 text-xs"><input name="vehicle_number" value="{{ $visitor->vehicle_number }}" class="w-full rounded-lg border border-slate-300 p-2 text-xs"><input type="date" name="expected_date" value="{{ $visitor->expected_date }}" required class="w-full rounded-lg border border-slate-300 p-2 text-xs"><button class="w-full rounded-lg bg-blue-600 px-2 py-2 text-xs font-bold text-white hover:bg-blue-700">Save Changes</button></form></details><form action="{{ route('resident.destroy_visitor', $visitor->id) }}" method="POST" onsubmit="return confirm('Remove this visitor?');">@csrf @method('DELETE')<button class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">Remove</button></form></div></div>
                                @empty
                                    <p class="text-sm text-gray-500">No visitors registered yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <section data-tab-panel="pre-register" class="resident-panel hidden">
                    <div class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <p class="text-sm font-bold text-indigo-600">Visitor Management</p><h2 class="mt-1 text-2xl font-black text-gray-900">Pre-Register Visitor</h2>
                        @if($errors->any())<div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                        @if(session('success'))<div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-700">{{ session('success') }}</div>@endif
                        <form action="{{ route('resident.pre_register_visitor') }}" method="POST" class="mt-6 max-w-xl space-y-5">@csrf
                            <div><label for="visitor_name" class="mb-2 block text-sm font-semibold text-gray-700">Visitor Name</label><input id="visitor_name" name="visitor_name" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"></div>
                            <div><label for="phone" class="mb-2 block text-sm font-semibold text-gray-700">Phone Number</label><input id="phone" name="phone" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"></div>
                            <div><label for="expected_date" class="mb-2 block text-sm font-semibold text-gray-700">Expected Date</label><input type="date" id="expected_date" name="expected_date" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"></div>
                            <button class="w-full rounded-lg bg-indigo-600 px-4 py-3 font-bold text-white shadow-lg shadow-indigo-600/20 hover:bg-indigo-700">Pre-Register Visitor</button>
                        </form>
                    </div>
                </section>

                <section data-tab-panel="facility-booking" class="resident-panel hidden">
                    <div class="max-w-5xl rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="mb-6"><p class="text-sm font-bold text-blue-600">Amenities</p><h2 class="mt-1 text-2xl font-black text-gray-900">Book a Facility</h2></div>
                        @if(session('booking_success'))<div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-700">{{ session('booking_success') }}</div>@endif
                        @if(session('error'))<div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700">{{ session('error') }}</div>@endif

                        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem]">
                            <form action="{{ route('resident.book_facility') }}" method="POST" class="max-w-xl space-y-5">@csrf
                                <div id="booking-alert-box" class="hidden mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm font-bold text-red-800"></div>
                                <div><label for="facility_name" class="mb-2 block text-sm font-semibold text-gray-700">Facility</label><select id="facility_name" name="facility_name" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"><option value="">Select facility</option><option>Gym</option><option>Swimming Pool</option><option>Event Hall</option></select></div>
                                <div><label for="booking_date" class="mb-2 block text-sm font-semibold text-gray-700">Date</label><input type="date" id="booking_date" name="booking_date" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"></div>
                                <div><label for="time_slot" class="mb-2 block text-sm font-semibold text-gray-700">Time Slot</label><select id="time_slot" name="time_slot" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"><option value="">Select time slot</option><option value="08:00 AM - 10:00 AM">08:00 AM - 10:00 AM</option><option value="10:00 AM - 12:00 PM">10:00 AM - 12:00 PM</option><option value="01:00 PM - 03:00 PM">01:00 PM - 03:00 PM</option><option value="04:00 PM - 06:00 PM">04:00 PM - 06:00 PM</option></select></div>
                                <button class="w-full rounded-lg bg-blue-600 px-4 py-3 font-bold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700">Book Facility</button>
                            </form>

                            @php
                                $calendarMonth = now()->startOfMonth();
                                $daysInMonth = $calendarMonth->daysInMonth;
                                $firstDayOffset = $calendarMonth->dayOfWeek;
                            @endphp
                            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-blue-50/40 p-4 shadow-inner">
                                <div class="mb-4 flex items-center justify-between"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-blue-500">Availability</p><h3 class="mt-0.5 font-black text-slate-800">{{ $calendarMonth->format('F Y') }}</h3></div><span class="rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-bold text-blue-700">Booked</span></div>
                                <div class="mb-2 grid grid-cols-7 text-center text-[9px] font-black uppercase tracking-wide text-slate-400" style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));"><span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span></div>
                                <div class="text-center text-xs" style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:0.25rem;">
                                    @for($blank = 0; $blank < $firstDayOffset; $blank++)<span class="h-8"></span>@endfor
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php $calendarDate = $calendarMonth->copy()->day($day)->format('Y-m-d'); $dayBookings = $bookings->where('booking_date', $calendarDate); @endphp
                                        <span class="relative flex h-8 items-center justify-center rounded-lg text-[11px] transition {{ $dayBookings->count() ? 'bg-blue-600 font-bold text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}">{{ $day }}@if($dayBookings->count())<span class="absolute bottom-1 h-1 w-1 rounded-full bg-white"></span>@endif</span>
                                    @endfor
                                </div>
                                <div class="mt-5 border-t border-slate-200 pt-3"><p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-500">Upcoming booked slots</p><div class="space-y-2">
                                    @forelse($bookings->take(3) as $booking)<div class="rounded-lg bg-white px-3 py-2"><p class="text-xs font-bold text-slate-800">{{ $booking->booking_date }} · {{ $booking->facility_name }}</p><p class="text-[11px] text-slate-500">{{ $booking->time_slot }}</p></div>@empty<p class="text-xs text-slate-500">No booked slots yet.</p>@endforelse
                                </div></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section data-tab-panel="service-request" class="resident-panel hidden">
                    <div class="max-w-5xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <p class="text-sm font-bold text-amber-600">Operations</p><h2 class="mt-1 text-2xl font-black text-gray-900">Submit Service Request</h2>
                        @if(session('service_success'))<div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-700">{{ session('service_success') }}</div>@endif
                        <form action="{{ route('resident.store_service_request') }}" method="POST" class="mt-6 max-w-2xl space-y-5">@csrf
                            <div><label for="category" class="mb-2 block text-sm font-semibold text-gray-700">Category</label><select id="category" name="category" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"><option value="">Select category</option><option>Plumbing</option><option>Electrical</option><option>Carpentry</option><option>Cleaning</option><option>Security</option></select></div>
                            <div><label for="description" class="mb-2 block text-sm font-semibold text-gray-700">Description</label><textarea id="description" name="description" rows="4" required class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"></textarea></div>
                            <button class="w-full rounded-lg bg-amber-500 px-4 py-3 font-bold text-white shadow-lg shadow-amber-500/20 hover:bg-amber-600">Submit Service Request</button>
                        </form>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.resident-tab');
            const panels = document.querySelectorAll('.resident-panel');
            const tabMap = {
                booking: 'facility-booking',
                facility: 'facility-booking',
                'facility-booking': 'facility-booking'
            };

            function activateTab(targetName) {
                const target = tabMap[targetName] || targetName;
                tabs.forEach(function (tab) {
                    const isActive = tab.dataset.tabTarget === target;
                    tab.classList.toggle('bg-indigo-600', isActive);
                    tab.classList.toggle('text-white', isActive);
                    tab.classList.toggle('shadow-md', isActive);
                    tab.classList.toggle('text-slate-300', !isActive);
                });

                panels.forEach(function (panel) {
                    panel.classList.toggle('hidden', panel.dataset.tabPanel !== target);
                });
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    activateTab(tab.dataset.tabTarget);
                });
            });

            @if(session('active_tab'))
                const activeTab = "{{ session('active_tab') }}";
                const restoredTab = tabMap[activeTab] || activeTab;
                const activeTabButton = document.querySelector('.resident-tab[data-tab-target="' + restoredTab + '"]');
                if (activeTabButton) {
                    activeTabButton.click();
                }
            @endif

            const facilitySelect = document.getElementById('facility_name');
            const bookingDateInput = document.getElementById('booking_date');
            const timeSlotSelect = document.getElementById('time_slot');
            const bookingAlertBox = document.getElementById('booking-alert-box');
            let bookedSlots = [];

            function hideBookingAlert() {
                bookingAlertBox.classList.add('hidden');
                bookingAlertBox.textContent = '';
            }

            function showBookingAlert(message) {
                bookingAlertBox.textContent = message;
                bookingAlertBox.classList.remove('hidden');
            }

            function fetchBookedSlots() {
                const facilityName = facilitySelect.value;
                const bookingDate = bookingDateInput.value;

                hideBookingAlert();
                timeSlotSelect.value = '';

                if (!facilityName || !bookingDate) {
                    bookedSlots = [];
                    return;
                }

                fetch('/resident/check-availability?facility_name=' + encodeURIComponent(facilityName) + '&booking_date=' + encodeURIComponent(bookingDate), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(function (response) { return response.json(); })
                    .then(function (slots) {
                        bookedSlots = Array.isArray(slots) ? slots : [];
                    })
                    .catch(function () {
                        bookedSlots = [];
                    });
            }

            facilitySelect.addEventListener('change', function (e) {
                e.preventDefault();
                fetchBookedSlots();
            });

            bookingDateInput.addEventListener('change', function (e) {
                e.preventDefault();
                fetchBookedSlots();
            });

            timeSlotSelect.addEventListener('change', function (e) {
                e.preventDefault();
                const selectedSlot = this.value;

                if (!selectedSlot) {
                    hideBookingAlert();
                    return;
                }

                if (bookedSlots.includes(selectedSlot)) {
                    showBookingAlert('⚠️ Warning: This slot is already reserved by another resident for today! Please choose an alternative time.');
                    this.value = '';
                    return;
                }

                hideBookingAlert();
            });
        });
    </script>
</body>
</html>