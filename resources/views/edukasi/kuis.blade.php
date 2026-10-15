<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kuis Profil Risiko') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi sukses --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info cara pengisian --}}
            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Petunjuk:</strong> Isi nama dan NIM Anda, lalu masukkan total skor kuis (0-15)
                    sesuai hasil perhitungan dari pertanyaan-pertanyaan yang telah Anda jawab.
                </p>
            </div>

            {{-- Form Kuis --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('edukasi.kuis.simpan') }}" method="POST">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label for="nama"
                               class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Nama Lengkap
                        </label>
                        <input type="text"
                               id="nama"
                               name="nama"
                               value="{{ old('nama', Auth::user()->name ?? '') }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIM --}}
                    <div class="mb-4">
                        <label for="nim"
                               class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            NIM
                        </label>
                        <input type="text"
                               id="nim"
                               name="nim"
                               value="{{ old('nim', Auth::user()->nim ?? '') }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('nim')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Skor --}}
                    <div class="mb-4">
                        <label for="skor"
                               class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Total Skor Kuis (0 - 15)
                        </label>
                        <input type="number"
                               id="skor"
                               name="skor"
                               min="0"
                               max="15"
                               value="{{ old('skor') }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('skor')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan Kategori --}}
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-3 mb-6 text-xs text-gray-600 dark:text-gray-300">
                        <p class="font-semibold mb-1">Kategori Profil Risiko:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><span class="font-medium text-green-600 dark:text-green-400">0 - 5</span> → Konservatif</li>
                            <li><span class="font-medium text-yellow-600 dark:text-yellow-400">6 - 10</span> → Moderat</li>
                            <li><span class="font-medium text-red-600 dark:text-red-400">11 - 15</span> → Agresif</li>
                        </ul>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3">
                        <x-primary-button>
                            {{ __('Simpan Hasil') }}
                        </x-primary-button>

                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>