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
                <h1 class="mt-5 text-2xl font-semibold text-slate-100">Enter your verification code</h1>
                <p class="mt-2 text-sm text-slate-400">We sent a six-digit code to {{ $email }}. It expires in 10 minutes.</p>
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

            <form method="POST" action="{{ route('register.verify.submit') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-400 tracking-wider">SIX-DIGIT CODE</label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus autocomplete="one-time-code" class="w-full mt-1.5 px-4 py-3 bg-slate-950 border border-slate-700 text-slate-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm tracking-[0.4em]">
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow-lg text-sm tracking-wide hover:bg-indigo-500">Verify and continue</button>
            </form>

            <form method="POST" action="{{ route('register.email-code') }}" class="text-center">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="text-sm text-sky-300 hover:text-sky-200">Send a new code</button>
            </form>

            <p class="text-center text-sm text-slate-400"><a href="{{ route('register') }}" class="text-sky-300 hover:text-sky-200">Use a different Gmail address</a></p>
        </div>
    </body>
</html>
