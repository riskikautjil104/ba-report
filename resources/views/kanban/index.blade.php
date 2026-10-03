@extends('layouts.app', ['heading' => 'Kanban Pemantauan Vendor'])

@section('content')
<div class="space-y-6 w-full" x-data="kanbanBoard()">
    <!-- Top Hero Banner -->
    <div class="card-3d p-6 bg-gradient-to-r from-sky-50 via-white to-sky-100/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-100/80 text-sky-800 text-xs font-bold border border-sky-200 mb-2">
                <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                Workflow Kanban Lapangan & Rekanan Vendor
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">
                Papan Pemantauan Pekerjaan Vendor & IT
            </h1>
            <p class="text-xs text-slate-600 mt-1 max-w-3xl leading-relaxed">
                Pantau progres penanganan insiden, perbaikan hardware, instalasi jaringan, dan pemeliharaan oleh <strong>rekanan vendor pihak ketiga</strong> secara visual, transparan, dan terstruktur antar tahapan kerja.
            </p>
        </div>
        <div class="flex items-center flex-wrap gap-2.5 shrink-0">
            @if ($canManage)
                <a href="{{ route('berita-acara.create') }}" class="btn-3d-primary px-4 py-2.5 text-xs shadow-md flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Catat Pekerjaan</span>
                </a>
            @else
                <span class="badge-3d bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs py-2 px-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Mode Pemantauan Eksekutif</span>
                </span>
            @endif

            <a href="{{ route('berita-acara.index') }}" class="btn-3d-light px-3.5 py-2.5 text-xs text-slate-600 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span>Tampilan Tabel</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card-3d p-4 bg-white">
        <form method="GET" action="{{ route('kanban.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
            <!-- Search Input -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nomor, lokasi, vendor, keluhan..." 
                    class="input-3d input-3d-icon text-xs py-2 w-full"
                >
            </div>

            <!-- Filter Vendor -->
            <div>
                <select name="vendor" onchange="this.form.submit()" class="input-3d text-xs py-2 w-full">
                    <option value="">Semua Vendor Rekanan</option>
                    @foreach ($vendorsList as $v)
                        <option value="{{ $v }}" {{ request('vendor') === $v ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kategori IT -->
            <div>
                <select name="kategori_id" onchange="this.form.submit()" class="input-3d text-xs py-2 w-full">
                    <option value="">Semua Kategori IT</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('kategori_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Toggle Filter Vendor Only -->
            <div class="flex items-center justify-between sm:justify-start gap-3">
                <label class="inline-flex items-center cursor-pointer select-none">
                    <input 
                        type="checkbox" 
                        name="vendor_only" 
                        value="1" 
                        {{ $vendorOnly ? 'checked' : '' }} 
                        onchange="this.form.submit()"
                        class="rounded border-sky-300 text-sky-600 shadow-sm focus:ring-sky-500 w-4 h-4"
                    >
                    <span class="ml-2 text-xs font-semibold text-slate-700">Khusus Vendor</span>
                </label>
                <button type="submit" class="btn-3d-secondary px-3 py-1.5 text-xs">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- Live Toast Notification Container -->
    <div 
        x-show="toast.visible" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-5 right-5 z-50 p-4 rounded-2xl shadow-xl border text-xs font-semibold flex items-center gap-3 backdrop-blur-md"
        :class="toast.type === 'success' ? 'bg-emerald-50/95 border-emerald-300 text-emerald-900' : 'bg-rose-50/95 border-rose-300 text-rose-900'"
        style="display: none;"
    >
        <span x-text="toast.message"></span>
        <button type="button" @click="toast.visible = false" class="text-slate-400 hover:text-slate-600">
            &times;
        </button>
    </div>

    <!-- Horizontal Kanban Board Grid -->
    <div class="overflow-x-auto pb-4">
        <div class="flex gap-5 min-w-[1250px] items-start">
            @foreach ($columns as $statusKey => $column)
                <div 
                    class="flex-1 bg-slate-50/90 rounded-2xl border border-sky-100/80 p-3.5 flex flex-col min-h-[580px] shadow-sm transition"
                    data-status="{{ $statusKey }}"
                    @dragover.prevent="onDragOver($event)"
                    @dragleave="onDragLeave($event)"
                    @drop="onDrop($event, '{{ $statusKey }}')"
                >
                    <!-- Column Header -->
                    <div class="pb-3 border-b border-sky-100/70 mb-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full 
                                    @if($column['color'] === 'slate') bg-slate-400
                                    @elseif($column['color'] === 'sky') bg-sky-500
                                    @elseif($column['color'] === 'amber') bg-amber-500
                                    @elseif($column['color'] === 'indigo') bg-indigo-500
                                    @elseif($column['color'] === 'emerald') bg-emerald-500
                                    @endif">
                                </span>
                                <span>{{ $column['title'] }}</span>
                            </h3>
                            <span class="badge-3d text-[11px] font-black px-2 py-0.5 
                                @if($column['color'] === 'slate') bg-slate-200 text-slate-800
                                @elseif($column['color'] === 'sky') bg-sky-100 text-sky-800
                                @elseif($column['color'] === 'amber') bg-amber-100 text-amber-800
                                @elseif($column['color'] === 'indigo') bg-indigo-100 text-indigo-800
                                @elseif($column['color'] === 'emerald') bg-emerald-100 text-emerald-800
                                @endif">
                                {{ $column['cards']->count() }}
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 line-clamp-1">
                            {{ $column['description'] }}
                        </p>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 space-y-3 kanban-column-body min-h-[450px]" id="column-{{ $statusKey }}">
                        @forelse ($column['cards'] as $card)
                            <div 
                                class="card-3d p-4 bg-white space-y-2.5 cursor-grab active:cursor-grabbing hover:shadow-lg transition-all duration-200 group border border-sky-100/70"
                                draggable="{{ $canManage ? 'true' : 'false' }}"
                                data-id="{{ $card->id }}"
                                data-status="{{ $statusKey }}"
                                @dragstart="onDragStart($event, {{ $card->id }})"
                                @dragend="onDragEnd($event)"
                            >
                                <!-- Card Header: Nomor & Prioritas -->
                                <div class="flex items-center justify-between gap-2">
                                    <a href="{{ route('berita-acara.show', $card) }}" class="text-xs font-mono font-bold text-sky-700 hover:text-sky-900 truncate">
                                        {{ $card->nomor }}
                                    </a>
                                    <span class="badge-3d text-[9px] py-0.5 px-1.5 {{ $card->prioritas->badgeClasses() }}">
                                        {{ $card->prioritas->label() }}
                                    </span>
                                </div>

                                <!-- Card Vendor Badge (If Any) -->
                                @if ($card->nama_vendor)
                                    <div class="p-1.5 rounded-lg bg-sky-50/70 border border-sky-200/60 flex items-center gap-1.5 text-[11px] text-sky-900 font-bold">
                                        <svg class="w-3.5 h-3.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate">{{ $card->nama_vendor }}</span>
                                    </div>
                                @endif

                                <!-- Lokasi & Unit -->
                                <div class="text-[11px] text-slate-600 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate font-medium">{{ $card->lokasi }}</span>
                                </div>

                                <!-- Keluhan / Deskripsi Singkat -->
                                <p class="text-[11px] text-slate-600 line-clamp-2 leading-relaxed bg-slate-50/50 p-2 rounded-lg border border-slate-100">
                                    {{ $card->keluhan }}
                                </p>

                                <!-- Footer Card -->
                                <div class="pt-2 border-t border-sky-50 flex items-center justify-between text-[10px] text-slate-400">
                                    <span class="truncate">{{ $card->tanggal->translatedFormat('d M Y') }}</span>

                                    <div class="flex items-center gap-1.5">
                                        <!-- Quick Move Select for Mobile / Accessibility -->
                                        @if ($canManage)
                                            <select 
                                                class="text-[10px] py-0.5 px-1 bg-white border border-slate-200 rounded text-slate-600 focus:ring-0 focus:border-sky-500"
                                                @change="changeStatus({{ $card->id }}, $event.target.value)"
                                                title="Pindah Status"
                                            >
                                                <option value="" disabled selected>&bull;&bull;&bull;</option>
                                                <option value="draft">Draft</option>
                                                <option value="dalam_penanganan">Dikerjakan</option>
                                                <option value="tertunda">Pending</option>
                                                <option value="menunggu_tanda_tangan">Verifikasi</option>
                                                <option value="selesai">Selesai</option>
                                            </select>
                                        @endif

                                        <a href="{{ route('berita-acara.show', $card) }}" class="text-sky-600 hover:text-sky-800 font-bold p-1">
                                            Buka &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="h-36 flex flex-col items-center justify-center border-2 border-dashed border-sky-100 rounded-xl text-slate-400 p-4 text-center">
                                <svg class="w-6 h-6 mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="text-[11px] font-medium">Belum ada pekerjaan</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function kanbanBoard() {
    return {
        draggedCardId: null,
        toast: {
            visible: false,
            message: '',
            type: 'success',
            timeout: null
        },
        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.visible = true;
            if (this.toast.timeout) clearTimeout(this.toast.timeout);
            this.toast.timeout = setTimeout(() => {
                this.toast.visible = false;
            }, 3500);
        },
        onDragStart(event, id) {
            this.draggedCardId = id;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', id);
            event.target.classList.add('opacity-40');
        },
        onDragEnd(event) {
            event.target.classList.remove('opacity-40');
        },
        onDragOver(event) {
            event.currentTarget.classList.add('bg-sky-100/60', 'border-sky-300');
        },
        onDragLeave(event) {
            event.currentTarget.classList.remove('bg-sky-100/60', 'border-sky-300');
        },
        onDrop(event, newStatus) {
            event.currentTarget.classList.remove('bg-sky-100/60', 'border-sky-300');
            const id = this.draggedCardId || event.dataTransfer.getData('text/plain');
            if (!id) return;
            this.changeStatus(id, newStatus);
        },
        async changeStatus(id, newStatus) {
            if (!id || !newStatus) return;

            try {
                const response = await fetch(`/kanban/${id}/update-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    this.showToast(data.message || 'Gagal memindahkan status.', 'error');
                }
            } catch (error) {
                this.showToast('Terjadi kesalahan jaringan.', 'error');
            }
        }
    }
}
</script>
@endsection
