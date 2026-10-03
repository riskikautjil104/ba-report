@extends('layouts.app', ['heading' => 'Dashboard Utama'])

@section('content')
<div class="space-y-6 w-full" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 400)">
    <!-- Welcome 3D Hero Banner -->
    <div class="card-3d p-6 sm:p-8 bg-gradient-to-r from-sky-50 via-white to-sky-100/60 border border-sky-200/80">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-100/80 text-sky-800 text-xs font-bold border border-sky-200">
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                    Sistem Audit Kebutuhan & Vendor Ruang IT RSUD Dr. H. Chasan Boesoirie
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">
                    Selamat Datang, <span class="text-sky-600">{{ $user->name }}</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
                    Aplikasi ini digunakan untuk <strong>mengaudit dan mendokumentasikan kebutuhan perbaikan/pengadaan IT dari seluruh ruangan</strong>, merumuskan spesifikasi teknis, lalu menyerahkan pekerjaan ke <strong>vendor rekanan</strong> hingga selesai dan diverifikasi secara digital.
                </p>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('berita-acara.create') }}" class="btn-3d-primary px-5 py-3 text-xs shadow-xl">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Audit Kebutuhan Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 3D Stat Cards Section with Shimmer Skeleton -->
    <div>
        <!-- SKELETON LOADING (Shimmer Wave Effect) -->
        <template x-if="loading">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @for ($i = 0; $i < 4; $i++)
                    <div class="skeleton-card space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="h-3 w-24 shimmer-skeleton rounded-md"></div>
                            <div class="w-9 h-9 rounded-xl shimmer-skeleton"></div>
                        </div>
                        <div class="h-8 w-16 shimmer-skeleton rounded-md"></div>
                        <div class="h-2.5 w-32 shimmer-skeleton rounded-md"></div>
                    </div>
                @endfor
            </div>
        </template>

        <!-- ACTUAL 3D STAT CARDS (Smoothly Revealed) -->
        <div x-show="!loading" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 transition-opacity duration-300">
            <!-- Stat 1: Total BA -->
            <a href="{{ route('berita-acara.index') }}" class="card-3d card-3d-hover p-5 bg-white block group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Audit BA</span>
                    <div class="w-10 h-10 icon-3d-sphere">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalBa }}</div>
                <div class="mt-2 text-xs text-sky-600 font-semibold flex items-center gap-1 group-hover:translate-x-0.5 transition">
                    <span>Lihat seluruh data &rarr;</span>
                </div>
            </a>

            <!-- Stat 2: Dalam Penanganan / Dikerjakan Vendor -->
            <a href="{{ route('berita-acara.index', ['status' => 'dalam_penanganan']) }}" class="card-3d card-3d-hover p-5 bg-white block group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengerjaan Vendor</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-amber-600 tracking-tight">{{ $inProgress }}</div>
                <div class="mt-2 text-xs text-slate-500 font-medium">
                    Sedang diproses / dikerjakan
                </div>
            </a>

            <!-- Stat 3: Menunggu TTD Pelapor -->
            <a href="{{ route('berita-acara.index', ['status' => 'menunggu_tanda_tangan']) }}" class="card-3d card-3d-hover p-5 bg-white block group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Verifikasi & TTD</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-sky-700 tracking-tight">{{ $waitingSign }}</div>
                <div class="mt-2 text-xs text-slate-500 font-medium">
                    Menunggu konfirmasi pelapor
                </div>
            </a>

            <!-- Stat 4: Selesai & Diserahkan -->
            <a href="{{ route('berita-acara.index', ['status' => 'selesai']) }}" class="card-3d card-3d-hover p-5 bg-white block group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Selesai & Sah</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-emerald-600 tracking-tight">{{ $completed }}</div>
                <div class="mt-2 text-xs text-slate-500 font-medium">
                    Serah terima tuntas
                </div>
            </a>
        </div>
    </div>

    <!-- 2 Column Section: Recent List & Workflow Guide -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Berita Acara (2 Cols) with Shimmer Skeleton -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card-3d p-6 bg-white space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-sky-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Daftar Audit & Kebutuhan Vendor Terkini</h2>
                        <p class="text-[11px] text-slate-400">Daftar pengajuan perbaikan dan instruksi pekerjaan ke vendor</p>
                    </div>
                    <a href="{{ route('berita-acara.index') }}" class="text-xs font-bold text-sky-700 hover:text-sky-800">
                        Buka Semua &rarr;
                    </a>
                </div>

                <!-- SKELETON FOR RECENT TABLE -->
                <template x-if="loading">
                    <div class="space-y-3">
                        @for ($s = 0; $s < 4; $s++)
                            <div class="p-3.5 rounded-xl border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="h-3 w-32 shimmer-skeleton rounded"></div>
                                    <div class="h-4 w-16 shimmer-skeleton rounded-full"></div>
                                </div>
                                <div class="h-4 w-3/4 shimmer-skeleton rounded"></div>
                                <div class="h-2.5 w-1/2 shimmer-skeleton rounded"></div>
                            </div>
                        @endfor
                    </div>
                </template>

                <!-- ACTUAL RECENT LIST -->
                <div x-show="!loading" x-cloak class="transition-opacity duration-300">
                    @if ($recentBas->isEmpty())
                        <div class="py-8 text-center text-slate-400 text-xs">
                            Belum ada Berita Acara kebutuhan yang didaftarkan.
                            <div class="mt-3">
                                <a href="{{ route('berita-acara.create') }}" class="btn-3d-primary px-4 py-2 text-xs">
                                    + Buat Audit Kebutuhan Sekarang
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach ($recentBas as $ba)
                                <a href="{{ route('berita-acara.show', $ba) }}" class="py-3.5 flex items-center justify-between gap-3 group hover:bg-sky-50/50 px-2 rounded-xl transition">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-sky-800">{{ $ba->nomor }}</span>
                                            <span class="badge-3d text-[10px] {{ $ba->status->badgeClasses() }}">{{ $ba->status->label() }}</span>
                                            @if ($ba->nama_vendor)
                                                <span class="badge-3d text-[9px] bg-slate-100 text-slate-700 border border-slate-200">
                                                    Vendor: {{ $ba->nama_vendor }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs font-bold text-slate-800 truncate mt-0.5 group-hover:text-sky-600 transition">{{ $ba->keluhan }}</p>
                                        <span class="text-[11px] text-slate-400">{{ $ba->reporter?->unit ?? '-' }} &bull; {{ $ba->lokasi }}</span>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-xs text-slate-400">{{ $ba->tanggal->format('d/m/Y') }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Alur Audit Kebutuhan & Rekanan Vendor Info Card -->
            <div class="card-3d p-6 bg-gradient-to-r from-sky-50/80 via-white to-sky-50 border border-sky-200">
                <h3 class="text-sm font-bold text-sky-900 mb-2">Alur Kerja Audit Kebutuhan & Penugasan Vendor IT</h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-white/90 border border-sky-100 shadow-sm space-y-1">
                        <span class="w-5 h-5 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-[10px]">1</span>
                        <strong class="text-slate-800 block text-xs">Audit & Identifikasi</strong>
                        <p class="text-[11px] text-slate-500">Pemeriksaan teknis keluhan unit rumah sakit & analisis kebutuhan.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/90 border border-sky-100 shadow-sm space-y-1">
                        <span class="w-5 h-5 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-[10px]">2</span>
                        <strong class="text-slate-800 block text-xs">Penugasan Vendor</strong>
                        <p class="text-[11px] text-slate-500">Spesifikasi material & pekerjaan diserahkan ke vendor rekanan.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/90 border border-sky-100 shadow-sm space-y-1">
                        <span class="w-5 h-5 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-[10px]">3</span>
                        <strong class="text-slate-800 block text-xs">Pengerjaan & Bukti</strong>
                        <p class="text-[11px] text-slate-500">Pencatatan timeline progres & unggah foto dokumentasi fisik.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/90 border border-sky-100 shadow-sm space-y-1">
                        <span class="w-5 h-5 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-[10px]">4</span>
                        <strong class="text-slate-800 block text-xs">Serah Terima & TTD</strong>
                        <p class="text-[11px] text-slate-500">Tanda tangan digital pelapor & penerbitan dokumen resmi ber-QR.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel (1 Col) -->
        <div class="space-y-6">
            <!-- User Profile Summary -->
            <div class="card-3d p-6 bg-white text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 to-sky-300 text-white text-xl font-black flex items-center justify-center mx-auto shadow-lg border-2 border-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h3 class="mt-3 text-base font-bold text-slate-800">{{ $user->name }}</h3>
                <p class="text-xs text-slate-400 font-mono">{{ $user->email }}</p>
                <div class="mt-2">
                    <span class="badge-3d bg-sky-100 text-sky-800 text-xs">
                        {{ $user->role->label() }}
                    </span>
                </div>
                <div class="mt-4 pt-4 border-t border-sky-50 text-left text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Username:</span>
                        <span class="font-bold text-slate-700">{{ $user->username }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Unit:</span>
                        <span class="font-bold text-slate-700">Ruang IT RSUD</span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Links -->
            <div class="card-3d p-6 bg-white space-y-3 text-xs">
                <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Menu Navigasi Cepat</h4>
                <div class="space-y-1.5">
                    <a href="{{ route('berita-acara.create') }}" class="block p-2.5 rounded-xl bg-sky-50/50 hover:bg-sky-100/70 text-sky-800 transition font-bold flex items-center justify-between">
                        <span>+ Buat Audit Berita Acara</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('berita-acara.index') }}" class="block p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 hover:text-sky-700 transition font-semibold text-slate-700 flex items-center justify-between">
                        <span>Daftar Berita Acara & Vendor</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('berita-acara.archive') }}" class="block p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 hover:text-sky-700 transition font-semibold text-slate-700 flex items-center justify-between">
                        <span>Arsip Dokumen Permanen</span>
                        <span>&rarr;</span>
                    </a>
                    @if (auth()->user()->isSuperadmin())
                        <a href="{{ route('categories.index') }}" class="block p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 hover:text-sky-700 transition font-semibold text-slate-700 flex items-center justify-between">
                            <span>Kelola Kategori IT</span>
                            <span>&rarr;</span>
                        </a>
                        <a href="{{ route('audit-logs.index') }}" class="block p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 hover:text-sky-700 transition font-semibold text-slate-700 flex items-center justify-between">
                            <span>Jejak Audit Trail Sistem</span>
                            <span>&rarr;</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
