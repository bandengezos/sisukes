<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiSuKes') }} - Layanan Pasien Klinik</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Scripts and CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="clinic-welcome-bg font-sans antialiased min-h-screen flex flex-col justify-between">
    
    <!-- Header / Navbar -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-sky-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-sky-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md shadow-sky-200">
                            🏥
                        </div>
                        <div>
                            <span class="text-lg font-bold text-slate-800 tracking-tight block leading-none">SiSuKes</span>
                            <span class="text-xs text-sky-600 font-medium">Klinik & Rumah Sakit</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('welcome') }}" class="text-sm font-medium text-slate-600 hover:text-sky-600 transition">Beranda</a>
                    <a href="{{ route('pengaduan.create') }}" class="text-sm font-medium text-slate-600 hover:text-sky-600 transition">Pengaduan</a>
                    <a href="{{ route('survey.fill') }}" class="text-sm font-medium text-slate-600 hover:text-sky-600 transition">Isi Survey</a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 h-9 bg-sky-50 text-sky-700 text-xs font-semibold rounded-lg hover:bg-sky-100 transition border border-sky-200">
                            Dashboard Staff
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 h-9 bg-sky-600 text-white text-xs font-semibold rounded-lg hover:bg-sky-700 transition shadow-sm">
                            Masuk Staff
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 py-8 border-t border-slate-900 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-white font-bold">SiSuKes</span>
                <span class="text-xs text-slate-600">|</span>
                <span class="text-xs">Sistem Survey & Pengaduan Kesehatan Klinik</span>
            </div>
            <p class="text-xs">&copy; {{ date('Y') }} Klinik SiSuKes. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </footer>
</body>
</html>
