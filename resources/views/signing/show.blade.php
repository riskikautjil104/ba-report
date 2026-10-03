@extends('layouts.guest', ['title' => 'Tanda Tangan Berita Acara'])

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-2xl px-4 py-6" x-data="signaturePad()">
    <!-- Header -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-20 h-20 p-2 rounded-2xl bg-white shadow-xl border border-sky-100 mx-auto mb-3">
            <img src="{{ asset('logo/logoresmi.png') }}" alt="Logo RSUD Dr. H. Chasan Boesoirie" class="w-full h-full object-contain">
        </div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Tanda Tangan Berita Acara Digital</h1>
        <p class="text-xs text-slate-500 font-medium">Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate</p>
    </div>

    <!-- Document Summary Card -->
    <div class="card-3d p-6 bg-white mb-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-sky-100">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nomor Berita Acara</span>
                <div class="text-lg font-black font-mono text-sky-800">{{ $beritaAcara->nomor }}</div>
            </div>
            <span class="badge-3d text-xs font-bold {{ $beritaAcara->status->badgeClasses() }}">
                {{ $beritaAcara->status->label() }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3 text-xs">
            <div>
                <span class="text-slate-400 block font-medium">Tanggal:</span>
                <span class="font-bold text-slate-700">{{ $beritaAcara->tanggal->translatedFormat('d F Y') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">Lokasi / Unit:</span>
                <span class="font-bold text-slate-700">{{ $beritaAcara->lokasi }}</span>
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-sky-50/60 border border-sky-100 text-xs space-y-2">
            <div>
                <span class="text-slate-500 font-semibold block">Keluhan / Masalah:</span>
                <p class="text-slate-800 font-medium">{{ $beritaAcara->keluhan }}</p>
            </div>
            @if ($beritaAcara->tindakan)
                <div>
                    <span class="text-slate-500 font-semibold block">Tindakan Penyelesaian:</span>
                    <p class="text-slate-800 font-medium">{{ $beritaAcara->tindakan }}</p>
                </div>
            @endif
        </div>

        <!-- Signer Identity Notice -->
        <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs">
            <span class="text-amber-800 font-bold block">Pernyataan Penandatangan:</span>
            <p class="text-amber-900 mt-0.5">
                Saya yang bertanda tangan di bawah ini menyatakan bahwa pekerjaan / perbaikan telah diperiksa dan disetujui:
            </p>
            <div class="mt-2 font-bold text-slate-800 flex items-center justify-between">
                <span>{{ $participant->nama }} ({{ $participant->unit }})</span>
                <span class="badge-3d bg-amber-100 text-amber-900 text-[10px]">{{ $participant->participant_type->label() }}</span>
            </div>
        </div>
    </div>

    <!-- Signature Canvas Card -->
    <div class="card-3d p-6 bg-white space-y-4">
        <div class="flex items-center justify-between">
            <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                <span>Goreskan Tanda Tangan Anda di Kotak Berikut:</span>
            </label>
            <button type="button" @click="clear()" class="btn-3d-light px-3 py-1.5 text-xs text-rose-600">
                Bersihkan
            </button>
        </div>

        <div class="relative rounded-2xl border-2 border-dashed border-sky-200 bg-slate-50/50 overflow-hidden shadow-inner touch-none">
            <canvas 
                id="signatureCanvas" 
                width="600" 
                height="220" 
                class="w-full h-52 bg-white cursor-crosshair"
                @mousedown="startDrawing($event)"
                @mousemove="draw($event)"
                @mouseup="stopDrawing()"
                @mouseleave="stopDrawing()"
                @touchstart.prevent="startTouch($event)"
                @touchmove.prevent="drawTouch($event)"
                @touchend.prevent="stopDrawing()"
            ></canvas>
            <div class="absolute bottom-2 right-3 pointer-events-none text-[10px] text-slate-300 font-mono">
                Layar Sentuh / Mouse
            </div>
        </div>

        <form method="POST" action="{{ route('sign.process', $token) }}" @submit="handleSubmit($event)">
            @csrf
            <input type="hidden" name="signature" id="signatureInput" x-model="signatureData">

            <div class="pt-2">
                <button type="submit" :disabled="!hasSignature" :class="hasSignature ? 'btn-3d-primary' : 'opacity-50 cursor-not-allowed bg-slate-300'" class="w-full py-3 text-sm font-bold shadow-lg">
                    ✓ Konfirmasi & Kirim Tanda Tangan Digital
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function signaturePad() {
        return {
            canvas: null,
            ctx: null,
            isDrawing: false,
            hasSignature: false,
            signatureData: '',
            init() {
                this.canvas = document.getElementById('signatureCanvas');
                this.ctx = this.canvas.getContext('2d');
                this.ctx.lineWidth = 2.5;
                this.ctx.lineCap = 'round';
                this.ctx.lineJoin = 'round';
                this.ctx.strokeStyle = '#0f172a';
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
            },
            clear() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                this.hasSignature = false;
                this.signatureData = '';
            },
            handleSubmit(e) {
                if (!this.hasSignature) {
                    e.preventDefault();
                    alert('Silakan goreskan tanda tangan Anda terlebih dahulu!');
                    return;
                }
                this.signatureData = this.canvas.toDataURL('image/png');
                document.getElementById('signatureInput').value = this.signatureData;
            }
        };
    }
</script>
@endsection
