<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Gate Pass Entry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans antialiased">

    <div class="flex min-h-screen bg-slate-100">
        
        <!-- 🛡️ 1. වම් පැත්තේ PREMIUM DARK SIDEBAR LAYER -->
        <aside class="w-64 bg-slate-900 text-white min-h-screen fixed left-0 top-0 shadow-xl flex flex-col justify-between z-20">
            <div>
                <div class="px-6 py-5 bg-slate-950 border-b border-slate-800">
                    <span class="text-md font-black tracking-widest text-indigo-400 block">GATE CONTROL</span>
                    <span class="text-xs text-slate-500 font-medium">Security Portal</span>
                </div>
                <nav class="mt-6 px-4 space-y-2">
                    <a href="#" class="flex items-center px-4 py-3 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md">
                        <span class="mr-3">🛡️</span> Security Gate Entry
                    </a>
                </nav>
            </div>
            <div class="p-4 bg-slate-950 border-t border-slate-800">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-slate-400 font-bold tracking-wide truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] bg-indigo-900 text-indigo-200 px-2 py-0.5 rounded font-extrabold uppercase">Security</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-1.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded">
                        🚪 Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- 📊 2. දකුණු පැත්තේ MAIN CONTENT AREA CONTAINER -->
        <main class="flex-1 md:ml-64 p-8">
            <div class="max-w-6xl mx-auto">
                
                @if(session('success'))
                    <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-xl font-bold shadow-sm">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <div class="flex items-center space-x-3 mb-6 border-b pb-4">
                    <div class="w-6 h-7 bg-blue-500 rounded-md transform rotate-12 flex items-center justify-center shadow-sm">
                        <span class="text-white text-xs font-bold">S</span>
                    </div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">Security Gate Pass Entry</h1>
                </div>

                <!-- PANEL 2개 나란히 배치하는 2-COLUMN GRID WRAPPER -->
                <div class="grid grid-cols-2 gap-6 mb-8 w-full items-stretch">
                    
                    <!-- Panel A: Log Walk-In Visitor Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-black text-slate-800 mb-4 tracking-tight">Log Walk-In Visitor</h2>
                        <form action="{{ route('visitor.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Visitor Name</label>
                                <input type="text" name="visitor_name" required class="mt-1 block w-full rounded-lg border-gray-200 p-2.5 bg-white border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                                <input type="text" name="phone" required class="mt-1 block w-full rounded-lg border-gray-200 p-2.5 bg-white border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Vehicle Number</label>
                                <input type="text" name="vehicle_number" class="mt-1 block w-full rounded-lg border-gray-200 p-2.5 bg-white border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Flat to Visit</label>
                                <select name="flat_number" required class="mt-1 block w-full rounded-lg border-gray-200 p-2.5 bg-white border text-gray-500 outline-none">
                                    <option value="">-- Select Flat --</option>
                                    <option value="A-102">A-102</option>
                                    <option value="B-205">B-205</option>
                                    <option value="C-301">C-301</option>
                                </select>
                            </div>
                            <div class="pt-2">
                                <button type="submit" class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition duration-200">
                                    Check-In Visitor
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Panel B: Verify Pre-Registered Visitor Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                        <div>
                            <h2 class="text-lg font-black text-slate-800 mb-4 tracking-tight">Verify Pre-Registered Visitor</h2>
                            
                            <!-- Search Section -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Search by Name</label>
                                    <input type="text" placeholder="Type visitor name..." class="mt-1 block w-full rounded-lg border-gray-200 p-2.5 bg-white border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                </div>
                                <button type="button" class="py-2 px-5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition duration-200">
                                    Search
                                </button>
                            </div>

                            <!-- Pre-Registered Table List -->
                            <div class="overflow-x-auto mt-6">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-gray-400 bg-white font-bold border-b text-xs uppercase tracking-wider">
                                        <tr>
                                            <th class="px-2 py-3">Name</th>
                                            <th class="px-2 py-3">Flat</th>
                                            <th class="px-2 py-3 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 font-medium text-gray-800">
                                        @if(isset($preRegisteredVisitors) && count($preRegisteredVisitors) > 0)
                                            @foreach($preRegisteredVisitors as $preVisitor)
                                            <tr>
                                                <td class="px-2 py-4 font-bold text-slate-700">{{ $preVisitor->visitor_name }}</td>
                                                <td class="px-2 py-4">{{ $preVisitor->flat_number }}</td>
                                                <td class="px-2 py-4 text-right">
                                                    <form action="{{ route('visitor.checkin', $preVisitor->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-1.5 rounded-lg text-xs shadow-sm transition">
                                                            Check-In
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="px-2 py-4 font-bold text-slate-700">Saman Kumara</td>
                                                <td class="px-2 py-4">B-205</td>
                                                <td class="px-2 py-4 text-right">
                                                    <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-1.5 rounded-lg text-xs shadow-sm transition">
                                                        Check-In
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- Panel B Ends -->

                </div> <!-- Grid Container Ends -->

                <!-- Currently Inside (Active Visitors) Full-Width Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4 tracking-tight">Currently Inside (Active Visitors)</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-gray-400 bg-white font-bold border-b text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Flat</th>
                                    <th class="px-4 py-3">Vehicle</th>
                                    <th class="px-4 py-3">Check-In Time</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 font-medium text-gray-800">
                                @if(isset($activeVisitors) && count($activeVisitors) > 0)
                                    @foreach($activeVisitors as $activeVisitor)
                                    <tr>
                                        <td class="px-4 py-4 font-bold text-slate-700">{{ $activeVisitor->visitor_name }}</td>
                                        <td class="px-4 py-4">{{ $activeVisitor->flat_number }}</td>
                                        <td class="px-4 py-4">{{ $activeVisitor->vehicle_number ?? 'N/A' }}</td>
                                        <td class="px-4 py-4 text-gray-400">{{ $activeVisitor->check_in_time }}</td>
                                        <td class="px-4 py-4 text-right">
                                            <form action="{{ route('visitor.checkout', $activeVisitor->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-1.5 rounded-lg text-xs shadow-sm transition">
                                                    Check-Out
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="px-4 py-4 font-bold text-slate-700">Kamal Perera</td>
                                        <td class="px-4 py-4">A-102</td>
                                        <td class="px-4 py-4 text-slate-700">WP CAS-1234</td>
                                        <td class="px-4 py-4 text-gray-400">2026-09-12 19:24:20</td>
                                        <td class="px-4 py-4 text-right">
                                            <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-1.5 rounded-lg text-xs shadow-sm transition">
                                                Check-Out
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Project Tracking Note -->
                <div class="mt-8 text-center text-xs text-gray-400 font-semibold tracking-wide">
                    ICT2232 Software Engineering — Group 8 Mini Project
                </div>

            </div>
        </main>
    </div>

</body>
</html>
