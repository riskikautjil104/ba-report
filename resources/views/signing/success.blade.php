@extends('layouts.guest', ['title' => 'Tanda Tangan Berhasil'])

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
    <div class="card-3d p-8 bg-white text-center space-y-4">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-md">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-xl font-black text-slate-800">Tanda Tangan Berhasil Direkam!</h1>
        <p class="text-xs text-slate-600 leading-relaxed">
            Terima kasih, <strong>{{ $participant->nama }}</strong>. Tanda tangan elektronik Anda untuk Berita Acara <span class="font-mono font-bold text-sky-800">{{ $beritaAcara->nomor }}</span> telah disimpan secara aman.
        </p>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-left text-xs space-y-1.5 font-mono text-slate-600">
            <div>Waktu: {{ $signature->signed_at->format('d/m/Y H:i:s') }} WIT</div>
            <div>Pihak: {{ $participant->participant_type->label() }}</div>
            <div>Status Dokumen: {{ $beritaAcara->status->label() }}</div>
        </div>

        <div class="pt-2">
            <a href="{{ route('verify.show', $beritaAcara->getVerificationCode()) }}" class="btn-3d-primary w-full py-2.5 text-xs">
                Periksa Keabsahan Dokumen (QR)
            </a>
        </div>
    </div>
</div>
@endsection
