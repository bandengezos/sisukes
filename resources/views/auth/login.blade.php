<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiSuKes') }} - Masuk Staff</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Scripts and CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="clinic-welcome-bg font-sans antialiased min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md space-y-6 animate-fade-in">
        
        <!-- Back Link -->
        <div class="text-center">
            <a href="{{ route('welcome') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-sky-600 transition">
                &larr; Kembali ke Portal Publik
            </a>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 space-y-6">
            
            <!-- Card Header -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 bg-sky-600 rounded-2xl flex items-center justify-center text-white font-bold shadow-md shadow-sky-200 mx-auto">
                    🏥
                </div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Masuk Sesi Staff</h2>
                <p class="text-xs text-slate-500">Gunakan akun Anda untuk masuk ke sistem dashboard manajemen.</p>
            </div>

            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="p-3 bg-sky-50 border border-sky-100 rounded-xl text-xs text-sky-700 font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                           placeholder="e.g. admin@sisukes.com">
                    @error('email')
                        <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-[10px] font-semibold text-sky-600 hover:text-sky-700 hover:underline" href="{{ route('password.request') }}">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                           class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                           placeholder="Masukkan kata sandi Anda">
                    @error('password')
                        <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" 
                           class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500 w-4 h-4">
                    <label for="remember_me" class="ml-2 text-xs font-semibold text-slate-500 select-none">Ingat Sesi Saya</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 h-11 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-sky-200">
                    Masuk Sekarang &rarr;
                </button>
            </form>

            <!-- Demo accounts helper -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-[10px] text-slate-500 leading-relaxed">
                <span class="font-bold text-slate-700 block mb-1">🔑 Demo Akun Login:</span>
                Email: <strong class="text-slate-800">admin@sisukes.com</strong> | Sandi: <strong class="text-slate-800">admin123</strong>
            </div>

        </div>

        <!-- Footer -->
        <p class="text-center text-[10px] text-slate-400">&copy; {{ date('Y') }} Klinik SiSuKes. Hak Cipta Dilindungi.</p>
    </div>

</body>
</html>
