<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kalkulator Simulasi DCA') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info DCA --}}
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 rounded-lg p-4">
                <p class="text-sm text-emerald-800 dark:text-emerald-200">
                    <strong>Dollar Cost Averaging (DCA)</strong> adalah strategi investasi dengan membeli
                    aset secara rutin dalam jumlah tetap, tanpa memperhatikan harga pasar.
                    Simulasi ini mengasumsikan kenaikan harga <strong>1% per bulan</strong>.
                </p>
            </div>

            {{-- Form Kalkulator --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    Input Simulasi
                </h3>

                <form action="{{ route('edukasi.kalkulator.hitung') }}" method="POST">
                    @csrf

                    {{-- Nama Aset --}}
                    <div class="mb-4">
                        <label for="nama_aset"
                               class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Nama Aset
                        </label>
                        <input type="text"
                               id="nama_aset"
                               name="nama_aset"
                               value="{{ old('nama_aset') }}"
                               placeholder="Contoh: Bitcoin, Saham BBCA, Emas"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('nama_aset')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Saat Ini --}}
                    <div class="mb-4">
                        <label for="harga_saat_ini"
                               class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Harga Aset Saat Ini (Rp)
                        </label>
                        <input type="number"
                               id="harga_saat_ini"
                               name="harga_saat_ini"
                               min="1"
                               value="{{ old('harga_saat_ini') }}"
                               placeholder="Contoh: 1000000"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('harga_saat_ini')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Investasi Bulanan --}}
                    <div class="mb-4">
                        <label for="investasi_bulanan"
                               class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Investasi per Bulan (Rp)
                        </label>
                        <input type="number"
                               id="investasi_bulanan"
                               name="investasi_bulanan"
                               min="1"
                               value="{{ old('investasi_bulanan') }}"
                               placeholder="Contoh: 500000"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('investasi_bulanan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah Bulan --}}
                    <div class="mb-6">
                        <label for="jumlah_bulan"
                               class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Jumlah Bulan Investasi (1 - 120)
                        </label>
                        <input type="number"
                               id="jumlah_bulan"
                               name="jumlah_bulan"
                               min="1"
                               max="120"
                               value="{{ old('jumlah_bulan', 12) }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                        @error('jumlah_bulan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3">
                        <x-primary-button>
                            {{ __('Hitung Simulasi') }}
                        </x-primary-button>

                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>
            </div>

            {{-- Hasil Simulasi --}}
            @isset($nilaiAkhir)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        📊 Hasil Simulasi ({{ $bulan }} bulan)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Total Investasi</p>
                            <p class="text-lg font-bold text-blue-700 dark:text-blue-300">
                                Rp {{ number_format($totalInvestasi, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-emerald-50 dark:bg-emerald-900/30 p-4 rounded">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Nilai Akhir</p>
                            <p class="text-lg font-bold text-emerald-700 dark:text-emerald-300">
                                Rp {{ number_format($nilaiAkhir, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="{{ $keuntungan >= 0 ? 'bg-green-50 dark:bg-green-900/30' : 'bg-red-50 dark:bg-red-900/30' }} p-4 rounded">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Estimasi Keuntungan</p>
                            <p class="text-lg font-bold {{ $keuntungan >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                Rp {{ number_format($keuntungan, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 italic">
                        *Asumsi kenaikan harga 1% per bulan. Hasil aktual bisa berbeda tergantung kondisi pasar.
                    </p>
                </div>
            @endisset

            {{-- Riwayat Simulasi --}}
            @isset($riwayatSimulasi)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                            Riwayat Simulasi Terakhir
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-600 dark:text-gray-300">Nama Aset</th>
                                    <th class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">Harga Saat Ini</th>
                                    <th class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">Nilai Akhir DCA</th>
                                    <th class="px-4 py-3 text-left text-gray-600 dark:text-gray-300">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($riwayatSimulasi as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                            {{ $item->nama_aset }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-200">
                                            Rp {{ number_format($item->harga_saat_ini, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                            Rp {{ number_format($item->simulasi_dca ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                            {{ $item->created_at->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                                            Belum ada riwayat simulasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endisset

        </div>
    </div>
</x-app-layout>