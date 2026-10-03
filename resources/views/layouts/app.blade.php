<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'e-BA IT Chasan' }} — RSUD Dr. H. Chasan Boesoirie</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logoresmi.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-pattern-3d font-sans text-slate-800 antialiased selection:bg-sky-200 selection:text-sky-900" x-data="{ sidebarOpen: false }">
    <div class="h-screen flex flex-col md:flex-row overflow-hidden">
        <!-- Mobile Header Bar -->
        <header class="md:hidden flex items-center justify-between px-4 py-3 bg-white/95 backdrop-blur-md border-b border-sky-100 shadow-sm shrink-0 z-30">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/logoresmi.png') }}" alt="Logo RSUD Dr. H. Chasan Boesoirie" class="w-10 h-10 object-contain drop-shadow-sm shrink-0">
                <div>
                    <span class="font-extrabold text-slate-800 text-base tracking-tight">e-BA <span class="text-sky-600">Chasan</span></span>
                    <span class="block text-[10px] text-slate-500 font-medium">Ruang IT RSUD</span>
                </div>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl border border-sky-200 bg-sky-50 text-sky-700 hover:bg-sky-100 transition shadow-sm" aria-label="Toggle Navigation">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </header>

        <!-- Mobile Sidebar Backdrop Overlay -->
        <div 
            x-show="sidebarOpen" 
            x-cloak
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden transition-opacity"
        ></div>

        <!-- Sidebar Navigation (Desktop Fixed & Mobile Drawer) -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed md:static inset-y-0 left-0 z-50 w-72 md:h-screen md:shrink-0 bg-white/95 md:bg-white/80 backdrop-blur-md border-r border-sky-100 flex flex-col transition-transform duration-300 ease-in-out shadow-lg md:shadow-none"
        >
            <!-- Sidebar Header -->
            <div class="p-5 border-b border-sky-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo/logoresmi.png') }}" alt="Logo RSUD Dr. H. Chasan Boesoirie" class="w-11 h-11 object-contain drop-shadow-sm shrink-0">
                    <div>
                        <div class="font-black text-slate-800 text-lg tracking-tight">e-BA <span class="text-sky-600">Chasan</span></div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">RSUD Dr. H. Chasan B.</div>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- User Info Card in Sidebar -->
            <div class="px-4 pt-4 shrink-0">
                <div class="p-3.5 card-3d bg-gradient-to-b from-sky-50/70 to-white flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-sky-400 text-white font-black text-sm flex items-center justify-center shadow-md shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        <span class="badge-3d bg-sky-100 text-sky-800 text-[10px] py-0.5 px-2 mt-0.5">
                            {{ auth()->user()->role->label() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Dedicated Primary Action Button -->
            <div class="px-4 pt-4 pb-2 shrink-0">
                <a href="{{ route('berita-acara.create') }}" class="btn-3d-primary w-full py-2.5 text-xs shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Buat Berita Acara</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-3 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" 
                   class="{{ request()->routeIs('dashboard') 
                       ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-sky-50 text-sky-800 border border-sky-200/80 shadow-sm' 
                       : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-sky-50/60 hover:text-sky-700 transition' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-sky-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-xs">Dashboard</span>
                </a>

                <div class="pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">
                    Manajemen & Audit
                </div>

                <a href="{{ route('audit-kebutuhan.index') }}" 
                   class="{{ request()->routeIs('audit-kebutuhan.*') 
                       ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-sky-50 text-sky-800 border border-sky-200/80 shadow-sm' 
                       : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-sky-50/60 hover:text-sky-700 transition' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('audit-kebutuhan.*') ? 'text-sky-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span class="text-xs">{{ auth()->user()->isVendor() ? 'Pantau Audit Ruangan' : 'Audit Kebutuhan User' }}</span>
                </a>

                <a href="{{ route('berita-acara.index') }}" 
                   class="{{ request()->routeIs('berita-acara.index', 'berita-acara.show', 'berita-acara.edit') 
                       ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-sky-50 text-sky-800 border border-sky-200/80 shadow-sm' 
                       : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-sky-50/60 hover:text-sky-700 transition' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('berita-acara.index', 'berita-acara.show', 'berita-acara.edit') ? 'text-sky-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-xs">Berita Acara & Vendor</span>
                </a>

                <a href="{{ route('berita-acara.archive') }}" 
                   class="{{ request()->routeIs('berita-acara.archive') 
                       ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-sky-50 text-sky-800 border border-sky-200/80 shadow-sm' 
                       : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-sky-50/60 hover:text-sky-700 transition' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('berita-acara.archive') ? 'text-sky-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    <span class="text-xs">Arsip Permanen</span>
                </a>

                @if (auth()->user()->isSuperadmin())
                    <div class="pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">
                        Administrasi Superadmin
                    </div>

                    <a href="{{ route('categories.index') }}" 
                       class="{{ request()->routeIs('categories.*') 
                           ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-sky-50 text-sky-800 border border-sky-200/80 shadow-sm' 
                           : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-sky-50/60 hover:text-sky-700 transition' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('categories.*') ? 'text-sky-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span class="text-xs">Kategori IT</span>
                    </a>

                    <a href="{{ route('users.index') }}" 
                       class="{{ request()->routeIs('users.*') 
                           ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-sky-50 text-sky-800 border border-sky-200/80 shadow-sm' 
                           : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-sky-50/60 hover:text-sky-700 transition' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-sky-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="text-xs">Manajemen Pengguna</span>
                    </a>

                    <a href="{{ route('audit-logs.index') }}" 
                       class="{{ request()->routeIs('audit-logs.*') 
                           ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-sky-50 text-sky-800 border border-sky-200/80 shadow-sm' 
                           : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-sky-50/60 hover:text-sky-700 transition' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('audit-logs.*') ? 'text-sky-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="text-xs">Audit Log</span>
                    </a>
                @endif
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-sky-100 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-3d-light w-full py-2.5 text-xs text-rose-600 border-rose-200 hover:bg-rose-50 hover:text-rose-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area (Independently Scrollable) -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <!-- Top App Bar -->
            <div class="hidden md:flex items-center justify-between px-8 py-3.5 bg-white/70 backdrop-blur-md border-b border-sky-100 shrink-0">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">{{ $heading ?? 'Dashboard' }}</h2>
                    <p class="text-[11px] text-slate-400">Audit Kebutuhan & Rekanan Vendor — Ruang IT RSUD Dr. H. Chasan Boesoirie</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="badge-3d bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px]">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                        Sistem Siap Operasi
                    </span>
                    <div class="h-5 w-px bg-slate-200 mx-1"></div>
                    <span class="text-xs font-semibold text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            <!-- Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Mobile Bottom Navigation Bar (Visible only on mobile) -->
            <nav class="md:hidden shrink-0 bg-white/95 backdrop-blur-md border-t border-sky-100 px-3 py-1.5 shadow-[0_-4px_20px_rgba(14,165,233,0.08)] z-30">
                <div class="flex items-center justify-around relative">
                    <!-- 1. Beranda / Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex flex-col items-center justify-center py-1 px-2 rounded-xl transition {{ request()->routeIs('dashboard') ? 'text-sky-600 font-bold' : 'text-slate-400 hover:text-slate-600 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-sky-600 stroke-[2.5]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-[10px] mt-0.5">Beranda</span>
                    </a>

                    <!-- 2. Berita Acara -->
                    <a href="{{ route('berita-acara.index') }}" 
                       class="flex flex-col items-center justify-center py-1 px-2 rounded-xl transition {{ request()->routeIs('berita-acara.index', 'berita-acara.show', 'berita-acara.edit') ? 'text-sky-600 font-bold' : 'text-slate-400 hover:text-slate-600 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('berita-acara.index', 'berita-acara.show', 'berita-acara.edit') ? 'text-sky-600 stroke-[2.5]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-[10px] mt-0.5">Audit BA</span>
                    </a>

                    <!-- 3. Tombol Buat (Tengah Elevated 3D) -->
                    <div class="flex flex-col items-center -mt-5">
                        <a href="{{ route('berita-acara.create') }}" 
                           class="w-12 h-12 rounded-full btn-3d-primary flex items-center justify-center text-white shadow-lg border-2 border-white transition-transform active:scale-95"
                           aria-label="Buat Berita Acara Baru">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        <span class="text-[9px] font-bold text-sky-700 mt-0.5">Buat BA</span>
                    </div>

                    <!-- 4. Audit Ruangan / Kebutuhan User -->
                    <a href="{{ route('audit-kebutuhan.index') }}" 
                       class="flex flex-col items-center justify-center py-1 px-2 rounded-xl transition {{ request()->routeIs('audit-kebutuhan.*') ? 'text-sky-600 font-bold' : 'text-slate-400 hover:text-slate-600 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('audit-kebutuhan.*') ? 'text-sky-600 stroke-[2.5]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span class="text-[10px] mt-0.5">{{ auth()->user()->isVendor() ? 'Pantau Audit' : 'Audit User' }}</span>
                    </a>

                    <!-- 5. Menu Lainnya (Buka Drawer) -->
                    <button type="button" 
                            @click="sidebarOpen = true" 
                            class="flex flex-col items-center justify-center py-1 px-2 rounded-xl text-slate-400 hover:text-slate-600 font-medium transition">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="text-[10px] mt-0.5">Menu</span>
                    </button>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
