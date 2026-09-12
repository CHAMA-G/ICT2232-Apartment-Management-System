<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Security Gate Control - Visitor Check-In') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Success Message එක පෙන්වන කොටස -->
                @if(session('success'))
                    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <h3 class="text-lg font-medium text-gray-900 mb-6 text-center border-b pb-2">Visitor Check-In Form</h3>

                @if(session('success'))
                    <div class="mb-4 p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-gray-800 dark:text-green-400 font-medium" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('visitor.store') }}" method="POST" class="space-y-4 pb-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Visitor Name</label>
                        <input type="text" name="visitor_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vehicle Number (Optional)</label>
                        <input type="text" name="vehicle_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Flat Number</label>
                        <input type="text" name="flat_number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="pt-2 pb-4">
                        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-md shadow-lg text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            Check-In Visitor
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
