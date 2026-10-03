@extends('layouts.app', ['heading' => 'Tambah Pengguna Baru'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('users.index') }}" class="btn-3d-light px-3.5 py-2 text-xs">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
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

    <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
        @csrf

        <div class="card-3d p-6 sm:p-8 bg-white space-y-5">
            <div class="flex items-center gap-2.5 pb-4 border-b border-sky-100">
                <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 font-black flex items-center justify-center text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Formulir Pendaftaran Pengguna</h2>
                    <p class="text-[11px] text-slate-400">Buat akun untuk akses staf IT atau administrator sistem</p>
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
                        value="{{ old('name') }}" 
                        required 
                        placeholder="Contoh: Muhammad Ilham, S.Kom" 
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
                            value="{{ old('username') }}" 
                            required 
                            placeholder="Contoh: ilham_it" 
                            class="input-3d text-xs font-mono w-full"
                        >
                        <span class="text-[10px] text-slate-400 mt-1 block">Digunakan untuk login (huruf, angka, strip/garis bawah)</span>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="ilham@rsudchasan.id" 
                            class="input-3d text-xs w-full"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">
                            Peran / Hak Akses (Role) <span class="text-rose-500">*</span>
                        </label>
                        <select id="role" name="role" required class="input-3d text-xs w-full">
                            @foreach ($roles as $r)
                                <option value="{{ $r->value }}" {{ old('role') === $r->value ? 'selected' : '' }}>
                                    {{ $r->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="is_active" class="block text-xs font-semibold text-slate-700 mb-1">
                            Status Akun <span class="text-rose-500">*</span>
                        </label>
                        <select id="is_active" name="is_active" required class="input-3d text-xs w-full">
                            <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Nonaktif (Diblokir)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-sky-50">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                            Kata Sandi (Password) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="Minimal 8 karakter" 
                            class="input-3d text-xs w-full"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                            Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required 
                            placeholder="Ketik ulang kata sandi" 
                            class="input-3d text-xs w-full"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="card-3d p-4 bg-white flex items-center justify-end">
            <button type="submit" class="btn-3d-primary px-6 py-2.5 text-xs shadow-lg">
                Simpan & Daftarkan Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
