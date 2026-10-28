<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Apartment Management System') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 font-sans antialiased flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md bg-slate-900 border border-slate-700 rounded-2xl p-8 shadow-2xl space-y-6">
            <div class="text-center">
                <span class="px-3 py-1 text-xs font-bold text-sky-300 bg-sky-400/10 rounded-full uppercase tracking-wider inline-block">ELITE LIVING</span>
                <h1 class="mt-5 text-2xl font-semibold text-slate-100">Create your account</h1>
                <p class="mt-2 text-sm text-slate-400">Verified Gmail: {{ $email }}</p>
            </div>

            @if (session('status'))
                <p class="text-sm text-emerald-400" role="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <div class="text-sm text-red-400" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 tracking-wider">NAME</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full mt-1.5 px-4 py-3 bg-slate-950 border border-slate-700 text-slate-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="flat_number" class="block text-xs font-semibold text-slate-400 tracking-wider">FLAT</label>
                    <select id="flat_number" name="flat_number" required class="w-full mt-1.5 px-4 py-3 bg-slate-950 border border-slate-700 text-slate-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">Select your flat</option>
                        @foreach($flats as $flat)
                            <option value="{{ $flat->flat_number }}" {{ old('flat_number') === $flat->flat_number ? 'selected' : '' }}>{{ $flat->flat_number }} · Block {{ $flat->block }} · Floor {{ $flat->floor }}</option>
                        @endforeach
                    </select>
                    @error('flat_number')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 tracking-wider">PASSWORD</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full mt-1.5 px-4 py-3 bg-slate-950 border border-slate-700 text-slate-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 tracking-wider">CONFIRM PASSWORD</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full mt-1.5 px-4 py-3 bg-slate-950 border border-slate-700 text-slate-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow-lg text-sm tracking-wide hover:bg-indigo-500">Register</button>
            </form>
        </div>
    </body>
</html>
