@extends('layouts.app', ['heading' => 'Daftar Berita Acara & Rekanan Vendor'])

@section('content')
<div class="space-y-6 w-full" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 400)">
    <!-- Top Action & Filter Bar -->
    <div class="card-3d p-5 bg-white">
        <form method="GET" action="{{ route('berita-acara.index') }}" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nomor BA, vendor, lokasi, keluhan, pelapor, atau unit..."
                        class="input-3d input-3d-icon"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sky-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-3d-light px-4 py-2.5 text-xs">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if(request()->anyFilled(['search', 'status', 'prioritas', 'kategori_id']))
                        <a href="{{ route('berita-acara.index') }}" class="btn-3d-light px-3 py-2.5 text-xs text-slate-500 hover:text-rose-600">
                            Reset
                        </a>
                    @endif

                    <a href="{{ route('berita-acara.create') }}" class="btn-3d-primary px-4 py-2.5 text-xs shadow-md ml-auto md:ml-0">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Buat BA Baru</span>
                    </a>
                </div>
            </div>

            <!-- Filter Badges Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-sky-50 text-xs">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Status Dokumen</label>
                    <select name="status" class="input-3d text-xs py-2" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Prioritas Urgensi</label>
                    <select name="prioritas" class="input-3d text-xs py-2" onchange="this.form.submit()">
                        <option value="">Semua Prioritas</option>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority->value }}" {{ request('prioritas') === $priority->value ? 'selected' : '' }}>
                                {{ $priority->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 mb-1">Kategori Masalah</label>
                    <select name="kategori_id" class="input-3d text-xs py-2" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string) request('kategori_id') === (string) $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- SKELETON SHIMMER CARDS (Shown when loading is true) -->
    <template x-if="loading">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @for ($c = 0; $c < 6; $c++)
                <div class="skeleton-card space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="h-4 w-24 shimmer-skeleton rounded-full"></div>
                        <div class="h-4 w-16 shimmer-skeleton rounded-full"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-3 w-28 shimmer-skeleton rounded"></div>
                        <div class="h-4 w-5/6 shimmer-skeleton rounded"></div>
                        <div class="h-4 w-4/6 shimmer-skeleton rounded"></div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <div class="h-2.5 w-44 shimmer-skeleton rounded"></div>
                        <div class="h-2.5 w-36 shimmer-skeleton rounded"></div>
                    </div>
                </div>
            @endfor
        </div>
    </template>

    <!-- ACTUAL BERITA ACARA CARDS (Revealed when loading is false) -->
    <div x-show="!loading" x-cloak class="transition-opacity duration-300">
        @if ($beritaAcaras->isEmpty())
            <div class="card-3d p-12 text-center bg-white">
                <div class="w-16 h-16 icon-3d-sphere mx-auto mb-4">
                    <svg class="w-8 h-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum ada Berita Acara yang ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Mulai catat audit kebutuhan perbaikan dari unit ruangan untuk ditindaklanjuti secara teknis atau diserahkan ke vendor rekanan.
                </p>
                <div class="mt-5">
                    <a href="{{ route('berita-acara.create') }}" class="btn-3d-primary px-5 py-2.5 text-xs shadow-md">
                        + Buat Berita Acara Baru
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($beritaAcaras as $ba)
                    <a href="{{ route('berita-acara.show', $ba) }}" class="card-3d card-3d-hover p-5 bg-white flex flex-col justify-between block group">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <span class="badge-3d text-[11px] font-mono {{ $ba->status->badgeClasses() }}">
                                    {{ $ba->status->label() }}
                                </span>
                                <span class="badge-3d text-[10px] {{ $ba->prioritas->badgeClasses() }}">
                                    {{ $ba->prioritas->label() }}
                                </span>
                            </div>

                            <div>
                                <div class="text-xs font-mono font-bold text-sky-700 group-hover:text-sky-800 transition flex items-center justify-between">
                                    <span>{{ $ba->nomor }}</span>
                                    @if ($ba->nama_vendor)
                                        <span class="badge-3d bg-slate-100 text-slate-600 text-[9px] font-sans">
                                            Vendor: {{ $ba->nama_vendor }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 line-clamp-2 mt-1 group-hover:text-sky-600 transition">
                                    {{ $ba->keluhan }}
                                </h3>
                            </div>

                            <div class="text-xs text-slate-500 space-y-1 pt-2 border-t border-sky-50">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="truncate">{{ $ba->reporter?->unit ?? 'Unit Kerja' }} ({{ $ba->lokasi }})</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="truncate">Pelapor: <strong class="text-slate-700 font-semibold">{{ $ba->reporter?->nama ?? '-' }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-sky-50 flex items-center justify-between text-[11px] text-slate-400">
                            <span class="inline-flex items-center gap-1 font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                {{ $ba->category->name }}
                            </span>
                            <span class="font-mono">{{ $ba->tanggal->format('d/m/Y') }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $beritaAcaras->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
