<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Kuis Profil Risiko') }}
        </h2>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi sukses --}}
            @if(session('success'))
                <div class="px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-400/20 text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info cara pengisian --}}
            <div class="rounded-lg p-4 bg-cyan-500/10 border border-cyan-400/30">
                <p class="text-sm text-cyan-300">
                    <strong>Petunjuk:</strong> Isi nama dan NIM Anda, lalu masukkan total skor kuis (0-15)
                    sesuai hasil perhitungan dari pertanyaan-pertanyaan yang telah Anda jawab.
                </p>
            </div>

            {{-- Form Kuis --}}
            <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <form action="{{ route('edukasi.kuis.simpan') }}" method="POST">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label for="nama"
                               class="block mb-2 text-sm font-medium text-gray-300">
                            Nama Lengkap
                        </label>
                        <input type="text"
                               id="nama"
                               name="nama"
                               value="{{ old('nama', Auth::user()->name ?? '') }}"
                               class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                               required>
                        @error('nama')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIM --}}
                    <div class="mb-4">
                        <label for="nim"
                               class="block mb-2 text-sm font-medium text-gray-300">
                            NIM
                        </label>
                        <input type="text"
                               id="nim"
                               name="nim"
                               value="{{ old('nim', Auth::user()->nim ?? '') }}"
                               class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                               required>
                        @error('nim')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Skor --}}
                    <div class="mb-4">
                        <label for="skor"
                               class="block mb-2 text-sm font-medium text-gray-300">
                            Total Skor Kuis (0 - 15)
                        </label>
                        <input type="number"
                               id="skor"
                               name="skor"
                               min="0"
                               max="15"
                               value="{{ old('skor') }}"
                               class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                               required>
                        @error('skor')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan Kategori --}}
                    <div class="rounded-md p-3 mb-6 bg-white/[0.02] border border-white/5 text-xs text-gray-400">
                        <p class="font-semibold mb-1 text-white">Kategori Profil Risiko:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><span class="font-medium text-emerald-400">0 - 5</span> → Konservatif</li>
                            <li><span class="font-medium text-yellow-400">6 - 10</span> → Moderat</li>
                            <li><span class="font-medium text-red-400">11 - 15</span> → Agresif</li>
                        </ul>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-cyan-500 hover:to-blue-500 transition shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                            {{ __('Simpan Hasil') }}
                        </button>

                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 text-gray-300 rounded-xl text-xs uppercase tracking-widest hover:bg-cyan-500/10 hover:border-cyan-400/40 transition">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>