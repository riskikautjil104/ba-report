@extends('layouts.guest', ['title' => 'Dokumen Tidak Ditemukan'])

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
    <div class="card-3d p-8 bg-white text-center space-y-4">
        <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-md">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <h1 class="text-xl font-black text-slate-800">Dokumen Tidak Valid / Tidak Ditemukan</h1>
        <p class="text-xs text-slate-600 leading-relaxed">
            Kode verifikasi <code class="bg-rose-50 px-2 py-0.5 rounded text-rose-800 font-mono">{{ $code }}</code> tidak terdaftar pada basis data resmi e-BA IT Chasan.
        </p>

        <p class="text-xs text-slate-500">
            Pastikan kode QR atau tautan yang Anda pindai berasal dari dokumen fisik resmi RSUD Dr. H. Chasan Boesoirie.
        </p>
    </div>
</div>
@endsection
