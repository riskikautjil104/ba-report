<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'e-BA IT Chasan' }} — RSUD Dr. H. Chasan Boesoirie</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logoresmi.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-50 font-sans text-slate-800 antialiased selection:bg-sky-200 selection:text-sky-900 relative overflow-x-hidden">
    <!-- Animated 3D Background Canvas -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <!-- 3D Subtle Grid Mesh -->
        <div class="absolute inset-0 bg-grid-mesh opacity-60"></div>

        <!-- 3D Floating Orbs Layer -->
        <!-- Orb 1: Top Left Sky Glow -->
        <div class="absolute -top-24 -left-24 w-96 h-96 sm:w-[32rem] sm:h-[32rem] rounded-full bg-gradient-to-tr from-sky-300/40 via-sky-200/30 to-blue-200/20 blur-3xl animate-float-slow"></div>

        <!-- Orb 2: Bottom Right Cyan/Sky Wave -->
        <div class="absolute -bottom-32 -right-32 w-96 h-96 sm:w-[36rem] sm:h-[36rem] rounded-full bg-gradient-to-bl from-cyan-300/35 via-sky-300/25 to-blue-400/20 blur-3xl animate-float-reverse"></div>

        <!-- Orb 3: Center Ambient Pulse -->
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 sm:w-[28rem] sm:h-[28rem] rounded-full bg-gradient-to-r from-sky-200/25 to-blue-100/30 blur-2xl animate-pulse-glow"></div>

        <!-- Floating 3D Geometric Accents -->
        <!-- Ring 1: Top Right -->
        <div class="hidden sm:block absolute top-16 right-20 w-32 h-32 rounded-full border-2 border-sky-200/60 shadow-[inset_0_2px_10px_rgba(56,189,248,0.15)] animate-float-slow opacity-75 backdrop-blur-[1px]"></div>

        <!-- Ring 2: Bottom Left Small -->
        <div class="hidden sm:block absolute bottom-20 left-16 w-20 h-20 rounded-full border-2 border-sky-300/50 shadow-[0_4px_16px_rgba(56,189,248,0.2)] animate-float-reverse opacity-70"></div>

        <!-- Floating 3D Rounded Cube: Top Center -->
        <div class="hidden md:block absolute top-28 left-1/4 w-12 h-12 rounded-2xl bg-white/60 border border-sky-100 shadow-lg shadow-sky-200/40 rotate-12 animate-float-slow opacity-80 backdrop-blur-sm"></div>

        <!-- Floating 3D Rounded Cube: Bottom Right -->
        <div class="hidden md:block absolute bottom-28 right-1/4 w-14 h-14 rounded-2xl bg-white/70 border border-sky-200/60 shadow-xl shadow-sky-300/30 -rotate-12 animate-float-reverse opacity-85 backdrop-blur-sm"></div>
    </div>

    <!-- Page Content (Foreground) -->
    <div class="relative z-10 min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        {{ $slot ?? '' }}
        @yield('content')
    </div>
</body>
</html>
