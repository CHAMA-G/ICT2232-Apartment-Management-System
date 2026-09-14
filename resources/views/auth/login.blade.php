<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Apartment Management System') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 font-sans antialiased flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md bg-slate-900 border border-slate-700 rounded-2xl p-8 shadow-2xl space-y-6 transition-all duration-700 ease-out transform translate-y-0 opacity-100 animate-in fade-in slide-in-from-bottom-8 duration-500" style="max-width: 28rem;">
            <div class="text-center">
                <span class="px-3 py-1 text-xs font-bold text-sky-300 bg-sky-400/10 rounded-full uppercase tracking-wider inline-block">ELITE LIVING</span>
                <h1 class="mt-5 text-2xl font-semibold text-slate-100">Welcome back</h1>
                <p class="mt-2 text-sm text-slate-400">Sign in to manage your apartment.</p>
            </div>

            <x-auth-session-status class="mb-2" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 tracking-wider">EMAIL</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full mt-1.5 px-4 py-3 bg-slate-950 border border-slate-700 text-slate-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all duration-300 focus:scale-[1.01] focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-inner">
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 tracking-wider">PASSWORD</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full mt-1.5 px-4 py-3 bg-slate-950 border border-slate-700 text-slate-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all duration-300 focus:scale-[1.01] focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-inner">
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center text-sm text-slate-300">
                        <input id="remember_me" type="checkbox" class="rounded border-slate-700 bg-slate-950 text-sky-500 shadow-sm focus:ring-sky-500" name="remember">
                        <span class="ml-2">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-sky-300 hover:text-sky-200 transition">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow-lg text-sm tracking-wide transition-all duration-200 hover:bg-indigo-500 active:scale-95 transform hover:-translate-y-0.5">Log In</button>
            </form>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-700"></div>
                </div>
                <div class="relative flex justify-center text-[11px] uppercase tracking-[0.2em] text-slate-500">
                    <span class="bg-slate-900 px-3">Or continue with</span>
                </div>
            </div>

            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-slate-900 font-semibold rounded-xl shadow-lg text-sm tracking-wide transition-all duration-200 hover:bg-slate-100 hover:shadow-xl active:scale-[0.99] transform hover:-translate-y-0.5">
                <span aria-hidden="true">🔴</span>
                Sign in with Google
            </a>

            @if (Route::has('register'))
                <p class="text-center text-sm text-slate-400">New resident? <a href="{{ route('register') }}" class="text-sky-300 hover:text-sky-200">Create an account</a></p>
            @endif
        </div>
    </body>
</html>
