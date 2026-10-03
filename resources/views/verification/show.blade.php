@extends('layouts.guest', ['title' => 'Verifikasi Dokumen Resmi'])

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-xl px-4 py-8">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-20 h-20 p-2 rounded-2xl bg-white shadow-xl border border-sky-100 mx-auto mb-3">
            <img src="{{ asset('logo/logoresmi.png') }}" alt="Logo RSUD Dr. H. Chasan Boesoirie" class="w-full h-full object-contain">
        </div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Verifikasi Keabsahan Dokumen</h1>
        <p class="text-xs text-slate-500 font-medium">Layanan Verifikasi Publik RSUD Dr. H. Chasan Boesoirie Ternate</p>
    </div>

    <div class="card-3d p-6 sm:p-8 bg-white space-y-5">
        <!-- Verification Banner -->
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-emerald-900 text-sm">Dokumen Terverifikasi Sah & Asli</h2>
                <p class="text-xs text-emerald-700">Tercatat secara resmi dalam sistem e-BA Ruang IT RSUD Dr. H. Chasan Boesoirie.</p>
            </div>
        </div>

        <!-- Safe Public Metadata -->
        <div class="space-y-3 text-xs border-y border-sky-100 py-4">
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400 font-semibold">Nomor Berita Acara:</span>
                <span class="font-bold font-mono text-sky-800 text-sm">{{ $beritaAcara->nomor }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400 font-semibold">Tanggal Dokumen:</span>
                <span class="font-bold text-slate-800">{{ $beritaAcara->tanggal->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400 font-semibold">Kategori Masalah:</span>
                <span class="font-bold text-slate-800">{{ $beritaAcara->category->name }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400 font-semibold">Status Dokumen:</span>
                <span class="badge-3d {{ $beritaAcara->status->badgeClasses() }}">{{ $beritaAcara->status->label() }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400 font-semibold">Kode Validasi:</span>
                <span class="font-mono font-bold text-slate-700">{{ $code }}</span>
            </div>
        </div>

        <!-- Signatures Confirmation -->
        <div>
            <h3 class="text-xs font-bold text-slate-800 mb-2 uppercase tracking-wider">Konfirmasi Penandatanganan:</h3>
            <div class="space-y-2 text-xs">
                @foreach ($beritaAcara->participants as $part)
                    @php $sig = $part->latestSignature; @endphp
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800 block">{{ $part->nama }}</span>
                            <span class="text-[10px] text-slate-500">{{ $part->unit }} ({{ $part->participant_type->label() }})</span>
                        </div>
                        <div>
                            @if ($sig)
                                <span class="badge-3d bg-emerald-100 text-emerald-800 text-[10px]">
                                    ✓ Sah ({{ $sig->signed_at->format('d/m/Y') }})
                                </span>
                            @else
                                <span class="badge-3d bg-amber-100 text-amber-800 text-[10px]">
                                    Belum Menandatangani
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="text-center pt-2">
            <span class="text-[10px] text-slate-400">Instalasi TIK Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate</span>
        </div>
    </div>
</div>
@endsection
