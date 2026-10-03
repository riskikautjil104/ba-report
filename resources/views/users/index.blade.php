@extends('layouts.app', ['heading' => 'Manajemen Pengguna'])

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 300)">
    <!-- Top Action & Filter Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Manajemen Pengguna Sistem</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola akun Superadmin dan Staf IT Ruang IT RSUD Dr. H. Chasan Boesoirie</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('users.create') }}" class="btn-3d-primary px-4 py-2.5 text-xs shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Pengguna Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card-3d p-4 bg-white">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama, username, atau email..." 
                    class="input-3d input-3d-icon text-xs py-2 w-full"
                >
            </div>

            <div>
                <select name="role" onchange="this.form.submit()" class="input-3d text-xs py-2 w-full">
                    <option value="">Semua Peran / Role</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->value }}" {{ request('role') === $r->value ? 'selected' : '' }}>
                            {{ $r->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" onchange="this.form.submit()" class="input-3d text-xs py-2 w-full">
                    <option value="">Semua Status Akun</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Shimmer Skeleton Loading State -->
    <div x-show="loading" class="space-y-4">
        <div class="skeleton-card p-6 space-y-4">
            @for ($i = 0; $i < 5; $i++)
                <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-none">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl shimmer-skeleton shrink-0"></div>
                        <div class="space-y-2">
                            <div class="h-3 w-32 shimmer-skeleton rounded"></div>
                            <div class="h-2.5 w-44 shimmer-skeleton rounded"></div>
                        </div>
                    </div>
                    <div class="h-6 w-20 shimmer-skeleton rounded-full"></div>
                </div>
            @endfor
        </div>
    </div>

    <!-- User Table Card -->
    <div x-show="!loading" x-cloak class="card-3d bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-sky-50/70 border-b border-sky-100 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3.5 px-4 sm:px-6">Pengguna</th>
                        <th class="py-3.5 px-4">Username</th>
                        <th class="py-3.5 px-4">Peran (Role)</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-50">
                    @forelse ($users as $u)
                        <tr class="hover:bg-sky-50/40 transition">
                            <td class="py-3.5 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-500 to-sky-400 text-white font-bold flex items-center justify-center text-xs shadow-sm shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 text-xs truncate">
                                            {{ $u->name }}
                                            @if ($u->id === auth()->id())
                                                <span class="badge-3d bg-sky-100 text-sky-800 text-[9px] ml-1">Akun Anda</span>
                                            @endif
                                        </p>
                                        <p class="text-slate-400 text-[11px] truncate">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-medium text-slate-600">
                                {{ '@' . $u->username }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="badge-3d text-[10px] {{ $u->isSuperadmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-sky-100 text-sky-800 border border-sky-200' }}">
                                    {{ $u->role->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="badge-3d text-[10px] {{ $u->isActive() ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                    {{ $u->isActive() ? '✓ Aktif' : '✕ Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 sm:px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('users.edit', $u) }}" class="btn-3d-light px-3 py-1.5 text-xs text-sky-700">
                                        Edit
                                    </a>
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ $u->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-3d-light px-2.5 py-1.5 text-xs text-rose-600 hover:bg-rose-50" title="Hapus Pengguna">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">
                                Tidak ada data pengguna yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="p-4 border-t border-sky-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
