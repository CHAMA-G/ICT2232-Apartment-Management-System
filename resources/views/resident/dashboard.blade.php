<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resident Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pre-Register Visitor</h3>

                    @if($errors->any())
                        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('resident.pre_register_visitor') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="visitor_name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="visitor_name" name="visitor_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" id="phone" name="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="expected_date" class="block text-sm font-medium text-gray-700">Expected Date</label>
                            <input type="date" id="expected_date" name="expected_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 font-semibold transition">
                            Pre-Register Visitor
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Book a Facility</h3>

                    @if(session('booking_success'))
                        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                            {{ session('booking_success') }}
                        </div>
                    @endif

                    <form action="{{ route('resident.book_facility') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="facility_name" class="block text-sm font-medium text-gray-700">Facility</label>
                            <select id="facility_name" name="facility_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select facility</option>
                                <option value="Gym">Gym</option>
                                <option value="Swimming Pool">Swimming Pool</option>
                                <option value="Event Hall">Event Hall</option>
                            </select>
                        </div>

                        <div>
                            <label for="booking_date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" id="booking_date" name="booking_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="time_slot" class="block text-sm font-medium text-gray-700">Time Slot</label>
                            <select id="time_slot" name="time_slot" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select time slot</option>
                                <option value="08:00 AM - 10:00 AM">08:00 AM - 10:00 AM</option>
                                <option value="10:00 AM - 12:00 PM">10:00 AM - 12:00 PM</option>
                                <option value="01:00 PM - 03:00 PM">01:00 PM - 03:00 PM</option>
                                <option value="04:00 PM - 06:00 PM">04:00 PM - 06:00 PM</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 font-semibold transition">
                            Book Facility
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-10 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Service Requests</h3>

                @if(session('service_success'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                        {{ session('service_success') }}
                    </div>
                @endif

                <form action="{{ route('resident.store_service_request') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-6">
                    @csrf
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="category" name="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select category</option>
                            <option value="Plumbing">Plumbing</option>
                            <option value="Electrical">Electrical</option>
                            <option value="Carpentry">Carpentry</option>
                            <option value="Cleaning">Cleaning</option>
                            <option value="Security">Security</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="md:col-span-3">
                        <button type="submit" class="w-full bg-amber-600 text-white py-3 px-4 rounded-md hover:bg-amber-700 font-semibold transition">
                            Submit Service Request
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700">Category</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700">Description</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $requests = DB::table('service_requests')->where('resident_id', auth()->id())->orderByDesc('created_at')->get();
                            @endphp
                            @forelse($requests as $request)
                                <tr>
                                    <td class="px-4 py-3">{{ $request->category }}</td>
                                    <td class="px-4 py-3">{{ $request->description }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                            @if($request->status === 'Pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'In Progress') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-gray-500 text-center">No service requests yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
