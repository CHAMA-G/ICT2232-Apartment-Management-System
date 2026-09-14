<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Apartment Management</title>
    <!-- Tailwind CSS සහ Fonts කෙලින්ම සම්බන්ධ කිරීම -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        details form[class*="shadow-lg"] { position: static; width: 100%; max-width: 26rem; margin-top: 0.75rem; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800">

    <div class="flex min-h-screen bg-slate-100">
        
        <!-- 🛡️ 1. 100% පැහැදිලි තද කළු පාට PROFESSIONAL SIDEBAR (HIGH CONTRAST) -->
        <aside class="w-64 bg-slate-900 text-white min-h-screen fixed left-0 top-0 shadow-xl flex flex-col justify-between z-20">
            <div>
                <!-- Brand Title -->
                <div class="px-6 py-5 bg-slate-950 border-b border-slate-800">
                    <span class="text-md font-black tracking-widest text-indigo-400 block">APARTMENT ADMIN</span>
                    <span class="text-xs text-slate-500 font-medium">Management Portal</span>
                </div>
                
                <!-- Navigation Menu Links -->
                <nav class="mt-6 px-4 space-y-2">
                    <a href="#overview-section" id="tab-overview" class="dashboard-tab flex items-center px-4 py-3 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md transition duration-200">
                        <span class="mr-3">📊</span> Overview Dashboard
                    </a>
                    <a href="#requests-section" id="tab-requests" class="dashboard-tab flex items-center justify-between w-full px-4 py-3 text-sm font-semibold text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition duration-200">
                        <span class="flex items-center"><span class="mr-3">🔧</span> Service Requests</span>
                        @php $serviceBadgeCount = $pendingRequestsCount ?? \App\Models\ServiceRequest::where('status', 'Pending')->count(); @endphp
                        @if((int) $serviceBadgeCount > 0)
                            <span class="ml-auto bg-rose-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $serviceBadgeCount }}</span>
                        @endif
                    </a>
                    <a href="#visitors-section" id="tab-visitors" class="dashboard-tab flex items-center justify-between w-full px-4 py-3 text-sm font-semibold text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition duration-200">
                        <span class="flex items-center"><span class="mr-3">👥</span> Visitor Logs</span>
                        @php $visitorBadgeCount = $visitorsCount ?? 0; @endphp
                        @if((int) $visitorBadgeCount > 0)
                            <span class="ml-auto bg-sky-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $visitorBadgeCount }}</span>
                        @endif
                    </a>
                    <a href="#manage-section" id="tab-manage" class="dashboard-tab flex items-center justify-between w-full px-4 py-3 text-sm font-semibold text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition duration-200">
                        <span class="flex items-center"><span class="mr-3">✏️</span> Manage Records</span>
                        @php $bookingBadgeCount = $bookingsCount ?? \App\Models\Booking::where('status', 'Pending')->count(); @endphp
                        @if((int) $bookingBadgeCount > 0)
                            <span class="ml-auto bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $bookingBadgeCount }}</span>
                        @endif
                    </a>
                </nav>
            </div>

            <!-- Logout & User Info Footer -->
            <div class="p-4 bg-slate-950 border-t border-slate-800">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-slate-400 font-bold tracking-wide truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] bg-indigo-900 text-indigo-200 px-2 py-0.5 rounded font-extrabold uppercase">Admin</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-1.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded transition duration-200">
                        🚪 Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- 📊 2. MAIN CONTENT AREA (පළල සීමා කර මැදට සකස් කළ කොටස) -->
        <main class="w-full min-w-0 flex-1 bg-slate-100 p-8 md:ml-64">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                
                <div id="overview-section">
                    <!-- Dashboard Top Bar Header -->
                    <div class="mb-8 flex items-center justify-between border-b border-slate-200 pb-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Operations Center</p>
                        <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900">Admin Dashboard Overview</h1>
                    </div>
                    </div>

                <!-- 🗂️ 3. SIDE-BY-SIDE COMPACT STATISTICS CARDS (කාඩ්පත් 3 පේළියට එක ළඟ) -->
                    <div class="mb-8 grid max-w-6xl grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Card 1 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between transition duration-200 hover:shadow-md">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Visitors Today</p>
                            <h3 class="mt-2 text-3xl font-black text-gray-900">{{ $todayVisitors }}</h3>
                        </div>
                        <span class="text-xs font-bold text-green-700 bg-green-100 px-2.5 py-1 rounded-full">● Live</span>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between transition duration-200 hover:shadow-md">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Active Bookings</p>
                            <h3 class="mt-2 text-3xl font-black text-gray-900">{{ $activeBookings }}</h3>
                        </div>
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-100 px-2.5 py-1 rounded-full">● On Track</span>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between transition duration-200 hover:shadow-md">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Pending Requests</p>
                            <h3 class="mt-2 text-3xl font-black text-gray-900">{{ $pendingRequests }}</h3>
                        </div>
                        <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">● Review</span>
                    </div>
                    </div>

                <!-- 🛠️ 4. RECENT ACTIVITY TABLE SECTION (සීමිත පළලකින් යුත් ලස්සන කාඩ්පතක්) -->
                    <div class="max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-5 flex items-center justify-between border-b border-slate-100 pb-3"><div><p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Latest activity</p><h2 class="mt-1 text-lg font-black text-gray-800">Recent Booking Activity</h2></div><span class="text-xs font-semibold text-slate-400">Live data</span></div>
                        @forelse($bookings->take(3) as $booking)
                            <div class="mb-3 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 last:mb-0"><div><span class="block text-sm font-extrabold text-indigo-600">{{ $booking->facility_name }}</span><span class="mt-1 block text-xs font-medium text-gray-500">Resident: {{ $booking->resident_name }} · {{ $booking->booking_date }}</span></div><span class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700">⏰ {{ $booking->time_slot }}</span></div>
                        @empty
                            <p class="py-6 text-sm text-slate-500">No facility bookings found.</p>
                        @endforelse
                    </div>
                </div>

                <div id="requests-section" class="hidden">
                    <div class="flex justify-between items-center mb-8 border-b pb-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Operations Center</p>
                            <h1 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5">Active Service Requests</h1>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-6xl overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-slate-600">Resident</th>
                                    <th class="px-4 py-3 text-left font-bold text-slate-600">Category</th>
                                    <th class="px-4 py-3 text-left font-bold text-slate-600">Description</th>
                                    <th class="px-4 py-3 text-left font-bold text-slate-600">Status</th>
                                    <th class="px-4 py-3 text-right font-bold text-slate-600">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($serviceRequests as $request)
                                    <tr>
                                        <td class="px-4 py-3 text-slate-700">{{ $request->resident_name }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $request->category }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $request->description }}</td>
                                        <td class="px-4 py-3">
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">{{ $request->status }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="{{ route('admin.store_service_status', $request->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                                @csrf
                                                <select name="status" class="rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs font-semibold text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                                    <option value="Pending" {{ $request->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="In Progress" {{ $request->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="Resolved" {{ $request->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                                </select>
                                                <button type="submit" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-indigo-700">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">No service requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="visitors-section" class="hidden">
                    <div class="flex justify-between items-center mb-8 border-b pb-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Security & Access</p>
                            <h1 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5">Visitor Logs</h1>
                        </div>
                    </div>

                    <div class="max-w-6xl overflow-hidden rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Visitor</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Phone</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Vehicle</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Flat</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Status</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Check In</th>
                                        <th class="px-3 py-3 text-left font-bold text-slate-600">Check Out</th>
                                        <th class="px-3 py-3 text-right font-bold text-slate-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($visitors as $visitor)
                                        <tr>
                                            <td class="px-3 py-3 font-semibold text-slate-700">{{ $visitor->visitor_name }}</td>
                                            <td class="px-3 py-3 text-slate-600">{{ $visitor->phone }}</td>
                                            <td class="px-3 py-3 text-slate-600">{{ $visitor->vehicle_number ?: 'N/A' }}</td>
                                            <td class="px-3 py-3 text-slate-600">{{ $visitor->flat_number }}</td>
                                            <td class="px-3 py-3"><span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-700">{{ $visitor->status }}</span></td>
                                            <td class="whitespace-nowrap px-3 py-3 text-slate-600">{{ $visitor->check_in_time ? \Carbon\Carbon::parse($visitor->check_in_time)->format('Y-m-d H:i') : 'N/A' }}</td>
                                            <td class="whitespace-nowrap px-3 py-3 text-slate-600">{{ $visitor->check_out_time ? \Carbon\Carbon::parse($visitor->check_out_time)->format('Y-m-d H:i') : 'N/A' }}</td>
                                            <td class="px-3 py-3 text-right"><details class="inline-block text-left"><summary class="cursor-pointer rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white">Edit</summary><form action="{{ route('admin.update_visitor', $visitor->id) }}" method="POST" class="mt-2 w-64 space-y-2 rounded-lg border bg-white p-3 shadow-lg">@csrf @method('PATCH')<input name="visitor_name" value="{{ $visitor->visitor_name }}" required class="w-full rounded border p-2 text-xs"><input name="phone" value="{{ $visitor->phone }}" required class="w-full rounded border p-2 text-xs"><input name="vehicle_number" value="{{ $visitor->vehicle_number }}" class="w-full rounded border p-2 text-xs"><input name="flat_number" value="{{ $visitor->flat_number }}" required class="w-full rounded border p-2 text-xs"><select name="status" class="w-full rounded border p-2 text-xs"><option {{ $visitor->status === 'Pre-registered' ? 'selected' : '' }}>Pre-registered</option><option {{ $visitor->status === 'Checked-In' ? 'selected' : '' }}>Checked-In</option><option {{ $visitor->status === 'Checked-Out' ? 'selected' : '' }}>Checked-Out</option></select><button class="w-full rounded bg-blue-600 px-2 py-1 text-xs font-bold text-white">Save</button></form></details><form action="{{ route('admin.destroy_visitor', $visitor->id) }}" method="POST" class="ml-1 inline" onsubmit="return confirm('Remove this visitor?');">@csrf @method('DELETE')<button class="rounded bg-red-600 px-3 py-1.5 text-xs font-bold text-white">Remove</button></form></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="px-3 py-6 text-center text-slate-500">No visitor logs found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="manage-section" class="hidden">
                    <div class="flex items-center justify-between mb-8 border-b pb-4">
                        <div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Administration</p><h1 class="text-2xl font-black text-slate-800 tracking-tight mt-0.5">Manage Records</h1></div>
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">Full access</span>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div class="mb-4 border-b pb-3"><p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Amenities</p><h2 class="mt-1 text-lg font-black text-gray-800">Facility Bookings</h2></div>
                            <div class="space-y-3">
                                @forelse($bookings as $booking)
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4"><div class="flex items-start justify-between gap-3"><div><p class="font-bold text-slate-800">{{ $booking->facility_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $booking->resident_name }} · {{ $booking->booking_date }} · {{ $booking->time_slot }}</p></div><span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-bold text-blue-700">{{ $booking->status }}</span></div><div class="mt-3 flex gap-2"><details><summary class="cursor-pointer rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white">Edit</summary><form action="{{ route('admin.update_booking', $booking->id) }}" method="POST" class="mt-2 w-64 space-y-2 rounded-lg border bg-white p-3 shadow-lg">@csrf @method('PATCH')<select name="facility_name" class="w-full rounded border p-2 text-xs"><option {{ $booking->facility_name === 'Gym' ? 'selected' : '' }}>Gym</option><option {{ $booking->facility_name === 'Swimming Pool' ? 'selected' : '' }}>Swimming Pool</option><option {{ $booking->facility_name === 'Event Hall' ? 'selected' : '' }}>Event Hall</option></select><input type="date" name="booking_date" value="{{ $booking->booking_date }}" required class="w-full rounded border p-2 text-xs"><input name="time_slot" value="{{ $booking->time_slot }}" required class="w-full rounded border p-2 text-xs"><select name="status" class="w-full rounded border p-2 text-xs"><option {{ $booking->status === 'Approved' ? 'selected' : '' }}>Approved</option><option {{ $booking->status === 'Pending' ? 'selected' : '' }}>Pending</option><option {{ $booking->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option></select><button class="w-full rounded bg-blue-600 px-2 py-1 text-xs font-bold text-white">Save</button></form></details><form action="{{ route('admin.destroy_booking', $booking->id) }}" method="POST" onsubmit="return confirm('Remove this booking?');">@csrf @method('DELETE')<button class="rounded bg-red-600 px-3 py-1.5 text-xs font-bold text-white">Remove</button></form></div></div>
                                @empty
                                    <p class="text-sm text-slate-500">No facility bookings found.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div class="mb-4 border-b pb-3"><p class="text-xs font-bold uppercase tracking-wider text-amber-600">Operations</p><h2 class="mt-1 text-lg font-black text-gray-800">Service Requests</h2></div>
                            <div class="space-y-3">
                                @forelse($serviceRequests as $request)
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4"><div class="flex items-start justify-between gap-3"><div><p class="font-bold text-slate-800">{{ $request->category }}</p><p class="mt-1 text-xs text-slate-500">{{ $request->resident_name }} · {{ \Carbon\Carbon::parse($request->created_at)->format('Y-m-d') }}</p></div><span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-bold text-amber-700">{{ $request->status }}</span></div><div class="mt-3 flex gap-2"><details><summary class="cursor-pointer rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white">Edit</summary><form action="{{ route('admin.update_service_request', $request->id) }}" method="POST" class="mt-2 w-64 space-y-2 rounded-lg border bg-white p-3 shadow-lg">@csrf @method('PATCH')<select name="category" class="w-full rounded border p-2 text-xs"><option>{{ $request->category }}</option><option>Plumbing</option><option>Electrical</option><option>Carpentry</option><option>Cleaning</option><option>Security</option></select><textarea name="description" required class="w-full rounded border p-2 text-xs">{{ $request->description }}</textarea><select name="status" class="w-full rounded border p-2 text-xs"><option {{ $request->status === 'Pending' ? 'selected' : '' }}>Pending</option><option {{ $request->status === 'In Progress' ? 'selected' : '' }}>In Progress</option><option {{ $request->status === 'Resolved' ? 'selected' : '' }}>Resolved</option></select><button class="w-full rounded bg-blue-600 px-2 py-1 text-xs font-bold text-white">Save</button></form></details><form action="{{ route('admin.destroy_service_request', $request->id) }}" method="POST" onsubmit="return confirm('Remove this service request?');">@csrf @method('DELETE')<button class="rounded bg-red-600 px-3 py-1.5 text-xs font-bold text-white">Remove</button></form></div></div>
                                @empty
                                    <p class="text-sm text-slate-500">No service requests found.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="hidden rounded-xl border border-gray-100 bg-white p-6 shadow-sm xl:col-span-2">
                            <div class="mb-4 border-b pb-3"><p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Security</p><h2 class="mt-1 text-lg font-black text-gray-800">Visitors</h2></div>
                            <div class="space-y-3">
                                @forelse($visitors as $visitor)
                                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50 p-4"><div><p class="font-bold text-slate-800">{{ $visitor->visitor_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $visitor->phone }} · Flat {{ $visitor->flat_number }} · {{ $visitor->status }}</p></div><div class="flex gap-2"><details><summary class="cursor-pointer rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white">Edit</summary><form action="{{ route('admin.update_visitor', $visitor->id) }}" method="POST" class="mt-2 w-64 space-y-2 rounded-lg border bg-white p-3 shadow-lg">@csrf @method('PATCH')<input name="visitor_name" value="{{ $visitor->visitor_name }}" required class="w-full rounded border p-2 text-xs"><input name="phone" value="{{ $visitor->phone }}" required class="w-full rounded border p-2 text-xs"><input name="vehicle_number" value="{{ $visitor->vehicle_number }}" class="w-full rounded border p-2 text-xs"><input name="flat_number" value="{{ $visitor->flat_number }}" required class="w-full rounded border p-2 text-xs"><select name="status" class="w-full rounded border p-2 text-xs"><option {{ $visitor->status === 'Pre-registered' ? 'selected' : '' }}>Pre-registered</option><option {{ $visitor->status === 'Checked-In' ? 'selected' : '' }}>Checked-In</option><option {{ $visitor->status === 'Checked-Out' ? 'selected' : '' }}>Checked-Out</option></select><button class="w-full rounded bg-blue-600 px-2 py-1 text-xs font-bold text-white">Save</button></form></details><form action="{{ route('admin.destroy_visitor', $visitor->id) }}" method="POST" onsubmit="return confirm('Remove this visitor?');">@csrf @method('DELETE')<button class="rounded bg-red-600 px-3 py-1.5 text-xs font-bold text-white">Remove</button></form></div></div>
                                @empty
                                    <p class="text-sm text-slate-500">No visitors found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.dashboard-tab');
            const sections = document.querySelectorAll('#overview-section, #requests-section, #visitors-section, #manage-section');

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function (event) {
                    event.preventDefault();

                    const targetId = tab.getAttribute('href').substring(1);

                    sections.forEach(function (section) {
                        section.classList.add('hidden');
                    });

                    tabs.forEach(function (item) {
                        item.classList.remove('bg-indigo-600', 'text-white', 'shadow-md');
                        item.classList.add('text-slate-300');
                    });

                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }

                    tab.classList.add('bg-indigo-600', 'text-white', 'shadow-md');
                    tab.classList.remove('text-slate-300');
                });
            });
        });
    </script>

</body>
</html>
