<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lembar Wawancara Audit Kebutuhan Ruangan — {{ $auditKebutuhan->nomor }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
                font-size: 11pt;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
            @page {
                size: A4;
                margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans p-4 sm:p-8 antialiased">
    <!-- Screen Print Control Bar -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('audit-kebutuhan.show', $auditKebutuhan) }}" class="btn-3d-light px-4 py-2 text-xs">
            &larr; Kembali ke Detail
        </a>
        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-500">Gunakan cetak browser untuk menyimpan sebagai PDF resmi</span>
            <button onclick="window.print()" class="btn-3d-primary px-5 py-2 text-xs shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Paper Document Container -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-lg border border-slate-200 rounded-lg print:shadow-none print:border-none print:p-0">
        <!-- Letterhead (KOP SURAT) -->
        <div class="border-b-4 border-double border-slate-900 pb-4 mb-6 text-center">
            <div class="flex items-center justify-between">
                <div class="w-20 h-20 shrink-0 flex items-center justify-center">
                    <img src="{{ asset('logo/logoresmi.png') }}" alt="Logo RSUD" class="w-full h-full object-contain">
                </div>
                <div class="flex-1 text-center px-4">
                    <h2 class="text-xs sm:text-sm font-bold tracking-wider text-slate-700 uppercase">Pemerintah Provinsi Maluku Utara</h2>
                    <h1 class="text-base sm:text-lg font-black tracking-tight text-slate-900 uppercase">RSUD Dr. H. Chasan Boesoirie Ternate</h1>
                    <p class="text-[11px] sm:text-xs text-slate-600 mt-0.5">INSTALASI TEKNOLOGI INFORMASI DAN KOMUNIKASI (IT)</p>
                    <p class="text-[10px] text-slate-500 italic mt-0.5">Jl. Tanah Tinggi No. 01, Kota Ternate, Maluku Utara | e-mail: ruangit@chasanboesoirie.go.id</p>
                </div>
                <div class="w-20 h-20 shrink-0"></div>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="text-center mb-6">
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-wide uppercase underline">
                LEMBAR AUDIT KEBUTUHAN & WAWANCARA UNIT
            </h2>
            <p class="text-xs font-mono font-semibold text-slate-700 mt-1">
                Nomor: {{ $auditKebutuhan->nomor }}
            </p>
        </div>

        <!-- Isi Dokumen -->
        <div class="space-y-4 text-xs text-slate-800 leading-relaxed">
            <!-- 1. Identitas Ruangan & Responden -->
            <div class="border border-slate-300 rounded-md p-3.5 bg-slate-50/50">
                <h4 class="font-bold text-slate-900 text-xs uppercase mb-2 border-b border-slate-200 pb-1">
                    I. IDENTITAS RUANGAN & RESPONDEN WAWANCARA
                </h4>
                <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                    <div class="flex">
                        <span class="w-36 font-semibold text-slate-600">Tanggal Wawancara</span>
                        <span class="mr-2">:</span>
                        <span class="font-bold text-slate-900">{{ $auditKebutuhan->tanggal_audit ? $auditKebutuhan->tanggal_audit->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-slate-600">Unit / Ruangan</span>
                        <span class="mr-2">:</span>
                        <span class="font-bold text-slate-900">{{ $auditKebutuhan->unit_kerja }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-slate-600">Lokasi Gedung</span>
                        <span class="mr-2">:</span>
                        <span>{{ $auditKebutuhan->lokasi_gedung ?: '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-slate-600">Nama Responden</span>
                        <span class="mr-2">:</span>
                        <span class="font-bold text-slate-900">{{ $auditKebutuhan->nama_responden }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-slate-600">Jabatan / Profesi</span>
                        <span class="mr-2">:</span>
                        <span>{{ $auditKebutuhan->jabatan_responden ?: '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-36 font-semibold text-slate-600">Kontak Person</span>
                        <span class="mr-2">:</span>
                        <span>{{ $auditKebutuhan->kontak_responden ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Keluhan & Kendala Lapangan -->
            <div class="border border-slate-300 rounded-md p-3.5">
                <h4 class="font-bold text-slate-900 text-xs uppercase mb-2 border-b border-slate-200 pb-1">
                    II. KELUHAN & KENDALA OPERASIONAL DI RUANGAN
                </h4>
                <div class="space-y-2">
                    <div>
                        <span class="font-semibold text-slate-700 block">Uraian Keluhan / Kendala Yang Dihadapi:</span>
                        <p class="mt-1 pl-3 border-l-2 border-rose-400 text-slate-900 whitespace-pre-line">{{ $auditKebutuhan->keluhan_kendala }}</p>
                    </div>
                </div>
            </div>

            <!-- 3. Ekspektasi & Maunya User (Kebutuhan) -->
            <div class="border border-slate-300 rounded-md p-3.5">
                <h4 class="font-bold text-slate-900 text-xs uppercase mb-2 border-b border-slate-200 pb-1">
                    III. EKSPEKTASI & KEBUTUHAN RUANGAN ("MAUNYA MEREKA")
                </h4>
                <div class="space-y-2">
                    <div>
                        <span class="font-semibold text-slate-700 block">Kebutuhan / Permintaan Spesifik dari User:</span>
                        <p class="mt-1 pl-3 border-l-2 border-sky-400 text-slate-900 font-medium whitespace-pre-line">{{ $auditKebutuhan->keinginan_harapan }}</p>
                    </div>
                </div>
            </div>

            <!-- 4. Analisis Teknis Tim IT & Rekomendasi Solusi -->
            <div class="border border-slate-300 rounded-md p-3.5">
                <h4 class="font-bold text-slate-900 text-xs uppercase mb-2 border-b border-slate-200 pb-1">
                    IV. ANALISIS TEKNIS & REKOMENDASI TIM IT RSUD
                </h4>
                <div class="space-y-2">
                    <div class="grid grid-cols-2 gap-4 pb-1">
                        <div class="flex">
                            <span class="w-32 font-semibold text-slate-600">Prioritas</span>
                            <span class="mr-2">:</span>
                            <span class="font-bold uppercase text-slate-900">{{ $auditKebutuhan->prioritas->label() }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 font-semibold text-slate-600">Kategori IT</span>
                            <span class="mr-2">:</span>
                            <span class="font-bold text-slate-900">{{ $auditKebutuhan->category?->name ?: 'Umum' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="font-semibold text-slate-700 block">Rekomendasi / Solusi Teknis:</span>
                        <p class="mt-1 pl-3 border-l-2 border-sky-400 text-slate-900 whitespace-pre-line">{{ $auditKebutuhan->rekomendasi_it ?: 'Ditangani secara reguler oleh tim IT.' }}</p>
                    </div>
                </div>
            </div>

            <!-- 5. Alokasi Rekanan Vendor (Bila Ada) -->
            @if ($auditKebutuhan->nama_vendor)
                <div class="border border-slate-300 rounded-md p-3.5 bg-slate-50/50">
                    <h4 class="font-bold text-slate-900 text-xs uppercase mb-2 border-b border-slate-200 pb-1">
                        V. RENCANA PENUGASAN VENDOR REKANAN (PIHAK KETIGA)
                    </h4>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="w-32 font-semibold text-slate-600">Nama Vendor</span>
                            <span class="mr-2">:</span>
                            <span class="font-bold text-slate-900">{{ $auditKebutuhan->nama_vendor }}</span>
                        </div>
                        @if ($auditKebutuhan->catatan_vendor)
                            <div>
                                <span class="font-semibold text-slate-700 block">Instruksi / Spesifikasi Pekerjaan Vendor:</span>
                                <p class="mt-1 text-slate-900 whitespace-pre-line">{{ $auditKebutuhan->catatan_vendor }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Tanda Tangan Para Pihak (Auditor IT & Responden Unit) -->
        <div class="mt-10 pt-6 border-t border-slate-300">
            <div class="text-center text-xs text-slate-600 mb-6">
                Ternate, {{ $auditKebutuhan->tanggal_audit ? $auditKebutuhan->tanggal_audit->translatedFormat('d F Y') : date('d F Y') }}
            </div>

            <div class="grid grid-cols-2 gap-8 text-center text-xs">
                <!-- Pihak Responden Ruangan -->
                <div>
                    <p class="font-semibold text-slate-700">Responden / Staf Ruangan,</p>
                    <p class="text-[11px] text-slate-500 mb-16">{{ $auditKebutuhan->unit_kerja }}</p>
                    <p class="font-bold text-slate-900 underline">{{ $auditKebutuhan->nama_responden }}</p>
                    <p class="text-[11px] text-slate-600">{{ $auditKebutuhan->jabatan_responden ?: 'Staf Pelayanan' }}</p>
                </div>

                <!-- Pihak Auditor IT RSUD -->
                <div>
                    <p class="font-semibold text-slate-700">Petugas IT yang Mewawancarai,</p>
                    <p class="text-[11px] text-slate-500 mb-16">Instalasi TI & Komunikasi</p>
                    <p class="font-bold text-slate-900 underline">{{ $auditKebutuhan->auditor?->name ?: 'Petugas IT RSUD' }}</p>
                    <p class="text-[11px] text-slate-600">ID Petugas: IT-{{ str_pad((string)($auditKebutuhan->auditor_id ?? 1), 4, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
