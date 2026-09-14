<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Premium Apartment Management System') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-900 font-sans antialiased flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-8 sm:p-12 max-w-xl w-full shadow-2xl text-center">
            <span class="px-3 py-1 text-xs font-bold text-indigo-400 bg-indigo-500/10 rounded-full uppercase tracking-wider">
                ELITE LIVING
            </span>

            <h1 class="text-3xl font-black text-white tracking-tight mt-4">
                Apartment Management System
            </h1>

            <p class="text-sm text-slate-400 mt-2">
                Secure access portal for residents, administrators, and security staff.
            </p>

            @if (Route::has('login'))
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold rounded-xl shadow-lg transition duration-200 text-sm tracking-wide">
                            📊 Go to Dashboard Portal
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold rounded-xl shadow-lg transition duration-200 text-sm tracking-wide">
                            🔐 Log In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-extrabold rounded-xl shadow-sm transition duration-200 text-sm tracking-wide border border-slate-600">
                                👤 Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </body>
</html>
