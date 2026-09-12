<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-xl bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Visitor Check-In</h1>

        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('security.visitor.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="visitor_name" class="block text-sm font-medium text-gray-700">Visitor Name</label>
                <input type="text" name="visitor_name" id="visitor_name" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone" id="phone" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="vehicle_number" class="block text-sm font-medium text-gray-700">Vehicle Number (Optional)</label>
                <input type="text" name="vehicle_number" id="vehicle_number"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="flat_number" class="block text-sm font-medium text-gray-700">Flat Number</label>
                <input type="text" name="flat_number" id="flat_number" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200">
                Check-In Visitor
            </button>
        </form>
    </div>
</body>
</html>
