<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('status_success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                    {{ session('status_success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <p class="text-sm text-gray-600">Visitors Today</p>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $todayVisitors }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <p class="text-sm text-gray-600">Active Bookings</p>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $activeBookings }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <p class="text-sm text-gray-600">Pending Requests</p>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $pendingRequests }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Service Requests</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Resident</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Description</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($serviceRequests as $request)
                                <tr>
                                    <td class="px-4 py-3">{{ $request->resident_name }}</td>
                                    <td class="px-4 py-3">{{ $request->category }}</td>
                                    <td class="px-4 py-3">{{ $request->description }}</td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('admin.store_service_status', $request->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                <option value="Pending" {{ $request->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="In Progress" {{ $request->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="Resolved" {{ $request->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                            </select>
                                            <button type="submit" class="bg-indigo-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-indigo-700">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">No service requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
