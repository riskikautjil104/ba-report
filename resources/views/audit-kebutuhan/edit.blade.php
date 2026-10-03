@extends('layouts.app', ['heading' => 'Edit Hasil Wawancara Audit Kebutuhan'])

@section('content')
<div class="w-full space-y-6">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('audit-kebutuhan.show', $audit) }}" class="btn-3d-light px-3.5 py-2 text-xs">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Detail</span>
        </a>
        <span class="text-xs font-mono font-bold text-sky-700 bg-sky-50 px-2.5 py-1 rounded-lg border border-sky-200">
            {{ $audit->nomor }}
        </span>
    </div>

    @if ($errors->any())
        <div class="card-3d p-4 bg-rose-50 border-rose-200 text-rose-800 text-xs">
            <div class="font-bold mb-1 flex items-center gap-1.5 text-rose-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Periksa kembali isian formulir:
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('audit-kebutuhan.update', $audit) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Lokasi & Responden Wawancara -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">1</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Identitas Ruangan & Responden Wawancara</h3>
                    <p class="text-[11px] text-slate-400">Unit kerja rumah sakit yang dikunjungi dan orang yang ditanyai</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label for="tanggal_audit" class="block text-xs font-semibold text-slate-700 mb-1">
                        Tanggal Wawancara <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="tanggal_audit" 
                        name="tanggal_audit" 
                        value="{{ old('tanggal_audit', $audit->tanggal_audit ? $audit->tanggal_audit->format('Y-m-d') : '') }}" 
                        required 
                        class="input-3d text-xs w-full"
                    >
                </div>

                <div>
                    <label for="unit_kerja" class="block text-xs font-semibold text-slate-700 mb-1">
                        Ruangan / Unit Kerja <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="unit_kerja" 
                        name="unit_kerja" 
                        value="{{ old('unit_kerja', $audit->unit_kerja) }}" 
                        required 
                        placeholder="Contoh: IGD, ICU, Poli Jantung, Farmasi" 
                        class="input-3d text-xs w-full"
                    >
                </div>

                <div>
                    <label for="lokasi_gedung" class="block text-xs font-semibold text-slate-700 mb-1">
                        Detail Lokasi / Gedung <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="lokasi_gedung" 
                        name="lokasi_gedung" 
                        value="{{ old('lokasi_gedung', $audit->lokasi_gedung) }}" 
                        required 
                        placeholder="Gedung B Lantai 2 Nurse Station" 
                        class="input-3d text-xs w-full"
                    >
                </div>

                <div>
                    <label for="nama_responden" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Staf / Responden <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama_responden" 
                        name="nama_responden" 
                        value="{{ old('nama_responden', $audit->nama_responden) }}" 
                        required 
                        placeholder="dr. Ahmad, Sp.A / Ns. Siti" 
                        class="input-3d text-xs w-full"
                    >
                </div>

                <div>
                    <label for="jabatan_responden" class="block text-xs font-semibold text-slate-700 mb-1">
                        Jabatan / Profesi
                    </label>
                    <input 
                        type="text" 
                        id="jabatan_responden" 
                        name="jabatan_responden" 
                        value="{{ old('jabatan_responden', $audit->jabatan_responden) }}" 
                        placeholder="Kepala Ruangan / Dokter / Perawat" 
                        class="input-3d text-xs w-full"
                    >
                </div>

                <div>
                    <label for="kontak_responden" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nomor Kontak / WhatsApp
                    </label>
                    <input 
                        type="text" 
                        id="kontak_responden" 
                        name="kontak_responden" 
                        value="{{ old('kontak_responden', $audit->kontak_responden) }}" 
                        placeholder="0812xxxxxxxx" 
                        class="input-3d text-xs w-full"
                    >
                </div>
            </div>
        </div>

        <!-- Section 2: Keluhan & Kendala Yang Dialami User -->
        <div class="card-3d p-6 bg-white space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 font-bold flex items-center justify-center text-xs">2</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Keluhan & Kendala Yang Dialami User</h3>
                    <p class="text-[11px] text-slate-400">Apa saja permasalahan yang mereka hadapi dalam operasional sehari-hari</p>
                </div>
            </div>

            <div>
                <label for="keluhan_kendala" class="block text-xs font-semibold text-slate-700 mb-1">
                    Uraian Masalah / Kendala Lapangan <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    id="keluhan_kendala" 
                    name="keluhan_kendala" 
                    rows="3" 
                    required 
                    placeholder="Contoh: Jaringan internet sering putus saat input resep SIMRS, PC kasir sering restart sendiri, printer cetak barcode label macet dan buram..." 
                    class="input-3d text-xs leading-relaxed w-full"
                >{{ old('keluhan_kendala', $audit->keluhan_kendala) }}</textarea>
            </div>
        </div>

        <!-- Section 3: Ekspektasi & Maunya Mereka -->
        <div class="card-3d p-6 bg-white space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">3</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Ekspektasi & Kebutuhan ("Maunya Mereka")</h3>
                    <p class="text-[11px] text-slate-400">Apa yang diinginkan user untuk menyelesaikan kendala tersebut atau mempermudah tugas mereka</p>
                </div>
            </div>

            <div>
                <label for="keinginan_harapan" class="block text-xs font-semibold text-slate-700 mb-1">
                    Kebutuhan / Permintaan User <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    id="keinginan_harapan" 
                    name="keinginan_harapan" 
                    rows="3" 
                    required 
                    placeholder="Contoh: Minta penambahan 1 unit Access Point WiFi khusus poli, ganti printer barcode thermal baru, minta upgrade RAM PC agar tidak lemot saat buka SIMRS..." 
                    class="input-3d text-xs leading-relaxed w-full"
                >{{ old('keinginan_harapan', $audit->keinginan_harapan) }}</textarea>
            </div>
        </div>

        <!-- Section 4: Analisis Teknis IT & Penugasan Vendor -->
        <div class="card-3d p-6 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-sky-100">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">4</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Analisis Tim IT, Prioritas & Rekanan Vendor</h3>
                    <p class="text-[11px] text-slate-400">Rencana solusi dari tim IT dan apakah pekerjaan akan diserahkan ke vendor pihak ketiga</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="kategori_id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Kategori Masalah <span class="text-rose-500">*</span>
                    </label>
                    <select id="kategori_id" name="kategori_id" required class="input-3d text-xs w-full">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('kategori_id', $audit->kategori_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prioritas" class="block text-xs font-semibold text-slate-700 mb-1">
                        Tingkat Prioritas <span class="text-rose-500">*</span>
                    </label>
                    <select id="prioritas" name="prioritas" required class="input-3d text-xs w-full">
                        @foreach ($priorities as $p)
                            <option value="{{ $p->value }}" {{ old('prioritas', $audit->prioritas?->value ?? 'sedang') == $p->value ? 'selected' : '' }}>
                                {{ $p->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">
                        Status Audit <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" name="status" required class="input-3d text-xs w-full">
                        @foreach ($statuses as $st)
                            <option value="{{ $st->value }}" {{ old('status', $audit->status?->value ?? 'selesai_wawancara') == $st->value ? 'selected' : '' }}>
                                {{ $st->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <label for="rekomendasi_it" class="block text-xs font-semibold text-slate-700 mb-1">
                        Rekomendasi / Solusi Teknis Tim IT
                    </label>
                    <textarea 
                        id="rekomendasi_it" 
                        name="rekomendasi_it" 
                        rows="2" 
                        placeholder="Contoh: Pengadaan switch gigabit 8-port, penarikan kabel LAN baru dari ruang server, atau penyerahan pekerjaan instalasi ke vendor..." 
                        class="input-3d text-xs leading-relaxed w-full"
                    >{{ old('rekomendasi_it', $audit->rekomendasi_it) }}</textarea>
                </div>
            </div>

            <!-- Bagian Vendor (Opsional) -->
            <div class="pt-4 border-t border-sky-100 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Rencana Penyerahan ke Rekanan Vendor (Opsional)
                    </span>
                    <span class="text-[10px] text-slate-400">Jika pengadaan/pekerjaan butuh pihak ketiga</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_vendor" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Rekanan / Calon Vendor
                        </label>
                        <input 
                            type="text" 
                            id="nama_vendor" 
                            name="nama_vendor" 
                            value="{{ old('nama_vendor', $audit->nama_vendor) }}" 
                            placeholder="Contoh: PT. Telkom / CV. Maluku Cyber Solusi" 
                            class="input-3d text-xs w-full"
                        >
                    </div>

                    <div>
                        <label for="catatan_vendor" class="block text-xs font-semibold text-slate-700 mb-1">
                            Spesifikasi / Instruksi untuk Vendor
                        </label>
                        <input 
                            type="text" 
                            id="catatan_vendor" 
                            name="catatan_vendor" 
                            value="{{ old('catatan_vendor', $audit->catatan_vendor) }}" 
                            placeholder="Pengadaan kabel FO outdoor 100m dan instalasi" 
                            class="input-3d text-xs w-full"
                        >
                    </div>
                </div>
            </div>
        <!-- Section 5: Tanda Tangan Canvas Langsung Perequest / Responden -->
        <div class="card-3d p-6 bg-white space-y-4" x-data="signatureAuditEdit('{{ $audit->tanda_tangan_responden }}')">
            <div class="flex items-center justify-between pb-3 border-b border-sky-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-sky-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">5</div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Tanda Tangan Langsung Perequest / Responden Ruangan</h3>
                        <p class="text-[11px] text-slate-400">Goreskan tanda tangan langsung di layar (HP/Tablet/Mouse) sebagai bukti validasi kebutuhan</p>
                    </div>
                </div>
                <button type="button" @click="clear()" class="btn-3d-light px-3 py-1.5 text-xs text-rose-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus & Tanda Tangan Ulang</span>
                </button>
            </div>

            <div class="relative rounded-2xl border-2 border-dashed border-sky-300 bg-slate-50 overflow-hidden shadow-inner touch-none">
                <canvas 
                    id="signatureCanvasEdit" 
                    width="700" 
                    height="200" 
                    class="w-full h-48 bg-white cursor-crosshair block"
                    @mousedown="startDrawing($event)"
                    @mousemove="draw($event)"
                    @mouseup="stopDrawing()"
                    @mouseleave="stopDrawing()"
                    @touchstart.prevent="startTouch($event)"
                    @touchmove.prevent="drawTouch($event)"
                    @touchend.prevent="stopDrawing()"
                ></canvas>
                <div class="absolute bottom-2 right-3 pointer-events-none text-[10px] text-slate-400 font-mono bg-white/80 px-2 py-0.5 rounded border border-slate-200">
                    ✍️ Sentuh / Tulis di sini
                </div>
            </div>

            <input type="hidden" name="tanda_tangan_responden" id="tandaTanganInputEdit" x-model="signatureData">

            <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                <span :class="hasSignature ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                    <span x-text="hasSignature ? '✓ Tanda tangan tersimpan' : '(Opsional) Belum ditandatangani'"></span>
                </span>
                <span class="text-[11px] text-slate-400">Dapat ditandatangani langsung di smartphone saat keliling</span>
            </div>
        </div>

        <div class="card-3d p-4 bg-white flex items-center justify-between">
            <a href="{{ route('audit-kebutuhan.show', $audit) }}" class="btn-3d-light px-4 py-2.5 text-xs">
                Batal
            </a>
            <button type="submit" class="btn-3d-primary px-6 py-2.5 text-xs shadow-lg flex items-center gap-2">
                <span>Simpan Perubahan Hasil Wawancara</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>
        </div>
    </form>
</div>

<script>
    function signatureAuditEdit(existingSignature) {
        return {
            canvas: null,
            ctx: null,
            isDrawing: false,
            hasSignature: !!existingSignature,
            signatureData: existingSignature || '',
            init() {
                this.$nextTick(() => {
                    this.canvas = document.getElementById('signatureCanvasEdit');
                    if (!this.canvas) return;
                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.lineWidth = 2.5;
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                    this.ctx.strokeStyle = '#0f172a';

                    if (existingSignature) {
                        const img = new Image();
                        img.onload = () => {
                            this.ctx.drawImage(img, 0, 0, this.canvas.width, this.canvas.height);
                        };
                        img.src = existingSignature;
                    }

                    // Attach to parent form submit
                    const form = this.canvas.closest('form');
                    if (form) {
                        form.addEventListener('submit', () => {
                            if (this.hasSignature && !this.signatureData.startsWith('data:image')) {
                                this.signatureData = this.canvas.toDataURL('image/png');
                            }
                            document.getElementById('tandaTanganInputEdit').value = this.signatureData;
                        });
                    }
                });
            },
            getPos(e) {
                const rect = this.canvas.getBoundingClientRect();
                const scaleX = this.canvas.width / rect.width;
                const scaleY = this.canvas.height / rect.height;
                return {
                    x: (e.clientX - rect.left) * scaleX,
                    y: (e.clientY - rect.top) * scaleY
                };
            },
            startDrawing(e) {
                this.isDrawing = true;
                const pos = this.getPos(e);
                this.ctx.beginPath();
                this.ctx.moveTo(pos.x, pos.y);
            },
            draw(e) {
                if (!this.isDrawing) return;
                const pos = this.getPos(e);
                this.ctx.lineTo(pos.x, pos.y);
                this.ctx.stroke();
                this.hasSignature = true;
            },
            startTouch(e) {
                if (e.touches.length === 1) {
                    this.isDrawing = true;
                    const touch = e.touches[0];
                    const pos = this.getPos(touch);
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                }
            },
            drawTouch(e) {
                if (!this.isDrawing || e.touches.length !== 1) return;
                const touch = e.touches[0];
                const pos = this.getPos(touch);
                this.ctx.lineTo(pos.x, pos.y);
                this.ctx.stroke();
                this.hasSignature = true;
            },
            stopDrawing() {
                this.isDrawing = false;
                if (this.hasSignature) {
                    this.signatureData = this.canvas.toDataURL('image/png');
                    document.getElementById('tandaTanganInputEdit').value = this.signatureData;
                }
            },
            clear() {
                if (!this.ctx || !this.canvas) return;
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                this.hasSignature = false;
                this.signatureData = '';
                document.getElementById('tandaTanganInputEdit').value = '';
            }
        };
    }
</script>
@endsection
