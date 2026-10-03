@extends('layouts.app', ['heading' => 'Audit Kebutuhan User'])

@section('content')
<div class="space-y-6 w-full" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 300)">
    <!-- Header Banner -->
    <div class="card-3d p-6 bg-gradient-to-r from-sky-50 via-white to-sky-100/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-100/80 text-sky-800 text-xs font-bold border border-sky-200 mb-2">
                <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                Audit Lapangan & Kunjungan Ruangan
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Audit Kebutuhan & Keluhan Pengguna</h1>
            <p class="text-xs text-slate-600 mt-1 max-w-2xl leading-relaxed">
                Dokumentasikan hasil wawancara langsung ke ruangan: <strong>keluhan operasional yang dialami user</strong> serta <strong>ekspektasi/keinginan perbaikan mereka</strong> untuk dirumuskan solusinya oleh tim IT atau diserahkan ke vendor rekanan.
            </p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('audit-kebutuhan.create') }}" class="btn-3d-primary px-5 py-3 text-xs shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Catat Hasil Wawancara Audit</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card-3d p-4 bg-white">
        <form method="GET" action="{{ route('audit-kebutuhan.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari unit, responden, keluhan..." 
                    class="input-3d input-3d-icon text-xs py-2 w-full"
                >
            </div>

            <div>
                <select name="kategori_id" onchange="this.form.submit()" class="input-3d text-xs py-2 w-full">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('kategori_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" onchange="this.form.submit()" class="input-3d text-xs py-2 w-full">
                    <option value="">Semua Status Audit</option>
                    @foreach ($statuses as $st)
                        <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>
                            {{ $st->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="prioritas" onchange="this.form.submit()" class="input-3d text-xs py-2 w-full">
                    <option value="">Semua Tingkat Prioritas</option>
                    @foreach ($priorities as $p)
                        <option value="{{ $p->value }}" {{ request('prioritas') === $p->value ? 'selected' : '' }}>
                            {{ $p->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Shimmer Skeleton Loading State -->
    <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @for ($i = 0; $i < 6; $i++)
            <div class="skeleton-card space-y-4">
                <div class="flex items-center justify-between">
                    <div class="h-4 w-28 shimmer-skeleton rounded-full"></div>
                    <div class="h-4 w-20 shimmer-skeleton rounded-full"></div>
                </div>
                <div class="space-y-2">
                    <div class="h-3 w-32 shimmer-skeleton rounded"></div>
                    <div class="h-4 w-5/6 shimmer-skeleton rounded"></div>
                    <div class="h-3 w-4/6 shimmer-skeleton rounded"></div>
                </div>
                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <div class="h-3 w-40 shimmer-skeleton rounded"></div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Actual Cards -->
    <div x-show="!loading" x-cloak class="transition-opacity duration-300">
        @if ($audits->isEmpty())
            <div class="card-3d p-12 text-center bg-white">
                <div class="w-16 h-16 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center mx-auto mb-4 shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum ada dokumen audit kebutuhan user</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Mulai lakukan kunjungan ke ruangan-ruangan rumah sakit untuk mendengar keluhan dan mencatat kebutuhan mereka.
                </p>
                <div class="mt-5">
                    <a href="{{ route('audit-kebutuhan.create') }}" class="btn-3d-primary px-5 py-2.5 text-xs shadow-md">
                        + Catat Hasil Wawancara Audit Baru
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($audits as $audit)
                    <div class="card-3d card-3d-hover p-5 bg-white flex flex-col justify-between block group">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <span class="badge-3d text-[11px] font-mono {{ $audit->status->badgeClasses() }}">
                                    {{ $audit->status->label() }}
                                </span>
                                <span class="badge-3d text-[10px] {{ $audit->prioritas->badgeClasses() }}">
                                    {{ $audit->prioritas->label() }}
                                </span>
                            </div>

                            <div>
                                <div class="text-xs font-mono font-bold text-sky-700 flex items-center justify-between">
                                    <span>{{ $audit->nomor }}</span>
                                    <span class="text-[10px] text-slate-400 font-sans font-normal">{{ $audit->tanggal_audit->translatedFormat('d M Y') }}</span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 mt-1">
                                    {{ $audit->unit_kerja }}
                                </h3>
                                <p class="text-[11px] text-slate-500">{{ $audit->lokasi_gedung }}</p>
                            </div>

                            <!-- Responden Info -->
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                                <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                                    <svg class="w-3.5 h-3.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="truncate">Responden: <strong>{{ $audit->nama_responden }}</strong> @if($audit->jabatan_responden) ({{ $audit->jabatan_responden }}) @endif</span>
                                </div>
                                @if ($audit->kontak_responden)
                                    <div class="text-[10px] text-slate-400 font-mono pl-5">{{ $audit->kontak_responden }}</div>
                                @endif
                            </div>

                            <!-- Keluhan & Maunya Mereka Preview -->
                            <div class="space-y-2 text-xs">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-rose-600 block">Keluhan / Kendala:</span>
                                    <p class="text-slate-600 line-clamp-2 leading-relaxed text-[11px]">{{ $audit->keluhan_kendala }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-sky-600 block">Kebutuhan ("Maunya Mereka"):</span>
                                    <p class="text-slate-600 line-clamp-2 leading-relaxed text-[11px]">{{ $audit->keinginan_harapan }}</p>
                                </div>
                            </div>

                            @if ($audit->nama_vendor)
                                <div class="pt-2 border-t border-sky-50 text-[10px] text-slate-500 flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span>Vendor: <strong class="text-slate-700">{{ $audit->nama_vendor }}</strong></span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer Action Buttons -->
                        <div class="mt-4 pt-3 border-t border-sky-50 flex items-center justify-between text-xs">
                            <span class="badge-3d text-[10px] bg-sky-50 text-sky-700 border border-sky-200">
                                {{ $audit->category->name }}
                            </span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('audit-kebutuhan.print', $audit) }}" target="_blank" class="btn-3d-light p-1.5 text-slate-600" title="Cetak Lembar Audit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </a>
                                <a href="{{ route('audit-kebutuhan.show', $audit) }}" class="btn-3d-primary px-3 py-1 text-xs">
                                    Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($audits->hasPages())
                <div class="mt-6">
                    {{ $audits->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
