@extends('layouts.app', ['heading' => 'Audit Log Sistem'])

@section('content')
<div class="space-y-6 w-full">
    <!-- Top Filter Bar -->
    <div class="card-3d p-5 bg-white">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="flex flex-col sm:flex-row gap-3 items-center">
            <div class="flex-1 relative w-full">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari aktivitas, user, IP, atau nomor BA..."
                    class="input-3d pl-10 text-xs py-2.5"
                >
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sky-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <button type="submit" class="btn-3d-light px-4 py-2.5 text-xs shrink-0">
                Cari Log
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('audit-logs.index') }}" class="text-xs text-slate-400 hover:text-slate-600">Reset</a>
            @endif
        </form>
    </div>

    <!-- Audit Log Table -->
    <div class="card-3d p-6 bg-white space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-sky-100">
            <h2 class="text-sm font-bold text-slate-800">Riwayat Jejak Audit Transaksi</h2>
            <span class="text-xs text-slate-400">Total Tercatat: {{ $auditLogs->total() }}</span>
        </div>

        @if ($auditLogs->isEmpty())
            <p class="text-xs text-slate-400 italic py-6 text-center">Belum ada catatan aktivitas audit.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-slate-400 border-b border-slate-100 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="py-2.5 px-3">Waktu (WIT)</th>
                            <th class="py-2.5 px-3">Pengguna</th>
                            <th class="py-2.5 px-3">Aktivitas</th>
                            <th class="py-2.5 px-3">Dokumen BA</th>
                            <th class="py-2.5 px-3">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($auditLogs as $log)
                            <tr class="hover:bg-sky-50/40 transition">
                                <td class="py-3 px-3 font-mono text-slate-500 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-bold text-slate-800">{{ $log->user?->name ?? 'Sistem / Publik' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="badge-3d text-[10px] bg-sky-50 text-sky-800 border border-sky-200">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-mono text-sky-700">
                                    @if ($log->beritaAcara)
                                        <a href="{{ route('berita-acara.show', $log->beritaAcara) }}" class="underline font-bold">
                                            {{ $log->beritaAcara->nomor }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-3 font-mono text-slate-400 text-[11px]">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $auditLogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
