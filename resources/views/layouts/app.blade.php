<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiSuKes') }} - Dashboard Staff</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Scripts and CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sky-50/50 font-sans antialiased">
    <div class="min-h-screen flex flex-col md:flex-row" x-data="{ sidebarOpen: false }">
        
        <!-- Mobile Sidebar Toggle -->
        <div class="md:hidden bg-slate-900 text-white flex justify-between items-center px-4 h-16 border-b border-slate-800 sticky top-0 z-50">
            <div class="flex items-center space-x-2">
                <span class="text-xl">🏥</span>
                <span class="font-bold tracking-tight text-white">SiSuKes Staff</span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-800 transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
            </button>
        </div>

        <!-- Sidebar -->
        <aside class="w-full md:w-64 sisu-sidebar text-white flex flex-col justify-between fixed md:sticky top-0 h-[calc(100vh-4rem)] md:h-screen z-40 transition-all duration-300 transform"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            
            <div class="flex flex-col">
                <!-- Sidebar Header -->
                <div class="h-16 px-6 hidden md:flex items-center space-x-3 border-b border-sky-900/30">
                    <span class="text-2xl">🏥</span>
                    <div>
                        <span class="text-base font-extrabold text-white block tracking-tight leading-none">SiSuKes Admin</span>
                        <span class="text-[10px] text-sky-300 font-medium">Dashboard Klinik</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold sisu-sidebar-link {{ Route::is('dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Complaints -->
                    <a href="{{ route('complaints.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold sisu-sidebar-link {{ Route::is('complaints.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Laporan Pengaduan</span>
                    </a>

                    <!-- Surveys -->
                    <a href="{{ route('surveys.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold sisu-sidebar-link {{ Route::is('surveys.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span>Kelola Survey</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer / Profile -->
            <div class="p-4 border-t border-sky-900/30 bg-slate-950/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-sky-500 flex items-center justify-center text-sm font-bold text-white uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="truncate max-w-[120px]">
                            <span class="text-xs font-bold block truncate leading-none text-white">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-sky-300 font-medium capitalize">{{ Auth::user()->role }}</span>
                        </div>
                    </div>
                </div>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 h-9 bg-rose-600/10 hover:bg-rose-600 text-rose-500 hover:text-white text-xs font-bold rounded-lg transition">
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="bg-white border-b border-slate-200 h-16 px-6 hidden md:flex justify-between items-center sticky top-0 z-30">
                <h1 class="text-lg font-bold text-slate-800">
                    @yield('title', 'Dashboard')
                </h1>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('welcome') }}" target="_blank" class="text-xs font-semibold text-sky-600 hover:text-sky-700 transition">
                        Lihat Portal Publik &nearr;
                    </a>
                    <div class="w-px h-6 bg-slate-200"></div>
                    <span class="text-xs font-medium text-slate-500">Hari ini: {{ date('d F Y') }}</span>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-grow p-6 animate-fade-in">
                @if(session('success'))
                    <!-- Success Alert -->
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center space-x-3 text-emerald-800 text-sm font-medium">
                        <span>✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <!-- Error Alert -->
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center space-x-3 text-rose-800 text-sm font-medium">
                        <span>⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
