@extends('layouts.app', ['heading' => 'Detail Audit Kebutuhan User'])

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 250)">
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
            <div class="shimmer-skeleton h-10 w-3/4 rounded-lg"></div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4">
                <div class="shimmer-skeleton h-16 rounded-xl"></div>
                <div class="shimmer-skeleton h-16 rounded-xl"></div>
                <div class="shimmer-skeleton h-16 rounded-xl"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div x-show="!loading" x-cloak class="space-y-6">
        <!-- Top Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <a href="{{ route('audit-kebutuhan.index') }}" class="btn-3d-light px-3.5 py-2 text-xs w-fit">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Daftar</span>
            </a>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Cetak Lembar Wawancara Resmi -->
                <a href="{{ route('audit-kebutuhan.print', $audit) }}" target="_blank" class="btn-3d-secondary px-3.5 py-2 text-xs">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Lembar Wawancara</span>
                </a>

                <!-- Konversi ke Berita Acara -->
                @if ($audit->berita_acara_id)
                    <a href="{{ route('berita-acara.show', $audit->berita_acara_id) }}" class="btn-3d-primary px-3.5 py-2 text-xs">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Lihat Berita Acara Terhubung</span>
                    </a>
                @else
                    <a href="{{ route('berita-acara.create', ['from_audit' => $audit->id]) }}" class="btn-3d-primary px-3.5 py-2 text-xs shadow-md">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Jadikan Berita Acara</span>
                    </a>
                @endif

                @if (auth()->user()->isSuperadmin() || auth()->id() === $audit->auditor_id)
                    <a href="{{ route('audit-kebutuhan.edit', $audit) }}" class="btn-3d-light px-3.5 py-2 text-xs text-sky-700">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit</span>
                    </a>

                    <form method="POST" action="{{ route('audit-kebutuhan.destroy', $audit) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen audit kebutuhan ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-3d-light px-3 py-2 text-xs text-rose-600 hover:text-rose-700 hover:bg-rose-50 border-rose-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Header Card: Info Utama Audit -->
        <div class="card-3d p-6 sm:p-8 bg-white border border-sky-100">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-sky-100">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-mono font-bold text-sky-700 bg-sky-50 px-3 py-1 rounded-lg border border-sky-200">
                            {{ $audit->nomor }}
                        </span>
                        <span class="text-xs text-slate-400">
                            {{ $audit->tanggal_audit ? $audit->tanggal_audit->translatedFormat('l, d F Y') : '-' }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                        {{ $audit->unit_kerja }}
                    </h1>
                    @if ($audit->lokasi_gedung)
                        <p class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $audit->lokasi_gedung }}
                        </p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Status Badge -->
                    <span class="badge-3d text-xs px-3 py-1 border {{ $audit->status->badgeClasses() }}">
                        {{ $audit->status->label() }}
                    </span>

                    <!-- Urgensi Badge -->
                    <span class="badge-3d text-xs px-3 py-1 border {{ $audit->prioritas->badgeClasses() }}">
                        Prioritas: {{ $audit->prioritas->label() }}
                    </span>

                    @if ($audit->category)
                        <span class="badge-3d text-xs px-3 py-1 border bg-sky-50 text-sky-700 border-sky-200">
                            {{ $audit->category->name }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Ringkasan Info Responden & Auditor -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 text-xs">
                <div class="p-4 rounded-xl bg-sky-50/50 border border-sky-100">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Responden Wawancara</span>
                    <p class="font-bold text-slate-800 text-sm">{{ $audit->nama_responden }}</p>
                    <p class="text-slate-500">{{ $audit->jabatan_responden ?: 'Staf Ruangan' }}</p>
                    @if ($audit->kontak_responden)
                        <p class="text-sky-600 font-mono mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $audit->kontak_responden }}
                        </p>
                    @endif
                </div>

                <div class="p-4 rounded-xl bg-sky-50/50 border border-sky-100">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Petugas IT yang Mewawancarai</span>
                    <p class="font-bold text-slate-800 text-sm">{{ $audit->auditor?->name ?: 'Tim IT RSUD' }}</p>
                    <p class="text-slate-500">{{ $audit->auditor?->email ?: '-' }}</p>
                    <p class="text-slate-400 mt-1">Dicatat: {{ $audit->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div class="p-4 rounded-xl bg-sky-50/50 border border-sky-100">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Status Eksekusi</span>
                    @if ($audit->nama_vendor)
                        <div class="flex items-center gap-1.5 text-purple-700 font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Vendor: {{ $audit->nama_vendor }}
                        </div>
                        <p class="text-slate-500 text-[11px] mt-0.5">{{ $audit->catatan_vendor ?: 'Memerlukan penanganan vendor rekanan' }}</p>
                    @else
                        <div class="flex items-center gap-1.5 text-sky-700 font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Penanganan Internal IT RSUD
                        </div>
                        <p class="text-slate-500 text-[11px] mt-0.5">Dapat diselesaikan secara mandiri oleh tim Ruang IT</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2 Kolom Konten Detail Wawancara -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom Kiri: Suara User (Keluhan & Maunya Mereka) -->
            <div class="space-y-6">
                <!-- Card: Keluhan & Kendala -->
                <div class="card-3d p-6 bg-white border border-rose-100">
                    <div class="flex items-center gap-2 pb-3 border-b border-rose-100 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Keluhan & Kendala yang Dialami User</h3>
                            <p class="text-[11px] text-slate-400">Masalah operasional harian yang dihadapi staf di ruangan</p>
                        </div>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div>
                            <span class="text-slate-400 font-semibold block mb-1">Uraian Masalah / Kendala Lapangan:</span>
                            <div class="p-3.5 bg-rose-50/60 rounded-xl border border-rose-100 text-slate-800 font-medium leading-relaxed whitespace-pre-line">
                                {{ $audit->keluhan_kendala }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Maunya Mereka (Kebutuhan & Harapan User) -->
                <div class="card-3d p-6 bg-white border border-sky-100">
                    <div class="flex items-center gap-2 pb-3 border-b border-sky-100 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-sky-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Ekspektasi & Kebutuhan ("Maunya Mereka")</h3>
                            <p class="text-[11px] text-slate-400">Harapan, permintaan alat, atau sistem yang diinginkan ruangan</p>
                        </div>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div>
                            <span class="text-slate-400 font-semibold block mb-1">Permintaan / Kebutuhan User:</span>
                            <div class="p-3.5 bg-sky-50/70 rounded-xl border border-sky-200 text-sky-950 font-medium leading-relaxed whitespace-pre-line">
                                {{ $audit->keinginan_harapan }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Jawaban Tim IT & Vendor Rekanan -->
            <div class="space-y-6">
                <!-- Card: Rekomendasi & Solusi IT -->
                <div class="card-3d p-6 bg-white border border-sky-100">
                    <div class="flex items-center gap-2 pb-3 border-b border-sky-100 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Rekomendasi / Solusi Teknis Tim IT</h3>
                            <p class="text-[11px] text-slate-400">Kajian teknis kelayakan dan rencana tindak lanjut tim IT RSUD</p>
                        </div>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div>
                            <div class="p-3.5 bg-sky-50/50 rounded-xl border border-sky-100 text-slate-800 leading-relaxed whitespace-pre-line">
                                {{ $audit->rekomendasi_it ?: 'Belum ada catatan rekomendasi teknis dari tim IT.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Instruksi Vendor (Jika Diserahkan ke Pihak Ketiga) -->
                @if ($audit->nama_vendor)
                    <div class="card-3d p-6 bg-white border border-purple-200">
                        <div class="flex items-center gap-2 pb-3 border-b border-purple-100 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-purple-900">Penyerahan ke Rekanan Vendor</h3>
                                <p class="text-[11px] text-purple-500">Pekerjaan diteruskan ke mitra rekanan IT RSUD</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div>
                                <span class="text-slate-400 font-semibold block mb-0.5">Nama Vendor:</span>
                                <p class="font-bold text-slate-800">{{ $audit->nama_vendor }}</p>
                            </div>

                            @if ($audit->catatan_vendor)
                                <div class="pt-2 border-t border-purple-100">
                                    <span class="text-slate-400 font-semibold block mb-1">Instruksi / Spesifikasi Pekerjaan Vendor:</span>
                                    <div class="p-3 bg-purple-50/60 rounded-xl border border-purple-200 text-purple-950 leading-relaxed whitespace-pre-line">
                                        {{ $audit->catatan_vendor }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Card: Tanda Tangan Digital Perequest / Responden -->
                <div class="card-3d p-6 bg-white border border-sky-100">
                    <div class="flex items-center justify-between pb-3 border-b border-sky-100 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs">
                                ✍️
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Tanda Tangan Perequest / Responden</h3>
                                <p class="text-[11px] text-slate-400">Bukti verifikasi langsung dari unit ruangan</p>
                            </div>
                        </div>
                        @if ($audit->tanda_tangan_responden)
                            <span class="badge-3d text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Terverifikasi
                            </span>
                        @else
                            <span class="badge-3d text-[10px] bg-slate-50 text-slate-600 border border-slate-200">
                                Belum Ada Tanda Tangan
                            </span>
                        @endif
                    </div>

                    @if ($audit->tanda_tangan_responden)
                        <div class="p-3 bg-slate-50 rounded-xl border border-sky-200 flex flex-col items-center justify-center">
                            <img src="{{ $audit->tanda_tangan_responden }}" alt="Tanda Tangan {{ $audit->nama_responden }}" class="max-h-28 object-contain">
                            <div class="mt-2 text-center text-[11px] text-slate-500 font-medium">
                                {{ $audit->nama_responden }} ({{ $audit->jabatan_responden ?: $audit->unit_kerja }})
                            </div>
                        </div>
                    @else
                        <div class="p-4 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-400">
                            <p>Responden belum membubuhkan tanda tangan saat wawancara.</p>
                            <a href="{{ route('audit-kebutuhan.edit', $audit) }}" class="inline-block mt-2 text-sky-600 font-semibold hover:underline">
                                + Bubuhkan tanda tangan sekarang &rarr;
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Card: Hubungan Berita Acara -->
                <div class="card-3d p-6 bg-white border border-sky-100">
                    <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Hubungan Berita Acara
                    </h3>

                    @if ($audit->beritaAcara)
                        <div class="p-4 rounded-xl bg-sky-50/60 border border-sky-200 text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-bold text-sky-800">{{ $audit->beritaAcara->nomor }}</span>
                                <span class="badge-3d text-[10px] {{ $audit->beritaAcara->status->badgeClass() }}">
                                    {{ $audit->beritaAcara->status->label() }}
                                </span>
                            </div>
                            <p class="text-slate-600 line-clamp-2">{{ $audit->beritaAcara->keluhan }}</p>
                            <div class="pt-2 flex justify-end">
                                <a href="{{ route('berita-acara.show', $audit->beritaAcara) }}" class="btn-3d-primary px-3 py-1.5 text-xs">
                                    Buka Detail BA &rarr;
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-xs text-slate-500 space-y-3">
                            <p>Data hasil wawancara ini belum dikonversi menjadi dokumen Berita Acara resmi.</p>
                            <a href="{{ route('berita-acara.create', ['from_audit' => $audit->id]) }}" class="btn-3d-primary px-4 py-2 text-xs inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Jadikan Berita Acara dari Audit Ini</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
