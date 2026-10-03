@extends('layouts.app', ['heading' => 'Arsip Berita Acara'])

@section('content')
<div class="space-y-6 w-full">
    <div class="card-3d p-6 bg-gradient-to-r from-purple-50 via-white to-sky-50 border border-purple-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-800">Arsip Resmi Berita Acara Ruang IT</h1>
                <p class="text-xs text-slate-500">Kumpulan Berita Acara yang telah selesai ditangani, ditandatangani, dan diarsipkan permanen.</p>
            </div>
        </div>
    </div>

    @if ($beritaAcaras->isEmpty())
        <div class="card-3d p-12 text-center bg-white">
            <h3 class="text-base font-bold text-slate-800">Belum ada dokumen di arsip</h3>
            <p class="text-xs text-slate-500 mt-1">Dokumen yang berstatus Selesai dapat diarsipkan oleh Superadmin.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($beritaAcaras as $ba)
                <a href="{{ route('berita-acara.show', $ba) }}" class="card-3d card-3d-hover p-5 bg-white flex flex-col justify-between block group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="badge-3d text-[11px] bg-purple-50 text-purple-800 border border-purple-200">
                                Diarsipkan
                            </span>
                            <span class="text-xs text-slate-400">{{ $ba->tanggal->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <div class="text-xs font-mono font-bold text-purple-700">{{ $ba->nomor }}</div>
                            <h3 class="text-sm font-bold text-slate-800 line-clamp-2 mt-0.5">{{ $ba->keluhan }}</h3>
                        </div>
                        <p class="text-xs text-slate-500">{{ $ba->reporter?->unit ?? '-' }} &bull; {{ $ba->lokasi }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $beritaAcaras->links() }}
        </div>
    @endif
</div>
@endsection
