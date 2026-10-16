<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Kalkulator Simulasi DCA') }}
        </h2>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info DCA --}}
            <div class="rounded-xl p-4 bg-emerald-500/10 border border-emerald-400/20">
                <p class="text-sm text-emerald-300">
                    <strong>Dollar Cost Averaging (DCA)</strong> adalah strategi investasi dengan membeli
                    aset secara rutin dalam jumlah tetap, tanpa memperhatikan harga pasar.
                    Simulasi ini mengasumsikan kenaikan harga <strong>1% per bulan</strong>.
                </p>
            </div>

            {{-- Form Kalkulator --}}
            <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <h3 class="text-md font-semibold text-white mb-4">
                    Input Simulasi
                </h3>

                <form action="{{ route('edukasi.kalkulator.hitung') }}" method="POST">
                    @csrf

                    {{-- Nama Aset --}}
                    <div class="mb-4">
                        <label for="nama_aset"
                               class="block mb-2 text-sm font-medium text-gray-300">
                            Nama Aset
                        </label>
                        <input type="text"
                               id="nama_aset"
                               name="nama_aset"
                               value="{{ old('nama_aset') }}"
                               placeholder="Contoh: Bitcoin, Saham BBCA, Emas"
                               class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                               required>
                        @error('nama_aset')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Saat Ini --}}
                    <div class="mb-4">
                        <label for="harga_saat_ini"
                               class="block mb-2 text-sm font-medium text-gray-300">
                            Harga Aset Saat Ini (Rp)
                        </label>
                        <input type="number"
                               id="harga_saat_ini"
                               name="harga_saat_ini"
                               min="1"
                               value="{{ old('harga_saat_ini') }}"
                               placeholder="Contoh: 1000000"
                               class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                               required>
                        @error('harga_saat_ini')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Investasi Bulanan --}}
                    <div class="mb-4">
                        <label for="investasi_bulanan"
                               class="block mb-2 text-sm font-medium text-gray-300">
                            Investasi per Bulan (Rp)
                        </label>
                        <input type="number"
                               id="investasi_bulanan"
                               name="investasi_bulanan"
                               min="1"
                               value="{{ old('investasi_bulanan') }}"
                               placeholder="Contoh: 500000"
                               class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                               required>
                        @error('investasi_bulanan')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah Bulan --}}
                    <div class="mb-6">
                        <label for="jumlah_bulan"
                               class="block mb-2 text-sm font-medium text-gray-300">
                            Jumlah Bulan Investasi (1 - 120)
                        </label>
                        <input type="number"
                               id="jumlah_bulan"
                               name="jumlah_bulan"
                               min="1"
                               max="120"
                               value="{{ old('jumlah_bulan', 12) }}"
                               class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                               required>
                        @error('jumlah_bulan')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-cyan-500 hover:to-blue-500 transition shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                            {{ __('Hitung Simulasi') }}
                        </button>

                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 text-gray-300 rounded-xl text-xs uppercase tracking-widest hover:bg-cyan-500/10 hover:border-cyan-400/40 transition">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>
            </div>

            {{-- Hasil Simulasi --}}
            @isset($nilaiAkhir)
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <h3 class="text-md font-semibold text-white mb-4">
                        📊 Hasil Simulasi ({{ $bulan }} bulan)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-lg bg-blue-500/10 border border-blue-400/20">
                            <p class="text-xs text-gray-400">Total Investasi</p>
                            <p class="text-lg font-bold text-blue-300">
                                Rp {{ number_format($totalInvestasi, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="p-4 rounded-lg bg-emerald-500/10 border border-emerald-400/20">
                            <p class="text-xs text-gray-400">Nilai Akhir</p>
                            <p class="text-lg font-bold text-emerald-300">
                                Rp {{ number_format($nilaiAkhir, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="p-4 rounded-lg {{ $keuntungan >= 0 ? 'bg-emerald-500/10 border border-emerald-400/20' : 'bg-red-500/10 border border-red-400/20' }}">
                            <p class="text-xs text-gray-400">Estimasi Keuntungan</p>
                            <p class="text-lg font-bold {{ $keuntungan >= 0 ? 'text-emerald-300' : 'text-red-300' }}">
                                Rp {{ number_format($keuntungan, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 mt-3 italic">
                        *Asumsi kenaikan harga 1% per bulan. Hasil aktual bisa berbeda tergantung kondisi pasar.
                    </p>
                </div>
            @endisset

            {{-- Riwayat Simulasi --}}
            @isset($riwayatSimulasi)
                <div class="rounded-2xl overflow-hidden bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <div class="p-4 border-b border-white/10">
                        <h3 class="text-md font-semibold text-white">
                            Riwayat Simulasi Terakhir
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/[0.02]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-400 text-[10px] uppercase tracking-wider">Nama Aset</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Harga Saat Ini</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Nilai Akhir DCA</th>
                                    <th class="px-4 py-3 text-left text-gray-400 text-[10px] uppercase tracking-wider">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($riwayatSimulasi as $item)
                                    <tr class="hover:bg-white/[0.03] transition">
                                        <td class="px-4 py-3 text-gray-200">
                                            {{ $item->nama_aset }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-300">
                                            Rp {{ number_format($item->harga_saat_ini, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-emerald-400">
                                            Rp {{ number_format($item->simulasi_dca ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-400 text-xs">
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