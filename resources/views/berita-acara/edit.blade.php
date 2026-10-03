@extends('layouts.app', ['heading' => 'Edit Berita Acara'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('berita-acara.show', $beritaAcara) }}" class="btn-3d-light px-3.5 py-2 text-xs">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Detail</span>
        </a>
        <span class="text-xs font-mono font-bold text-sky-800">{{ $beritaAcara->nomor }}</span>
    </div>

    @if ($errors->any())
        <div class="card-3d p-4 bg-rose-50 border-rose-200 text-rose-800 text-xs">
            <div class="font-bold mb-1 flex items-center gap-1.5 text-rose-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Periksa kembali input formulir Anda:
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('berita-acara.update', $beritaAcara) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Informasi Dokumen & Klasifikasi -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">1</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Informasi & Klasifikasi</h3>
                    <p class="text-[11px] text-slate-400">Pengaturan nomor, tanggal, status, dan prioritas dokumen</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Dokumen</label>
                    <input type="text" value="{{ $beritaAcara->nomor }}" disabled class="input-3d bg-slate-50 text-slate-500 font-mono text-xs cursor-not-allowed">
                </div>

                <div>
                    <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $beritaAcara->tanggal->format('Y-m-d')) }}" required class="input-3d text-xs">
                </div>

                <div>
                    <label for="kategori_id" class="block text-xs font-semibold text-slate-700 mb-1">Kategori Masalah <span class="text-rose-500">*</span></label>
                    <select id="kategori_id" name="kategori_id" required class="input-3d text-xs">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('kategori_id', $beritaAcara->kategori_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prioritas" class="block text-xs font-semibold text-slate-700 mb-1">Prioritas <span class="text-rose-500">*</span></label>
                    <select id="prioritas" name="prioritas" required class="input-3d text-xs">
                        @foreach ($priorities as $p)
                            <option value="{{ $p->value }}" {{ old('prioritas', $beritaAcara->prioritas->value) == $p->value ? 'selected' : '' }}>
                                {{ $p->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">Status Dokumen <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required class="input-3d text-xs">
                        @foreach ($statuses as $st)
                            <option value="{{ $st->value }}" {{ old('status', $beritaAcara->status->value) == $st->value ? 'selected' : '' }}>
                                {{ $st->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Identitas Pelapor & Lokasi -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">2</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Identitas Pelapor & Lokasi</h3>
                    <p class="text-[11px] text-slate-400">Data unit kerja dan pemohon layanan IT</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="pelapor_nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Pelapor <span class="text-rose-500">*</span></label>
                    <input type="text" id="pelapor_nama" name="pelapor_nama" value="{{ old('pelapor_nama', $reporter?->nama) }}" required class="input-3d text-xs">
                </div>

                <div>
                    <label for="pelapor_jabatan" class="block text-xs font-semibold text-slate-700 mb-1">Jabatan</label>
                    <input type="text" id="pelapor_jabatan" name="pelapor_jabatan" value="{{ old('pelapor_jabatan', $reporter?->jabatan) }}" class="input-3d text-xs">
                </div>

                <div>
                    <label for="pelapor_unit" class="block text-xs font-semibold text-slate-700 mb-1">Ruangan / Unit <span class="text-rose-500">*</span></label>
                    <input type="text" id="pelapor_unit" name="pelapor_unit" value="{{ old('pelapor_unit', $reporter?->unit) }}" required class="input-3d text-xs">
                </div>

                <div>
                    <label for="pelapor_kontak" class="block text-xs font-semibold text-slate-700 mb-1">Kontak</label>
                    <input type="text" id="pelapor_kontak" name="pelapor_kontak" value="{{ old('pelapor_kontak', $reporter?->kontak) }}" class="input-3d text-xs">
                </div>

                <div class="sm:col-span-2">
                    <label for="lokasi" class="block text-xs font-semibold text-slate-700 mb-1">Detail Lokasi Fisik <span class="text-rose-500">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $beritaAcara->lokasi) }}" required class="input-3d text-xs">
                </div>
            </div>
        </div>

        <!-- Section 3: Uraian Masalah & Teknis -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">3</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Detail Teknis & Penanganan</h3>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="keluhan" class="block text-xs font-semibold text-slate-700 mb-1">Keluhan / Masalah <span class="text-rose-500">*</span></label>
                    <textarea id="keluhan" name="keluhan" rows="3" required class="input-3d text-xs leading-relaxed">{{ old('keluhan', $beritaAcara->keluhan) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="hasil_pemeriksaan" class="block text-xs font-semibold text-slate-700 mb-1">Hasil Pemeriksaan Teknis</label>
                        <textarea id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="3" class="input-3d text-xs leading-relaxed">{{ old('hasil_pemeriksaan', $beritaAcara->hasil_pemeriksaan) }}</textarea>
                    </div>

                    <div>
                        <label for="penyebab" class="block text-xs font-semibold text-slate-700 mb-1">Akar Masalah / Penyebab</label>
                        <textarea id="penyebab" name="penyebab" rows="3" class="input-3d text-xs leading-relaxed">{{ old('penyebab', $beritaAcara->penyebab) }}</textarea>
                    </div>

                    <div>
                        <label for="tindakan" class="block text-xs font-semibold text-slate-700 mb-1">Tindakan / Solusi</label>
                        <textarea id="tindakan" name="tindakan" rows="3" class="input-3d text-xs leading-relaxed">{{ old('tindakan', $beritaAcara->tindakan) }}</textarea>
                    </div>

                    <div>
                        <label for="kebutuhan" class="block text-xs font-semibold text-slate-700 mb-1">Audit Kebutuhan Material / Suku Cadang</label>
                        <textarea id="kebutuhan" name="kebutuhan" rows="3" class="input-3d text-xs leading-relaxed">{{ old('kebutuhan', $beritaAcara->kebutuhan) }}</textarea>
                    </div>

                    <div>
                        <label for="kesimpulan" class="block text-xs font-semibold text-slate-700 mb-1">Kesimpulan Akhir</label>
                        <textarea id="kesimpulan" name="kesimpulan" rows="3" class="input-3d text-xs leading-relaxed">{{ old('kesimpulan', $beritaAcara->kesimpulan) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="tindak_lanjut" class="block text-xs font-semibold text-slate-700 mb-1">Rencana Tindak Lanjut</label>
                        <textarea id="tindak_lanjut" name="tindak_lanjut" rows="2" class="input-3d text-xs leading-relaxed">{{ old('tindak_lanjut', $beritaAcara->tindak_lanjut) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Audit Kebutuhan & Rekanan Vendor Pelaksana -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-sky-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-sky-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">4</div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-800">Audit Kebutuhan & Rekanan Vendor Pelaksana</h3>
                            <span class="badge-3d text-[10px] bg-sky-50 text-sky-700 border border-sky-200">Opsional / Pihak Ketiga</span>
                        </div>
                        <p class="text-[11px] text-slate-400">Pengalihan pekerjaan atau pengadaan suku cadang ke vendor rekanan IT RSUD</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center text-[11px] text-sky-700 bg-sky-50 px-3 py-1 rounded-lg border border-sky-200">
                    <svg class="w-4 h-4 mr-1 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Penyedia Jasa / Vendor
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nama_vendor" class="block text-xs font-semibold text-slate-700 mb-1">Nama Perusahaan / Rekanan Vendor</label>
                    <input type="text" id="nama_vendor" name="nama_vendor" value="{{ old('nama_vendor', $beritaAcara->nama_vendor) }}" placeholder="Contoh: PT. Telkom Indonesia / CV. Maluku Cyber Solusi" class="input-3d text-xs">
                </div>

                <div>
                    <label for="kontak_vendor" class="block text-xs font-semibold text-slate-700 mb-1">Kontak Person / Telepon Vendor</label>
                    <input type="text" id="kontak_vendor" name="kontak_vendor" value="{{ old('kontak_vendor', $beritaAcara->kontak_vendor) }}" placeholder="Contoh: 08124233xxxx (Bpk. Fajar - Project Officer)" class="input-3d text-xs">
                </div>

                <div class="sm:col-span-2">
                    <label for="catatan_vendor" class="block text-xs font-semibold text-slate-700 mb-1">Instruksi & Catatan Khusus Pekerjaan Vendor</label>
                    <textarea id="catatan_vendor" name="catatan_vendor" rows="2" placeholder="Contoh: Pemasangan grounding server, penarikan kabel fiber optik gedung bedah sentral..." class="input-3d text-xs leading-relaxed">{{ old('catatan_vendor', $beritaAcara->catatan_vendor) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card-3d p-4 bg-white flex items-center justify-end">
            <button type="submit" class="btn-3d-primary px-6 py-2.5 text-xs shadow-lg">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
