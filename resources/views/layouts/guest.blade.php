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
<body 
    x-data="{
        mouseX: 0,
        mouseY: 0,
        relX: 0,
        relY: 0,
        hasMoved: false,
        handleMouse(e) {
            this.mouseX = e.clientX;
            this.mouseY = e.clientY;
            this.relX = ((e.clientX / window.innerWidth) - 0.5) * 2;
            this.relY = ((e.clientY / window.innerHeight) - 0.5) * 2;
            this.hasMoved = true;
        }
    }"
    @mousemove.window="handleMouse($event)"
    class="min-h-full bg-slate-50 font-sans text-slate-800 antialiased selection:bg-sky-200 selection:text-sky-900 relative overflow-x-hidden"
>
    <!-- Cursor Interactive 3D Background Canvas -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <!-- 3D Subtle Grid Mesh -->
        <div 
            class="absolute inset-0 bg-grid-mesh opacity-60 transition-transform duration-300 ease-out"
            :style="`transform: translate3d(${relX * 10}px, ${relY * 10}px, 0);`"
        ></div>

        <!-- Dynamic Cursor Spotlight Glow (Follows mouse directly) -->
        <div 
            x-show="hasMoved"
            x-cloak
            class="fixed pointer-events-none w-[520px] h-[520px] rounded-full bg-gradient-to-r from-sky-400/25 via-cyan-300/20 to-blue-300/15 blur-3xl transition-transform duration-75 ease-out"
            :style="`left: ${mouseX}px; top: ${mouseY}px; transform: translate(-50%, -50%);`"
        ></div>

        <!-- 3D Floating Orbs Layer (React to mouse direction) -->
        <!-- Orb 1: Top Left Sky Glow -->
        <div 
            class="absolute -top-24 -left-24 w-96 h-96 sm:w-[34rem] sm:h-[34rem] rounded-full bg-gradient-to-tr from-sky-300/45 via-sky-200/35 to-blue-200/25 blur-3xl transition-transform duration-300 ease-out"
            :style="`transform: translate3d(${relX * 50}px, ${relY * 50}px, 0);`"
        ></div>

        <!-- Orb 2: Bottom Right Cyan/Sky Wave -->
        <div 
            class="absolute -bottom-32 -right-32 w-96 h-96 sm:w-[38rem] sm:h-[38rem] rounded-full bg-gradient-to-bl from-cyan-300/40 via-sky-300/30 to-blue-400/20 blur-3xl transition-transform duration-300 ease-out"
            :style="`transform: translate3d(${-relX * 65}px, ${-relY * 65}px, 0);`"
        ></div>

        <!-- Orb 3: Center Ambient Glow -->
        <div 
            class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 sm:w-[30rem] sm:h-[30rem] rounded-full bg-gradient-to-r from-sky-200/30 to-blue-100/35 blur-2xl transition-transform duration-200 ease-out"
            :style="`transform: translate3d(calc(-50% + ${relX * 30}px), calc(-50% + ${relY * 30}px), 0);`"
        ></div>

        <!-- Floating 3D Geometric Accents (Tilt & Shift with cursor) -->
        <!-- Ring 1: Top Right -->
        <div 
            class="hidden sm:block absolute top-16 right-20 w-32 h-32 rounded-full border-2 border-sky-300/60 shadow-[inset_0_2px_12px_rgba(56,189,248,0.2)] opacity-85 backdrop-blur-[1px] transition-transform duration-200 ease-out"
            :style="`transform: translate3d(${-relX * 35}px, ${-relY * 35}px, 0) rotate(${relX * 18}deg);`"
        ></div>

        <!-- Ring 2: Bottom Left -->
        <div 
            class="hidden sm:block absolute bottom-20 left-16 w-24 h-24 rounded-full border-2 border-sky-400/50 shadow-[0_4px_20px_rgba(56,189,248,0.25)] opacity-80 transition-transform duration-200 ease-out"
            :style="`transform: translate3d(${relX * 40}px, ${relY * 40}px, 0) rotate(${-relY * 20}deg);`"
        ></div>

        <!-- Floating 3D Rounded Cube: Top Left -->
        <div 
            class="hidden md:block absolute top-28 left-1/4 w-14 h-14 rounded-2xl bg-white/70 border border-sky-200/80 shadow-xl shadow-sky-200/50 backdrop-blur-sm transition-transform duration-200 ease-out"
            :style="`transform: translate3d(${relX * 45}px, ${relY * 45}px, 0) rotate(${12 + relX * 25}deg);`"
        ></div>

        <!-- Floating 3D Rounded Cube: Bottom Right -->
        <div 
            class="hidden md:block absolute bottom-28 right-1/4 w-16 h-16 rounded-2xl bg-white/80 border border-sky-200/90 shadow-2xl shadow-sky-300/40 backdrop-blur-sm transition-transform duration-200 ease-out"
            :style="`transform: translate3d(${-relX * 55}px, ${-relY * 55}px, 0) rotate(${-12 - relY * 25}deg);`"
        ></div>
    </div>

    <!-- Page Content (Foreground with subtle 3D parallax tilt) -->
    <div 
        class="relative z-10 min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 transition-transform duration-200 ease-out"
        :style="`transform: perspective(1200px) rotateY(${relX * 2.2}deg) rotateX(${-relY * 2.2}deg);`"
    >
        {{ $slot ?? '' }}
        @yield('content')
    </div>
</body>
</html>
