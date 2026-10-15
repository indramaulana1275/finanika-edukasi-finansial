<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FINANIKA') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Futuristic Animations -->
    <style>
        /* ============================================================ */
        /* AURORA BACKGROUND - Gradient bergerak */
        /* ============================================================ */
        @keyframes aurora {
            0%, 100% {
                transform: translate(0%, 0%) rotate(0deg) scale(1);
                opacity: 0.4;
            }
            25% {
                transform: translate(5%, -5%) rotate(45deg) scale(1.1);
                opacity: 0.6;
            }
            50% {
                transform: translate(-5%, 5%) rotate(-45deg) scale(0.95);
                opacity: 0.5;
            }
            75% {
                transform: translate(3%, 3%) rotate(90deg) scale(1.05);
                opacity: 0.55;
            }
        }

        .aurora-orb {
            animation: aurora 20s ease-in-out infinite;
            will-change: transform, opacity;
        }

        .aurora-orb-delay-1 { animation-delay: -5s; }
        .aurora-orb-delay-2 { animation-delay: -10s; }
        .aurora-orb-delay-3 { animation-delay: -15s; }

        /* ============================================================ */
        /* GRID BACKGROUND - Cyberpunk pattern */
        /* ============================================================ */
        .cyber-grid {
            background-image:
                linear-gradient(rgba(6, 182, 212, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(6, 182, 212, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0%   { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }

        /* ============================================================ */
        /* TYPEWRITER - Efek mesin tik */
        /* ============================================================ */
        .typewriter {
            overflow: hidden;
            border-right: 2px solid #06b6d4;
            white-space: pre-wrap;
            animation:
                typing 3s steps(120, end),
                blink 0.7s step-end infinite;
            max-width: 100%;
        }

        @keyframes typing {
            from { max-height: 0; opacity: 0; }
            to   { max-height: 500px; opacity: 1; }
        }

        @keyframes blink {
            0%, 100% { border-color: transparent; }
            50%      { border-color: #06b6d4; }
        }

        /* ============================================================ */
        /* GLOW PULSE - Efek ring berdenyut */
        /* ============================================================ */
        @keyframes glowPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(6, 182, 212, 0.3), 0 0 40px rgba(6, 182, 212, 0.15); }
            50%      { box-shadow: 0 0 30px rgba(6, 182, 212, 0.6), 0 0 60px rgba(6, 182, 212, 0.3); }
        }

        .glow-pulse {
            animation: glowPulse 3s ease-in-out infinite;
        }

        /* ============================================================ */
        /* FLOATING - Efek melayang */
        /* ============================================================ */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%      { transform: translateY(-10px); }
        }

        .float-anim {
            animation: float 4s ease-in-out infinite;
        }

        /* ============================================================ */
        /* SHIMMER - Efek kilau pada border */
        /* ============================================================ */
        @keyframes shimmer {
            0%   { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .shimmer-border {
            position: relative;
        }
        .shimmer-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(90deg,
                transparent,
                rgba(6, 182, 212, 0.6),
                transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s linear infinite;
            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* ============================================================ */
        /* GRADIENT TEXT */
        /* ============================================================ */
        .gradient-text {
            background: linear-gradient(135deg, #06b6d4 0%, #a855f7 50%, #ec4899 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 5s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50%      { background-position: 100% 50%; }
        }

        /* ============================================================ */
        /* FADE IN UP - Animasi masuk */
        /* ============================================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .fade-in-up-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .fade-in-up-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .fade-in-up-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .fade-in-up-delay-4 { animation-delay: 0.4s; opacity: 0; }
    </style>
</head>
<body class="font-sans antialiased bg-[#0B0E14] text-gray-200 overflow-x-hidden">

    {{-- ============================================================ --}}
    {{-- AURORA BACKGROUND GLOBAL --}}
    {{-- ============================================================ --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        {{-- Orb 1: Cyan --}}
        <div class="aurora-orb aurora-orb-delay-1 absolute top-[-20%] left-[-10%]
                    w-[600px] h-[600px] rounded-full blur-[140px]
                    bg-gradient-to-br from-cyan-500/20 to-blue-600/10"></div>

        {{-- Orb 2: Purple --}}
        <div class="aurora-orb aurora-orb-delay-2 absolute top-[40%] right-[-15%]
                    w-[700px] h-[700px] rounded-full blur-[160px]
                    bg-gradient-to-br from-purple-600/20 to-pink-500/10"></div>

        {{-- Orb 3: Indigo --}}
        <div class="aurora-orb aurora-orb-delay-3 absolute bottom-[-20%] left-[20%]
                    w-[500px] h-[500px] rounded-full blur-[120px]
                    bg-gradient-to-br from-indigo-500/15 to-purple-500/10"></div>

        {{-- Cyber Grid --}}
        <div class="cyber-grid absolute inset-0 opacity-40"></div>
    </div>

    {{-- ============================================================ --}}
    {{-- LAYOUT: Sidebar + Main Content --}}
    {{-- ============================================================ --}}
    <div x-data="{ sidebarOpen: false }" class="relative min-h-screen flex z-10">

        {{-- ============================================================ --}}
        {{-- SIDEBAR (Desktop) --}}
        {{-- ============================================================ --}}
        <aside class="hidden md:flex md:flex-col md:w-64 md:fixed md:inset-y-0
                      bg-gradient-to-b from-[#1a0b2e]/80 via-[#140a24]/60 to-[#0B0E14]/80
                      backdrop-blur-2xl
                      border-r border-purple-500/20 z-40">

            {{-- Ambient glow --}}
            <div class="pointer-events-none absolute top-0 left-0 w-full h-96
                        bg-gradient-to-b from-purple-600/15 to-transparent"></div>

            <div class="relative flex flex-col h-full">

                {{-- Logo --}}
                <div class="flex items-center gap-3 px-6 py-6 border-b border-white/5">
                    <div class="relative w-11 h-11 rounded-xl bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600
                                flex items-center justify-center
                                shadow-[0_0_25px_rgba(6,182,212,0.6)] glow-pulse">
                        <svg class="w-6 h-6 text-white relative z-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 17l6-6 4 4 8-8"/>
                            <path d="M14 7h7v7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-extrabold text-base leading-tight tracking-tight">FINANIKA</p>
                        <p class="text-[10px] text-cyan-300/80 tracking-widest uppercase">Narasi Finansial</p>
                    </div>
                </div>

                {{-- Menu Navigasi --}}
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    @php
                        $menus = [
                            ['route' => 'dashboard',          'label' => 'Dasbor',         'icon' => 'grid'],
                            ['route' => 'edukasi.kuis',       'label' => 'Kuis Risiko',    'icon' => 'clipboard'],
                            ['route' => 'edukasi.kalkulator', 'label' => 'Kalkulator DCA', 'icon' => 'calculator'],
                            ['route' => 'edukasi.market',     'label' => 'Market Data',    'icon' => 'chart'],
                        ];
                    @endphp

                    @foreach($menus as $menu)
                        @php $active = request()->routeIs($menu['route']); @endphp

                        <a href="{{ route($menu['route']) }}"
                           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm
                                  transition-all duration-300
                                  {{ $active
                                      ? 'bg-gradient-to-r from-cyan-600/30 via-blue-600/20 to-purple-600/20
                                         text-white border border-cyan-400/40
                                         shadow-[0_0_20px_rgba(6,182,212,0.4)]'
                                      : 'text-gray-400 hover:bg-white/5 hover:text-white border border-transparent' }}">

                            @if($active)
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6
                                             bg-gradient-to-b from-cyan-400 to-purple-400
                                             rounded-r-full shadow-[0_0_10px_rgba(6,182,212,0.8)]"></span>
                            @endif

                            <span class="shrink-0 w-5 h-5 flex items-center justify-center">
                                @switch($menu['icon'])
                                    @case('grid')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                        </svg>
                                        @break
                                    @case('clipboard')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        @break
                                    @case('calculator')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h.01M9 15h.01M13 11h.01M13 15h.01M17 11h.01M17 15h.01M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                        </svg>
                                        @break
                                    @case('chart')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 4 4 5-6M4 20h16"/>
                                        </svg>
                                        @break
                                @endswitch
                            </span>

                            <span class="font-medium">{{ $menu['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                {{-- Brand Card Footer --}}
                <div class="px-4 py-4 border-t border-white/5">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-cyan-500/10 via-blue-500/5 to-purple-500/10
                                border border-cyan-400/30 shimmer-border">
                        <p class="text-[10px] font-bold text-cyan-300 tracking-widest uppercase mb-1">FINANIKA</p>
                        <p class="text-[10px] text-gray-400 leading-relaxed">
                            Ubah angka rumit jadi narasi cerdas.
                        </p>
                    </div>
                </div>

            </div>
        </aside>

        {{-- ============================================================ --}}
        {{-- SIDEBAR (Mobile — Slide over) --}}
        {{-- ============================================================ --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 md:hidden"
             style="display: none;">
        </div>

        <aside x-show="sidebarOpen"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 w-64 z-50 md:hidden
                      bg-gradient-to-b from-[#1a0b2e]/95 via-[#140a24]/90 to-[#0B0E14]/95
                      backdrop-blur-2xl
                      border-r border-purple-500/20"
               style="display: none;">

            <div class="flex flex-col h-full">

                <div class="flex items-center justify-between px-6 py-6 border-b border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600
                                    flex items-center justify-center
                                    shadow-[0_0_20px_rgba(6,182,212,0.5)]">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 17l6-6 4 4 8-8"/>
                                <path d="M14 7h7v7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-extrabold text-sm tracking-tight">FINANIKA</p>
                            <p class="text-[10px] text-cyan-300/80 tracking-widest uppercase">Narasi Finansial</p>
                        </div>
                    </div>

                    <button @click="sidebarOpen = false"
                            class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    @foreach($menus as $menu)
                        @php $active = request()->routeIs($menu['route']); @endphp

                        <a href="{{ route($menu['route']) }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all
                                  {{ $active
                                      ? 'bg-gradient-to-r from-cyan-600/30 to-purple-600/20 text-white border border-cyan-400/30'
                                      : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <span>{{ $menu['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="px-4 py-4 border-t border-white/5">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-cyan-500/10 via-blue-500/5 to-purple-500/10
                                border border-cyan-400/20">
                        <p class="text-[10px] font-bold text-cyan-300 tracking-widest uppercase mb-1">FINANIKA</p>
                        <p class="text-[10px] text-gray-400 leading-relaxed">
                            Ubah angka rumit jadi narasi cerdas.
                        </p>
                    </div>
                </div>

            </div>
        </aside>

        {{-- ============================================================ --}}
        {{-- MAIN CONTENT --}}
        {{-- ============================================================ --}}
        <div class="flex-1 md:ml-64 flex flex-col min-w-0">

            {{-- TOP BAR --}}
            <header class="sticky top-0 z-30 bg-[#0B0E14]/70 backdrop-blur-2xl border-b border-cyan-500/10">
                <div class="flex items-center justify-between px-4 md:px-8 py-4">

                    <button @click="sidebarOpen = true"
                            class="md:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="hidden md:block">
                        <p class="text-sm text-gray-400">Selamat siang,</p>
                        <h1 class="text-xl font-bold text-white mt-0.5">
                            {{ Auth::user()->name }}
                        </h1>
                    </div>

                    <div class="flex items-center gap-3">

                        @php
                            $navRiskProfile  = \App\Models\RiskProfile::where('nim', Auth::user()->nim)->first();
                            $navProfilRisiko = $navRiskProfile->tipe_risiko ?? 'Moderat';

                            $badgeStyle = match($navProfilRisiko) {
                                'Konservatif' => 'bg-emerald-500/10 border-emerald-400/50 text-emerald-300 shadow-[0_0_20px_rgba(16,185,129,0.5)]',
                                'Moderat'     => 'bg-yellow-500/10 border-yellow-400/50 text-yellow-300 shadow-[0_0_20px_rgba(234,179,8,0.5)]',
                                'Agresif'     => 'bg-orange-500/10 border-orange-400/50 text-orange-300 shadow-[0_0_20px_rgba(249,115,22,0.5)]',
                                default       => 'bg-gray-500/10 border-gray-400/50 text-gray-300',
                            };
                            $iconFire = match($navProfilRisiko) {
                                'Agresif' => '🔥',
                                'Moderat' => '⚡',
                                default   => '🛡️',
                            };
                        @endphp

                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full border {{ $badgeStyle }}">
                            <span class="text-sm">{{ $iconFire }}</span>
                            <span class="text-[11px] font-semibold tracking-wider uppercase">
                                Profil: {{ $navProfilRisiko }}
                            </span>
                        </div>

                        <button class="w-10 h-10 rounded-full bg-white/5 border border-white/10
                                       flex items-center justify-center text-gray-400
                                       hover:bg-cyan-500/10 hover:border-cyan-400/40 hover:text-cyan-300 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </button>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 p-1 rounded-full hover:bg-white/5 transition">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-cyan-500 to-purple-600
                                                flex items-center justify-center text-white text-sm font-bold
                                                shadow-[0_0_15px_rgba(6,182,212,0.5)]">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>

                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            <main class="flex-1 relative z-10">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>
</html>