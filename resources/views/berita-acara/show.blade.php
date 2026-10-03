@extends('layouts.app', ['heading' => 'Detail Berita Acara'])

@section('content')
<div class="space-y-6 w-full" x-data="{ loading: true, signModalOpen: false, signingUrl: '{{ session('signing_url') }}' }" x-init="setTimeout(() => loading = false, 300)">
    <!-- Shimmer Skeleton Loading State -->
    <div x-show="loading" class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="shimmer-skeleton h-8 w-36 rounded-xl"></div>
            <div class="flex gap-2">
                <div class="shimmer-skeleton h-8 w-28 rounded-xl"></div>
                <div class="shimmer-skeleton h-8 w-24 rounded-xl"></div>
            </div>
        </div>

        <div class="skeleton-card p-6 sm:p-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-sky-100">
                <div class="space-y-2">
                    <div class="shimmer-skeleton h-3 w-28 rounded"></div>
                    <div class="shimmer-skeleton h-8 w-64 rounded-lg"></div>
                    <div class="shimmer-skeleton h-3 w-48 rounded"></div>
                </div>
                <div class="flex gap-2">
                    <div class="shimmer-skeleton h-7 w-24 rounded-full"></div>
                    <div class="shimmer-skeleton h-7 w-28 rounded-full"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div class="shimmer-skeleton h-16 rounded-xl"></div>
                <div class="shimmer-skeleton h-16 rounded-xl"></div>
                <div class="shimmer-skeleton h-16 rounded-xl"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="skeleton-card p-6 space-y-3">
                    <div class="shimmer-skeleton h-4 w-40 rounded"></div>
                    <div class="shimmer-skeleton h-16 rounded-lg"></div>
                </div>
                <div class="skeleton-card p-6 space-y-3">
                    <div class="shimmer-skeleton h-4 w-48 rounded"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="shimmer-skeleton h-24 rounded-xl"></div>
                        <div class="shimmer-skeleton h-24 rounded-xl"></div>
                    </div>
                </div>
                <div class="skeleton-card p-6 space-y-3">
                    <div class="shimmer-skeleton h-4 w-52 rounded"></div>
                    <div class="shimmer-skeleton h-24 rounded-xl"></div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="skeleton-card p-6 space-y-4">
                    <div class="shimmer-skeleton h-4 w-36 rounded"></div>
                    <div class="shimmer-skeleton h-28 rounded-xl"></div>
                    <div class="shimmer-skeleton h-28 rounded-xl"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actual Content Container -->
    <div x-show="!loading" x-cloak class="space-y-6">
        <!-- Top Action Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('berita-acara.index') }}" class="btn-3d-light px-3.5 py-2 text-xs">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('berita-acara.print', $beritaAcara) }}" target="_blank" class="btn-3d-light px-4 py-2 text-xs text-slate-700">
                <svg class="w-4 h-4 mr-1.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Simpan PDF</span>
            </a>

            @can('update', $beritaAcara)
                <a href="{{ route('berita-acara.edit', $beritaAcara) }}" class="btn-3d-light px-4 py-2 text-xs text-sky-700">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Data</span>
                </a>
            @endcan

            @can('archive', $beritaAcara)
                <form method="POST" action="{{ route('berita-acara.archive-action', $beritaAcara) }}" onsubmit="return confirm('Pindahkan Berita Acara ini ke arsip permanen?');">
                    @csrf
                    <button type="submit" class="btn-3d-light px-4 py-2 text-xs text-purple-700 hover:bg-purple-50">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span>Arsipkan</span>
                    </button>
                </form>
            @endcan

            @can('delete', $beritaAcara)
                <form method="POST" action="{{ route('berita-acara.destroy', $beritaAcara) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus draft Berita Acara ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-3d-light px-3 py-2 text-xs text-rose-600 hover:bg-rose-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <!-- Signing Link Notification Banner (if session created) -->
    @if (session('signing_url'))
        <div class="card-3d p-5 bg-gradient-to-r from-sky-50 via-white to-sky-50 border-2 border-sky-300 shadow-xl space-y-3">
            <div class="flex items-center gap-2.5 text-sky-900 font-bold text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-sky-500 animate-ping"></span>
                Tautan Tanda Tangan Siap Dibagikan ke: {{ session('signing_participant') }}
            </div>
            <p class="text-xs text-slate-600">
                Kirimkan tautan berikut ke pelapor atau buka melalui browser/ponsel mereka untuk penandatanganan di layar sentuh.
            </p>
            <div class="flex items-center gap-2">
                <input type="text" readonly value="{{ session('signing_url') }}" id="signingUrlInput" class="input-3d text-xs font-mono bg-white">
                <button 
                    type="button" 
                    onclick="navigator.clipboard.writeText(document.getElementById('signingUrlInput').value); alert('Tautan berhasil disalin ke clipboard!');"
                    class="btn-3d-primary px-4 py-2.5 text-xs shrink-0"
                >
                    Salin Tautan
                </button>
                <a href="{{ session('signing_url') }}" target="_blank" class="btn-3d-light px-4 py-2.5 text-xs shrink-0">
                    Buka Halaman TTD
                </a>
            </div>
        </div>
    @endif

    <!-- Main Header Card -->
    <div class="card-3d p-6 sm:p-8 bg-white space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-sky-100">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor Berita Acara</span>
                <h1 class="text-2xl sm:text-3xl font-black text-sky-800 font-mono tracking-tight">{{ $beritaAcara->nomor }}</h1>
                <p class="text-xs text-slate-500 mt-1">Dibuat oleh {{ $beritaAcara->creator->name }} pada {{ $beritaAcara->created_at->translatedFormat('d F Y, H:i') }} WIT</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <span class="badge-3d text-xs font-bold {{ $beritaAcara->status->badgeClasses() }}">
                    {{ $beritaAcara->status->label() }}
                </span>
                <span class="badge-3d text-xs font-bold {{ $beritaAcara->prioritas->badgeClasses() }}">
                    Prioritas: {{ $beritaAcara->prioritas->label() }}
                </span>
                <span class="badge-3d text-xs bg-sky-50 text-sky-800 border border-sky-200">
                    Kategori: {{ $beritaAcara->category->name }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 text-xs">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 font-medium block">Tanggal Kegiatan:</span>
                <span class="text-slate-800 font-bold text-sm">{{ $beritaAcara->tanggal->translatedFormat('l, d F Y') }}</span>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 font-medium block">Lokasi Kejadian:</span>
                <span class="text-slate-800 font-bold text-sm">{{ $beritaAcara->lokasi }}</span>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 font-medium block">Kode Verifikasi QR:</span>
                <span class="text-sky-700 font-mono font-bold text-sm">{{ $beritaAcara->getVerificationCode() }}</span>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout: Content & Sidebar Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Documentation & Technical Details (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Keluhan / Masalah -->
            <div class="card-3d p-6 bg-white space-y-2">
                <div class="flex items-center gap-2 pb-2 border-b border-sky-50">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <h3 class="text-sm font-bold text-slate-800">Uraian Keluhan & Permasalahan</h3>
                </div>
                <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line pt-2 font-medium">
                    {{ $beritaAcara->keluhan }}
                </p>
            </div>

            <!-- Pemeriksaan & Penyebab -->
            <div class="card-3d p-6 bg-white space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-sky-50">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                    <h3 class="text-sm font-bold text-slate-800">Hasil Pemeriksaan Teknis & Akar Masalah</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-sky-50/50 border border-sky-100">
                        <span class="font-bold text-sky-900 block mb-1">Hasil Pemeriksaan:</span>
                        <p class="text-slate-700 whitespace-pre-line">{{ $beritaAcara->hasil_pemeriksaan ?: 'Belum dicatat' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-sky-50/50 border border-sky-100">
                        <span class="font-bold text-sky-900 block mb-1">Akar Masalah / Penyebab:</span>
                        <p class="text-slate-700 whitespace-pre-line">{{ $beritaAcara->penyebab ?: 'Belum dicatat' }}</p>
                    </div>
                </div>
            </div>

            <!-- Tindakan & Kebutuhan Material -->
            <div class="card-3d p-6 bg-white space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-sky-50">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold text-slate-800">Tindakan Perbaikan & Kebutuhan Material</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-bold text-slate-900 block mb-1">Tindakan Yang Dilakukan:</span>
                        <p class="text-slate-700 whitespace-pre-line">{{ $beritaAcara->tindakan ?: 'Belum dicatat' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-bold text-slate-900 block mb-1">Audit Kebutuhan Suku Cadang:</span>
                        <p class="text-slate-700 whitespace-pre-line">{{ $beritaAcara->kebutuhan ?: 'Tidak ada kebutuhan khusus' }}</p>
                    </div>
                </div>
            </div>

            <!-- Audit Kebutuhan & Rekanan Vendor Pelaksana -->
            <div class="card-3d p-6 bg-white space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-sky-50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-600"></span>
                        <h3 class="text-sm font-bold text-slate-800">Audit Kebutuhan & Rekanan Vendor Pelaksana</h3>
                    </div>
                    @if ($beritaAcara->nama_vendor)
                        <span class="badge-3d text-[10px] bg-sky-100 text-sky-800 border border-sky-300">
                            Pekerjaan Didelegasikan ke Rekanan
                        </span>
                    @else
                        <span class="badge-3d text-[10px] bg-slate-100 text-slate-600 border border-slate-200">
                            Dikerjakan Internal IT
                        </span>
                    @endif
                </div>

                @if ($beritaAcara->nama_vendor)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-4 rounded-xl bg-sky-50/70 border border-sky-200 space-y-2">
                            <div>
                                <span class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider block">Penyedia Jasa / Vendor:</span>
                                <span class="text-sky-900 font-bold text-sm block mt-0.5">{{ $beritaAcara->nama_vendor }}</span>
                            </div>
                            @if ($beritaAcara->kontak_vendor)
                                <div class="pt-2 border-t border-sky-100 flex items-center gap-2 text-slate-700">
                                    <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="font-mono text-xs font-semibold">{{ $beritaAcara->kontak_vendor }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                            <span class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider block">Instruksi & Catatan Khusus Vendor:</span>
                            <p class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $beritaAcara->catatan_vendor ?: 'Tidak ada instruksi khusus tertulis.' }}</p>
                        </div>
                    </div>

                    @php
                        $waPhone = preg_replace('/[^0-9]/', '', $beritaAcara->kontak_vendor ?? '');
                        if (str_starts_with($waPhone, '0')) {
                            $waPhone = '62' . substr($waPhone, 1);
                        }
                        $waMessage = rawurlencode(
                            "Yth. Rekanan Vendor (" . $beritaAcara->nama_vendor . "),\n\n" .
                            "Berikut Surat Penugasan Pekerjaan IT RSUD Dr. H. Chasan Boesoirie:\n" .
                            "• No. Dokumen: " . $beritaAcara->nomor . "\n" .
                            "• Lokasi: " . $beritaAcara->lokasi . "\n" .
                            "• Prioritas: " . $beritaAcara->prioritas->label() . "\n" .
                            "• Uraian Kendala: " . $beritaAcara->keluhan . "\n" .
                            "• Tindakan/Kebutuhan: " . ($beritaAcara->tindakan ?: $beritaAcara->kebutuhan) . "\n" .
                            "• Instruksi Khusus: " . ($beritaAcara->catatan_vendor ?: 'Sesuai SOP teknis vendor') . "\n\n" .
                            "Mohon segera ditindaklanjuti. Terima kasih."
                        );
                        $waUrl = $waPhone ? "https://wa.me/{$waPhone}?text={$waMessage}" : "https://wa.me/?text={$waMessage}";
                    @endphp

                    <div class="pt-3 border-t border-sky-100 flex flex-wrap items-center gap-2">
                        <a href="{{ $waUrl }}" target="_blank" class="btn-3d px-3.5 py-1.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white flex items-center gap-1.5 shadow-sm">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                            </svg>
                            <span>Kirim Penugasan via WhatsApp Vendor</span>
                        </a>
                        <a href="{{ route('kanban.index', ['vendor' => $beritaAcara->nama_vendor]) }}" class="btn-3d-light px-3.5 py-1.5 text-xs text-sky-700 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                            </svg>
                            <span>Pantau di Papan Kanban</span>
                        </a>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Belum ada penugasan vendor pihak ketiga. Seluruh tindakan dan pengadaan ditangani tim Ruang IT.</span>
                        </div>
                        @can('update', $beritaAcara)
                            <a href="{{ route('berita-acara.edit', $beritaAcara) }}" class="btn-3d-light px-3 py-1.5 text-xs text-sky-700 shrink-0">
                                + Tugaskan Vendor Rekanan
                            </a>
                        @endcan
                    </div>
                @endif
            </div>

            <!-- Kesimpulan & Tindak Lanjut -->
            <div class="card-3d p-6 bg-white space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-sky-50">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    <h3 class="text-sm font-bold text-slate-800">Kesimpulan Akhir & Tindak Lanjut</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-sky-50/40 border border-sky-100">
                        <span class="font-bold text-sky-900 block mb-1">Kesimpulan:</span>
                        <p class="text-slate-700 whitespace-pre-line">{{ $beritaAcara->kesimpulan ?: 'Belum dicatat' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-sky-50/40 border border-sky-100">
                        <span class="font-bold text-sky-900 block mb-1">Rencana Tindak Lanjut:</span>
                        <p class="text-slate-700 whitespace-pre-line">{{ $beritaAcara->tindak_lanjut ?: 'Tidak ada tindak lanjut lanjutan' }}</p>
                    </div>
                </div>
            </div>

            <!-- Berkas Lampiran (Attachments) -->
            <div class="card-3d p-6 bg-white space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Berkas Lampiran Foto / Dokumen</h3>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">{{ $beritaAcara->attachments->count() }} Berkas</span>
                </div>

                @if ($beritaAcara->attachments->isEmpty())
                    <p class="text-xs text-slate-400 italic">Belum ada berkas atau foto dokumentasi yang diunggah.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        @foreach ($beritaAcara->attachments as $att)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                                <div class="min-w-0 flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                                        @if ($att->isImage())
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="truncate">
                                        <p class="font-bold text-slate-800 truncate">{{ $att->original_name }}</p>
                                        <span class="text-[10px] text-slate-400">{{ $att->formatted_size }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('berita-acara.attachments.download', [$beritaAcara, $att]) }}" class="btn-3d-light px-2.5 py-1.5 text-xs text-sky-700 shrink-0">
                                    Unduh
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (! $beritaAcara->isFinalized())
                    <!-- Upload form -->
                    <form method="POST" action="{{ route('berita-acara.attachments.store', $beritaAcara) }}" enctype="multipart/form-data" class="pt-3 border-t border-sky-50 flex flex-col sm:flex-row gap-2">
                        @csrf
                        <input type="file" name="file" required accept="image/*,application/pdf" class="input-3d text-xs py-2 file:mr-3 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:bg-sky-50 file:text-sky-700">
                        <input type="text" name="description" placeholder="Keterangan singkat berkas..." class="input-3d text-xs py-2">
                        <button type="submit" class="btn-3d-primary px-4 py-2 text-xs shrink-0">
                            Unggah
                        </button>
                    </form>
                @endif
            </div>

            <!-- Handling Timeline (Timeline Penanganan) -->
            <div class="card-3d p-6 bg-white space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Timeline Aktivitas & Penanganan</h3>
                    </div>
                </div>

                <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-sky-100 text-xs">
                    @forelse ($beritaAcara->handlingLogs as $log)
                        <div class="relative">
                            <div class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-sky-400 border-2 border-white shadow-sm"></div>
                            <div class="font-bold text-slate-800 flex items-center gap-2">
                                <span>{{ $log->action }}</span>
                                <span class="text-[10px] text-slate-400 font-normal">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <p class="text-slate-600 mt-0.5">{{ $log->description }}</p>
                            @if ($log->user)
                                <span class="text-[10px] text-sky-600 font-semibold block mt-0.5">Oleh: {{ $log->user->name }}</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada riwayat aktivitas.</p>
                    @endforelse
                </div>

                @if (! $beritaAcara->isFinalized())
                    <!-- Add Note Form -->
                    <form method="POST" action="{{ route('berita-acara.handling', $beritaAcara) }}" class="pt-3 border-t border-sky-50 space-y-2">
                        @csrf
                        <input type="text" name="action" required placeholder="Judul aktivitas (Contoh: Pengecekan Lapangan / Penggantian Kabel)" class="input-3d text-xs py-2">
                        <textarea name="description" required rows="2" placeholder="Catatan detail penanganan..." class="input-3d text-xs leading-relaxed"></textarea>
                        <div class="text-right">
                            <button type="submit" class="btn-3d-light px-4 py-2 text-xs">
                                + Tambah Catatan Timeline
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Right: Participants & Signatures (1 Col) -->
        <div class="space-y-6">
            <!-- Pihak Terkait & Status Tanda Tangan -->
            <div class="card-3d p-6 bg-white space-y-5">
                <div class="flex items-center gap-2 pb-3 border-b border-sky-100">
                    <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Pihak Terkait & TTD Digital</h3>
                </div>

                <div class="space-y-4">
                    @foreach ($beritaAcara->participants as $part)
                        @php
                            $signature = $part->signatures->first();
                        @endphp
                        <div class="p-4 rounded-xl {{ $signature ? 'bg-emerald-50/70 border border-emerald-200' : 'bg-slate-50 border border-slate-200' }} text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="badge-3d text-[10px] {{ $signature ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $signature ? '✓ Sudah Ditandatangani' : '⏳ Belum Ditandatangani' }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-semibold">{{ $part->participant_type->label() }}</span>
                            </div>

                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $part->nama }}</h4>
                                <p class="text-slate-500 text-[11px]">{{ $part->unit }} @if($part->jabatan) &bull; {{ $part->jabatan }} @endif</p>
                                @if ($part->kontak)
                                    <p class="text-slate-400 text-[10px] font-mono mt-0.5">{{ $part->kontak }}</p>
                                @endif
                            </div>

                            @if ($signature)
                                <div class="pt-2 border-t border-emerald-200/60 flex items-center justify-between text-[10px] text-emerald-800">
                                    <span>Ditandatangani pada:</span>
                                    <span class="font-bold font-mono">{{ $signature->signed_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @else
                                <div class="pt-2 border-t border-slate-200">
                                    <form method="POST" action="{{ route('berita-acara.request-signature', $beritaAcara) }}">
                                        @csrf
                                        <input type="hidden" name="participant_id" value="{{ $part->id }}">
                                        <button type="submit" class="btn-3d-primary w-full py-2 text-xs">
                                            Buat Tautan TTD Digital
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Card Informasi Keabsahan Dokumen -->
            <div class="card-3d p-6 bg-gradient-to-b from-sky-50/60 to-white text-xs space-y-3">
                <div class="flex items-center gap-2 text-sky-800 font-bold">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Keabsahan & Integritas Dokumen</span>
                </div>
                <p class="text-slate-600 leading-relaxed">
                    Dokumen ini terlindungi secara digital. Dokumen final dilengkapi dengan tanda tangan elektronik, stempel waktu, dan kode QR verifikasi publik.
                </p>
                <div class="pt-2">
                    <a href="{{ route('verify.show', $beritaAcara->getVerificationCode()) }}" target="_blank" class="text-sky-600 hover:text-sky-800 font-bold underline flex items-center gap-1">
                        <span>Lihat Halaman Verifikasi Publik &rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
