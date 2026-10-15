<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dasbor Edukasi Finansial</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-4">📊 Dasbor Edukasi Finansial</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded">
                <p class="text-sm text-gray-600">Total Aset Dianalisis</p>
                <p class="text-2xl font-bold">{{ $totalAset }}</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded">
                <p class="text-sm text-gray-600">Profil Risiko</p>
                <p class="text-2xl font-bold">
                    {{ $riskProfile->tipe_risiko ?? 'Belum ada' }}
                </p>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('edukasi.kuis') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
               Mulai Kuis Risiko
            </a>
            <a href="{{ route('edukasi.kalkulator') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
               Kalkulator DCA
            </a>
        </div>
    </div>
</body>
</html>