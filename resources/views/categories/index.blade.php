@extends('layouts.app', ['heading' => 'Manajemen Kategori Masalah'])

@section('content')
<div class="space-y-6 w-full">
    <!-- Top Action Card: Add Category -->
    <div class="card-3d p-6 bg-white space-y-4">
        <h2 class="text-sm font-bold text-slate-800">Tambah Kategori Baru</h2>
        <form method="POST" action="{{ route('categories.store') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="flex-1">
                <input 
                    type="text" 
                    name="name" 
                    required 
                    placeholder="Nama kategori baru (Contoh: Jaringan LAN, Printer Thermal, Server SIMRS)..." 
                    class="input-3d text-xs py-2.5"
                >
            </div>
            <button type="submit" class="btn-3d-primary px-5 py-2.5 text-xs shrink-0">
                + Tambah Kategori
            </button>
        </form>
    </div>

    <!-- Category Table Card -->
    <div class="card-3d p-6 bg-white space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-sky-100">
            <h2 class="text-sm font-bold text-slate-800">Daftar Kategori IT Aktif</h2>
            <span class="text-xs text-slate-400">{{ $categories->count() }} Kategori</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-slate-400 border-b border-slate-100 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-2.5 px-3">Nama Kategori</th>
                        <th class="py-2.5 px-3">Slug</th>
                        <th class="py-2.5 px-3">Jumlah Berita Acara</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($categories as $cat)
                        <tr class="hover:bg-sky-50/40 transition">
                            <td class="py-3 px-3 font-bold text-slate-800">{{ $cat->name }}</td>
                            <td class="py-3 px-3 font-mono text-slate-500">{{ $cat->slug }}</td>
                            <td class="py-3 px-3 font-semibold text-sky-700">{{ $cat->berita_acaras_count }} Dokumen</td>
                            <td class="py-3 px-3">
                                <span class="badge-3d text-[10px] {{ $cat->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $cat->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-right">
                                <form method="POST" action="{{ route('categories.update', $cat) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $cat->name }}">
                                    <input type="hidden" name="is_active" value="{{ $cat->is_active ? 0 : 1 }}">
                                    <button type="submit" class="btn-3d-light px-2.5 py-1 text-[11px] {{ $cat->is_active ? 'text-amber-700' : 'text-emerald-700' }}">
                                        {{ $cat->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
