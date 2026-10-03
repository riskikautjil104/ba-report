@extends('layouts.app', ['heading' => 'Edit Pengguna'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('users.index') }}" class="btn-3d-light px-3.5 py-2 text-xs">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
        <span class="text-xs font-mono font-bold text-sky-800">{{ '@' . $user->username }}</span>
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

    @if ($user->id === auth()->id())
        <div class="card-3d p-4 bg-amber-50 border-amber-200 text-amber-900 text-xs flex items-center gap-2.5">
            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Anda sedang mengedit akun Anda sendiri. Role dan status aktif akun Anda terkunci demi keamanan sistem.</span>
        </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="card-3d p-6 sm:p-8 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-4 border-b border-sky-100">
                <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 font-black flex items-center justify-center text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Edit Data Pengguna</h2>
                    <p class="text-[11px] text-slate-400">Perbarui profil, hak akses, atau atur ulang kata sandi</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        class="input-3d text-xs w-full"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-700 mb-1">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username', $user->username) }}" 
                            required 
                            class="input-3d text-xs font-mono w-full"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}" 
                            required 
                            class="input-3d text-xs w-full"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">
                            Peran / Hak Akses (Role) <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="role" 
                            name="role" 
                            required 
                            {{ $user->id === auth()->id() ? 'disabled' : '' }} 
                            class="input-3d text-xs w-full {{ $user->id === auth()->id() ? 'bg-slate-50 cursor-not-allowed' : '' }}"
                        >
                            @foreach ($roles as $r)
                                <option value="{{ $r->value }}" {{ old('role', $user->role->value) === $r->value ? 'selected' : '' }}>
                                    {{ $r->label() }}
                                </option>
                            @endforeach
                        </select>
                        @if ($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role->value }}">
                        @endif
                    </div>

                    <div>
                        <label for="is_active" class="block text-xs font-semibold text-slate-700 mb-1">
                            Status Akun <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="is_active" 
                            name="is_active" 
                            required 
                            {{ $user->id === auth()->id() ? 'disabled' : '' }} 
                            class="input-3d text-xs w-full {{ $user->id === auth()->id() ? 'bg-slate-50 cursor-not-allowed' : '' }}"
                        >
                            <option value="1" {{ old('is_active', $user->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                            <option value="0" {{ old('is_active', $user->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif (Diblokir)</option>
                        </select>
                        @if ($user->id === auth()->id())
                            <input type="hidden" name="is_active" value="1">
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-sky-50 space-y-3">
                    <div class="p-3 rounded-xl bg-sky-50/60 border border-sky-100 text-xs text-sky-800">
                        <span class="font-bold block mb-0.5">Ubah Kata Sandi (Opsional):</span>
                        <span class="text-[11px] text-slate-500">Biarkan kedua kolom di bawah kosong jika Anda tidak ingin mengubah kata sandi saat ini.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                                Kata Sandi Baru
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Kosongkan jika tetap" 
                                class="input-3d text-xs w-full"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Ulangi kata sandi baru" 
                                class="input-3d text-xs w-full"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-3d p-4 bg-white flex items-center justify-end">
            <button type="submit" class="btn-3d-primary px-6 py-2.5 text-xs shadow-lg">
                Simpan Perubahan Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
