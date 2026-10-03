<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berita Acara — {{ $beritaAcara->nomor }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Times+New+Roman&display=swap" rel="stylesheet">

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
        <a href="{{ route('berita-acara.show', $beritaAcara) }}" class="btn-3d-light px-4 py-2 text-xs">
            &larr; Kembali ke Detail
        </a>
        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-500">Gunakan cetak browser untuk menyimpan sebagai PDF resmi</span>
            <button onclick="window.print()" class="btn-3d-primary px-5 py-2 text-xs shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Cetak ke PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Paper Document Container -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-lg border border-slate-200 rounded-lg print:shadow-none print:border-none print:p-0">
        <!-- Letterhead (KOP SURAT) -->
        <div class="border-b-4 border-double border-slate-900 pb-4 mb-6 text-center">
            <div class="flex items-center justify-between">
                <div class="w-16 h-16 shrink-0 flex items-center justify-center font-bold text-sky-800 text-xs border border-sky-300 rounded p-1">
                    RSUD CB
                </div>
                <div class="flex-1 text-center px-4">
                    <h2 class="text-sm font-bold tracking-wider text-slate-700 uppercase">Pemerintah Provinsi Maluku Utara</h2>
                    <h1 class="text-base sm:text-lg font-black text-slate-900 uppercase">Rumah Sakit Umum Daerah Dr. H. Chasan Boesoirie</h1>
                    <h3 class="text-xs sm:text-sm font-bold text-sky-800 tracking-wide uppercase">Instalasi Teknologi Informasi & Komunikasi (Ruang IT)</h3>
                    <p class="text-[11px] text-slate-600 mt-1">Jl. Tanah Tinggi No. 1, Kota Ternate, Maluku Utara 97715 &bull; Surel: it@rsudchasan.id</p>
                </div>
                <div class="w-16 h-16 shrink-0 flex items-center justify-center font-bold text-sky-800 text-xs border border-sky-300 rounded p-1">
                    TIK
                </div>
            </div>
        </div>

        <!-- Document Title -->
        <div class="text-center my-6 space-y-1">
            <h2 class="text-base sm:text-lg font-black uppercase tracking-wide underline underline-offset-4 text-slate-900">
                Berita Acara Penanganan & Perbaikan IT
            </h2>
            <p class="text-xs font-mono font-bold text-slate-700">Nomor: {{ $beritaAcara->nomor }}</p>
        </div>

        <p class="text-xs text-slate-800 leading-relaxed text-justify mb-4">
            Pada hari ini <strong>{{ $beritaAcara->tanggal->translatedFormat('l') }}</strong>, tanggal <strong>{{ $beritaAcara->tanggal->translatedFormat('d F Y') }}</strong>, bertempat di <strong>{{ $beritaAcara->lokasi }}</strong> RSUD Dr. H. Chasan Boesoirie Ternate, telah dilakukan penanganan dan pemeriksaan teknis operasional dengan rincian sebagai berikut:
        </p>

        <!-- Main Information Table -->
        <div class="space-y-4 text-xs">
            <!-- Table 1: Pihak Terkait -->
            <table class="w-full border-collapse border border-slate-300">
                <tbody>
                    <tr class="bg-slate-50 font-bold">
                        <td colspan="2" class="border border-slate-300 p-2 text-slate-900 uppercase">I. Identitas Pihak Terkait</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 w-1/3 text-slate-600 font-semibold">1. Unit / Ruangan Pelapor</td>
                        <td class="border border-slate-300 p-2 font-medium">{{ $reporter?->unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">2. Nama Pelapor / Pengguna</td>
                        <td class="border border-slate-300 p-2 font-medium">{{ $reporter?->nama ?? '-' }} ({{ $reporter?->jabatan ?? 'Staf' }})</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">3. Kontak Pelapor</td>
                        <td class="border border-slate-300 p-2 font-medium">{{ $reporter?->kontak ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">4. Petugas Pelaksana Ruang IT</td>
                        <td class="border border-slate-300 p-2 font-medium">{{ $itStaff?->nama ?? $beritaAcara->creator->name }} (Ruang IT Chasan)</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">5. Klasifikasi & Prioritas</td>
                        <td class="border border-slate-300 p-2 font-medium">Kategori: {{ $beritaAcara->category->name }} | Prioritas: {{ $beritaAcara->prioritas->label() }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Table 2: Uraian Masalah & Teknis -->
            <table class="w-full border-collapse border border-slate-300">
                <tbody>
                    <tr class="bg-slate-50 font-bold">
                        <td colspan="2" class="border border-slate-300 p-2 text-slate-900 uppercase">II. Rincian Teknis & Penanganan Masalah</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 w-1/3 text-slate-600 font-semibold">1. Uraian Keluhan / Kendala</td>
                        <td class="border border-slate-300 p-2 whitespace-pre-line">{{ $beritaAcara->keluhan }}</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">2. Hasil Pemeriksaan Teknis</td>
                        <td class="border border-slate-300 p-2 whitespace-pre-line">{{ $beritaAcara->hasil_pemeriksaan ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">3. Penyebab / Akar Masalah</td>
                        <td class="border border-slate-300 p-2 whitespace-pre-line">{{ $beritaAcara->penyebab ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">4. Tindakan Solusi Yang Dilakukan</td>
                        <td class="border border-slate-300 p-2 whitespace-pre-line">{{ $beritaAcara->tindakan ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">5. Audit Kebutuhan Perangkat / Material</td>
                        <td class="border border-slate-300 p-2 whitespace-pre-line">{{ $beritaAcara->kebutuhan ?: 'Tidak ada kebutuhan khusus' }}</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">6. Pelaksana / Rekanan Vendor</td>
                        <td class="border border-slate-300 p-2">
                            @if ($beritaAcara->nama_vendor)
                                <strong>{{ $beritaAcara->nama_vendor }}</strong>
                                @if ($beritaAcara->kontak_vendor)
                                    <span class="text-slate-600"> (Kontak: {{ $beritaAcara->kontak_vendor }})</span>
                                @endif
                                @if ($beritaAcara->catatan_vendor)
                                    <div class="text-[10px] text-slate-600 mt-0.5">Catatan/Instruksi: {{ $beritaAcara->catatan_vendor }}</div>
                                @endif
                            @else
                                <span class="italic text-slate-600">Dikerjakan mandiri oleh Tim Internal Ruang IT Chasan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">7. Kesimpulan Hasil Kerja</td>
                        <td class="border border-slate-300 p-2 whitespace-pre-line">{{ $beritaAcara->kesimpulan ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-600 font-semibold">8. Rekomendasi Tindak Lanjut</td>
                        <td class="border border-slate-300 p-2 whitespace-pre-line">{{ $beritaAcara->tindak_lanjut ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="text-xs text-slate-800 leading-relaxed text-justify mt-5 mb-8">
            Demikian Berita Acara ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya dan menjadi bukti dokumentasi penanganan resmi di lingkungan RSUD Dr. H. Chasan Boesoirie.
        </p>

        <!-- Signatures & QR Section -->
        <div class="grid grid-cols-3 gap-4 text-center text-xs mt-8 pt-4 border-t border-slate-200">
            <!-- Signature 1: Pelapor -->
            <div class="space-y-12">
                <p class="font-semibold text-slate-700">Pihak Pelapor / Pemohon,</p>
                <div class="h-20 flex items-center justify-center">
                    @if ($reporter && $reporter->latestSignature)
                        <div class="text-[10px] text-emerald-800 font-bold border border-emerald-300 bg-emerald-50 px-2 py-1 rounded">
                            Tersertifikasi Digital<br>
                            <span class="font-mono text-[9px]">{{ $reporter->latestSignature->signed_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @else
                        <span class="text-slate-400 italic text-[11px]">(Menunggu TTD)</span>
                    @endif
                </div>
                <div>
                    <p class="font-bold underline text-slate-900">{{ $reporter?->nama ?? '....................................' }}</p>
                    <p class="text-[11px] text-slate-500">{{ $reporter?->unit ?? 'Unit Kerja' }}</p>
                </div>
            </div>

            <!-- QR Verification Seal -->
            <div class="flex flex-col items-center justify-center space-y-1">
                <div class="w-24 h-24 border-2 border-slate-400 p-1 rounded bg-slate-50 flex flex-col items-center justify-center text-center">
                    <span class="font-mono font-bold text-[10px] text-sky-900">VERIFIKASI</span>
                    <span class="font-mono font-extrabold text-[12px] text-slate-800">{{ $beritaAcara->getVerificationCode() }}</span>
                    <span class="text-[8px] text-slate-500 uppercase mt-1">e-BA RSUD CH</span>
                </div>
                <p class="text-[9px] text-slate-500 text-center max-w-[140px]">
                    Validasi keaslian dokumen via: <br>
                    <span class="font-mono text-[8px] text-sky-700 truncate block">{{ route('verify.show', $beritaAcara->getVerificationCode()) }}</span>
                </p>
            </div>

            <!-- Signature 2: Petugas IT -->
            <div class="space-y-12">
                <p class="font-semibold text-slate-700">Petugas Ruang IT Chasan,</p>
                <div class="h-20 flex items-center justify-center">
                    @if ($itStaff && $itStaff->latestSignature)
                        <div class="text-[10px] text-emerald-800 font-bold border border-emerald-300 bg-emerald-50 px-2 py-1 rounded">
                            Tersertifikasi Digital<br>
                            <span class="font-mono text-[9px]">{{ $itStaff->latestSignature->signed_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @else
                        <div class="text-[10px] text-sky-800 font-bold border border-sky-300 bg-sky-50 px-2 py-1 rounded">
                            Tercatat Sistem IT<br>
                            <span class="font-mono text-[9px]">{{ $beritaAcara->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="font-bold underline text-slate-900">{{ $itStaff?->nama ?? $beritaAcara->creator->name }}</p>
                    <p class="text-[11px] text-slate-500">Ruang IT RSUD Dr. H. Chasan B.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
