@extends('layouts.guest')

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
    <!-- Brand / 3D Header -->
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 icon-3d-sphere mx-auto mb-4 shadow-xl">
            <!-- Medical Cross + Document 3D Icon -->
            <svg class="w-10 h-10 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 5h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">
            e-BA <span class="text-sky-600">IT Chasan</span>
        </h1>
        <p class="mt-1 text-sm text-slate-500 font-medium">
            Sistem Berita Acara Digital Ruang IT<br>
            RSUD Dr. H. Chasan Boesoirie Ternate
        </p>
    </div>

    <!-- 3D Form Card -->
    <div class="mt-8 card-3d p-6 sm:p-8 bg-white/95 backdrop-blur-md">
        @if (session('status'))
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium">
                <div class="flex items-center gap-2 mb-1 font-semibold">
                    <svg class="w-5 h-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Terjadi kesalahan autentikasi:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 ml-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="login" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Email atau Username
                </label>
                <div class="relative">
                    <input 
                        id="login" 
                        name="login" 
                        type="text" 
                        value="{{ old('login') }}" 
                        required 
                        autofocus 
                        placeholder="admin atau staf@rsudchasan.id"
                        class="input-3d input-3d-icon"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sky-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Kata Sandi
                </label>
                <div class="relative">
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        placeholder="••••••••"
                        class="input-3d input-3d-icon"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sky-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600 font-medium">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        value="1" 
                        class="rounded border-sky-300 text-sky-600 focus:ring-sky-400"
                    >
                    <span>Ingat saya</span>
                </label>
                <span class="text-xs text-slate-400">Ruang IT Chasan</span>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-3d-primary w-full py-3 text-base shadow-lg">
                    <span>Masuk ke Sistem</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>

        <!-- Credentials Quick Hint Card -->
        <div class="mt-6 pt-5 border-t border-sky-100">
            <p class="text-xs font-bold text-sky-900 uppercase tracking-wider mb-2">Akun Default Sistem (Sprint 0):</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="p-2 rounded-xl bg-sky-50/80 border border-sky-200/60 text-slate-700">
                    <div class="font-bold text-sky-800">Superadmin:</div>
                    <code class="text-slate-600 font-mono">admin</code> / <code class="text-slate-600 font-mono">password</code>
                </div>
                <div class="p-2 rounded-xl bg-sky-50/80 border border-sky-200/60 text-slate-700">
                    <div class="font-bold text-sky-800">Staf IT:</div>
                    <code class="text-slate-600 font-mono">staf</code> / <code class="text-slate-600 font-mono">password</code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
