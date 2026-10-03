@extends('layouts.app', ['heading' => 'Buat Berita Acara Baru'])

@section('content')
<div class="w-full space-y-6">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('berita-acara.index') }}" class="btn-3d-light px-3.5 py-2 text-xs">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar</span>
        </a>
        <span class="text-xs font-mono font-bold text-slate-400">Preview Nomor: {{ $nextNomor }}</span>
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

    @if (isset($fromAudit) && $fromAudit)
        <div class="card-3d p-4 bg-sky-50 border-sky-200 text-sky-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-bold">Dibuat Berdasarkan Lembar Audit Kebutuhan:</span>
                    <span class="font-mono ml-1">{{ $fromAudit->nomor }} ({{ $fromAudit->unit_kerja }})</span>
                </div>
            </div>
            <span class="badge-3d text-[10px] bg-sky-100 text-sky-700">Form Terisi Otomatis</span>
        </div>
    @endif

    <form method="POST" action="{{ route('berita-acara.store') }}" class="space-y-6">
        @csrf
        @if (isset($fromAudit) && $fromAudit)
            <input type="hidden" name="from_audit_id" value="{{ $fromAudit->id }}">
        @endif

        <!-- Section 1: Informasi Dokumen & Klasifikasi -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">1</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Informasi & Klasifikasi Berita Acara</h3>
                    <p class="text-[11px] text-slate-400">Data nomor dokumen otomatis dan tingkat urgensi masalah</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor BA (Otomatis)</label>
                    <input type="text" value="{{ $nextNomor }}" disabled class="input-3d bg-slate-50 text-slate-500 font-mono text-xs cursor-not-allowed">
                </div>

                <div>
                    <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', (isset($fromAudit) && $fromAudit && $fromAudit->tanggal_audit) ? $fromAudit->tanggal_audit->format('Y-m-d') : date('Y-m-d')) }}" required class="input-3d text-xs">
                </div>

                <div>
                    <label for="kategori_id" class="block text-xs font-semibold text-slate-700 mb-1">Kategori Masalah <span class="text-rose-500">*</span></label>
                    <select id="kategori_id" name="kategori_id" required class="input-3d text-xs">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('kategori_id', $fromAudit?->kategori_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prioritas" class="block text-xs font-semibold text-slate-700 mb-1">Prioritas <span class="text-rose-500">*</span></label>
                    <select id="prioritas" name="prioritas" required class="input-3d text-xs">
                        @foreach ($priorities as $p)
                            <option value="{{ $p->value }}" {{ old('prioritas', $fromAudit?->prioritas?->value ?? 'sedang') == $p->value ? 'selected' : '' }}>
                                {{ $p->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Identitas Pelapor & Lokasi Kejadian -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">2</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Identitas Pelapor & Lokasi Kejadian</h3>
                    <p class="text-[11px] text-slate-400">Informasi unit pemohon / pelapor masalah di lingkungan RSUD</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="pelapor_nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Pelapor <span class="text-rose-500">*</span></label>
                    <input type="text" id="pelapor_nama" name="pelapor_nama" value="{{ old('pelapor_nama', $fromAudit?->nama_responden) }}" required placeholder="Contoh: dr. Ahmad, Sp.A / Ns. Siti" class="input-3d text-xs">
                </div>

                <div>
                    <label for="pelapor_jabatan" class="block text-xs font-semibold text-slate-700 mb-1">Jabatan / Profesi</label>
                    <input type="text" id="pelapor_jabatan" name="pelapor_jabatan" value="{{ old('pelapor_jabatan', $fromAudit?->jabatan_responden) }}" placeholder="Contoh: Kepala Ruangan / Perawat / Dokter" class="input-3d text-xs">
                </div>

                <div>
                    <label for="pelapor_unit" class="block text-xs font-semibold text-slate-700 mb-1">Ruangan / Unit Kerja <span class="text-rose-500">*</span></label>
                    <input type="text" id="pelapor_unit" name="pelapor_unit" value="{{ old('pelapor_unit', $fromAudit?->unit_kerja) }}" required placeholder="Contoh: IGD, Rawat Inap Melati, Poli Anak" class="input-3d text-xs">
                </div>

                <div>
                    <label for="pelapor_kontak" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Kontak / WhatsApp</label>
                    <input type="text" id="pelapor_kontak" name="pelapor_kontak" value="{{ old('pelapor_kontak', $fromAudit?->kontak_responden) }}" placeholder="Contoh: 0812xxxxxxxx" class="input-3d text-xs">
                </div>

                <div class="sm:col-span-2">
                    <label for="lokasi" class="block text-xs font-semibold text-slate-700 mb-1">Detail Lokasi Fisik / Gedung <span class="text-rose-500">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $fromAudit ? ($fromAudit->lokasi_gedung ?: $fromAudit->unit_kerja) : '') }}" required placeholder="Contoh: Gedung A Lantai 2, Ruang Nurse Station ICU" class="input-3d text-xs">
                </div>
            </div>
        </div>

        <!-- Section 3: Uraian Keluhan & Permasalahan -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">3</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Uraian Keluhan & Permasalahan</h3>
                    <p class="text-[11px] text-slate-400">Deskripsi kendala atau permintaan dukungan teknis yang dilaporkan</p>
                </div>
            </div>

            <div>
                <label for="keluhan" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Keluhan / Permasalahan <span class="text-rose-500">*</span></label>
                <textarea id="keluhan" name="keluhan" rows="3" required placeholder="Jelaskan secara rinci permasalahan perangkat/jaringan/sistem yang dialami..." class="input-3d text-xs leading-relaxed">{{ old('keluhan', $fromAudit?->keluhan_kendala) }}</textarea>
            </div>
        </div>

        <!-- Section 4: Hasil Pemeriksaan & Analisis Teknis -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">4</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Hasil Pemeriksaan Teknis & Penyebab</h3>
                    <p class="text-[11px] text-slate-400">Temuan tim IT Ruang IT di lapangan dan analisis akar masalah</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="hasil_pemeriksaan" class="block text-xs font-semibold text-slate-700 mb-1">Hasil Pemeriksaan Teknis</label>
                    <textarea id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="3" placeholder="Pengecekan fisik perangkat, konektivitas ping, log error aplikasi..." class="input-3d text-xs leading-relaxed">{{ old('hasil_pemeriksaan', $fromAudit?->analisis_it) }}</textarea>
                </div>

                <div>
                    <label for="penyebab" class="block text-xs font-semibold text-slate-700 mb-1">Akar Masalah / Penyebab Kerusakan</label>
                    <textarea id="penyebab" name="penyebab" rows="3" placeholder="Kabel UTP terputus, power supply rusak, database lock..." class="input-3d text-xs leading-relaxed">{{ old('penyebab') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 5: Tindakan & Audit Kebutuhan Material -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">5</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Tindakan Perbaikan & Audit Kebutuhan Material</h3>
                    <p class="text-[11px] text-slate-400">Langkah penyelesaian tim IT dan spesifikasi kebutuhan material/perangkat yang diaudit</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tindakan" class="block text-xs font-semibold text-slate-700 mb-1">Tindakan / Solusi Yang Dikerjakan</label>
                    <textarea id="tindakan" name="tindakan" rows="3" placeholder="Crimping ulang konektor RJ45, setting IP static, backup database SIMRS..." class="input-3d text-xs leading-relaxed">{{ old('tindakan', $fromAudit?->rekomendasi_it) }}</textarea>
                </div>

                <div>
                    <label for="kebutuhan" class="block text-xs font-semibold text-slate-700 mb-1">Audit Kebutuhan Material / Suku Cadang</label>
                    <textarea id="kebutuhan" name="kebutuhan" rows="3" placeholder="Kabel FO 100m, Switch Gigabit Managed 24-Port, SSD NVMe 512GB, Cartridge Laser..." class="input-3d text-xs leading-relaxed">{{ old('kebutuhan', $fromAudit?->keinginan_harapan) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 6: Penugasan Vendor Rekanan (Pihak Ketiga) -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-sky-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-sky-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">6</div>
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
                    <input type="text" id="nama_vendor" name="nama_vendor" value="{{ old('nama_vendor', $fromAudit?->nama_vendor ?? (auth()->user()->isVendor() ? auth()->user()->name : '')) }}" placeholder="Contoh: PT. Telkom Indonesia / CV. Maluku Cyber Solusi" class="input-3d text-xs">
                </div>

                <div>
                    <label for="kontak_vendor" class="block text-xs font-semibold text-slate-700 mb-1">Kontak Person / Telepon Vendor</label>
                    <input type="text" id="kontak_vendor" name="kontak_vendor" value="{{ old('kontak_vendor', $fromAudit?->kontak_vendor) }}" placeholder="Contoh: 08124233xxxx (Bpk. Fajar - Project Officer)" class="input-3d text-xs">
                </div>

                <div class="sm:col-span-2">
                    <label for="catatan_vendor" class="block text-xs font-semibold text-slate-700 mb-1">Instruksi & Catatan Khusus Pekerjaan Vendor</label>
                    <textarea id="catatan_vendor" name="catatan_vendor" rows="2" placeholder="Contoh: Pemasangan grounding server, penarikan kabel fiber optik gedung bedah sentral, batas pengerjaan 3 hari kalender..." class="input-3d text-xs leading-relaxed">{{ old('catatan_vendor', $fromAudit?->catatan_vendor) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 7: Kesimpulan & Tindak Lanjut -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">7</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Kesimpulan Akhir & Tindak Lanjut</h3>
                    <p class="text-[11px] text-slate-400">Status akhir penanganan dan rencana monitoring lanjutan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="kesimpulan" class="block text-xs font-semibold text-slate-700 mb-1">Kesimpulan</label>
                    <textarea id="kesimpulan" name="kesimpulan" rows="3" placeholder="Audit kebutuhan selesai dan diserahkan ke vendor / perangkat normal kembali..." class="input-3d text-xs leading-relaxed">{{ old('kesimpulan') }}</textarea>
                </div>

                <div>
                    <label for="tindak_lanjut" class="block text-xs font-semibold text-slate-700 mb-1">Rencana Tindak Lanjut</label>
                    <textarea id="tindak_lanjut" name="tindak_lanjut" rows="3" placeholder="Monitoring berkala serah terima barang dan pengujian fungsional..." class="input-3d text-xs leading-relaxed">{{ old('tindak_lanjut') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Form Submit Bar -->
        <div class="card-3d p-4 bg-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <input type="hidden" name="status" id="statusField" value="draft">
                <button type="submit" onclick="document.getElementById('statusField').value='draft'" class="btn-3d-light px-5 py-2.5 text-xs">
                    Simpan Sebagai Draft
                </button>
            </div>
            <button type="submit" onclick="document.getElementById('statusField').value='dalam_penanganan'" class="btn-3d-primary px-6 py-2.5 text-xs shadow-lg">
                Simpan & Lanjutkan Penanganan
                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection
