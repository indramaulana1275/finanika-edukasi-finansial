<?php

namespace Database\Seeders;

use App\Models\EducationModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'slug' => 'dasar-investasi-mahasiswa',
                'title' => 'Dasar Investasi untuk Mahasiswa',
                'description' => 'Kenapa investasi penting dan bedanya investasi vs menabung.',
                'content' => <<<'KONTEN'
Investasi bukan cuma buat orang tua atau orang yang sudah punya uang banyak, lho! Justru sebagai mahasiswa, ini waktu yang tepat buat mulai belajar mengelola uang dan menanamkan bibit keuangan dewasa.

Apa Itu Investasi?
Investasi adalah kegiatan menanamkan uang atau modal ke dalam suatu aset dengan harapan nilainya akan tumbuh seiring waktu. Contohnya: beli saham, beli emas, simpan di reksadana, atau beli crypto. Tujuannya agar uang kamu tidak cuma diam di dompet, tapi bekerja untukmu.

Bedanya Menabung dan Investasi
Banyak yang bingung: "Sudah tabungan, kok harus investasi lagi?" Inilah bedanya:
• Menabung: uang kamu disimpan di bank atau tabungan. Nilainya tetap, bahkan bisa tergerus inflasi (naiknya harga barang). Tabungan cocok untuk dana darurat, bukan untuk pertumbuhan.
• Investasi: uang kamu dipertumbuhkan ke aset yang nilainya bisa naik. Ada risiko, tapi potensi untungnya lebih besar dari bunga tabungan.

Contoh nyata: Kamu tabung Rp 500.000 per bulan selama 10 tahun di tabungan (bunga 0%) = Rp 60 juta. Tapi jika investasi dengan return rata-rata 10% per tahun, uang itu bisa jadi lebih dari Rp 100 juta karena efek bunga majemuk (compound interest).

Kenapa Mahasiswa Perlu Investasi Sekarang?
1. Waktu masih panjang — compound interest bekerja maksimal kalau mulai muda.
2. Risiko relatif kecil — belum ada tanggungan besar seperti rumah atau anak.
3. Belajar sambil praktik — salah sedikit dalam investasi masih bisa diperbaiki.
4. Membangun disiplin keuangan — belajar memisahkan dana konsumsi dan dana investasi.

Contoh Praktis
Budi mahasiswa semester 3, uang saku Rp 2 juta per bulan. Dia memutuskan untuk investasi Rp 200.000 per bulan di reksadana campuran. Setelah 4 tahun (saat lulus), dengan asumsi return 8% per tahun, Budi sudah punya sekitar Rp 11,5 juta. Ini bisa jadi modal awal kerjanya atau dana emergency setelah lulus.

Tips Awal
Mulai kecil, jangan nunggu "sudah punya uang banyak". Bahkan Rp 50.000 per bulan sudah cukup buat mulai belajar investasi. Pentingnya adalah memulai dan konsisten.
KONTEN
                ,
                'order_index' => 1,
                'icon' => '📚',
                'category' => 'dasar',
            ],
            [
                'slug' => 'memahami-profil-risiko',
                'title' => 'Memahami Profil Risiko',
                'description' => 'Konservatif, Moderat, dan Agresif — mana yang cocok denganmu?',
                'content' => <<<'KONTEN'
Setiap orang memiliki reaksi yang berbeda terhadap risiko. Ada yang tenang saat investasi turun 20%, ada yang panik saat turun cuma 5%. Itulah yang disebut profil risiko.

Apa Itu Profil Risiko?
Profil risiko mengukur seberapa besar toleransi kamu terhadap kerugian dalam investasi. Mengetahui profil risiko penting karena akan menentukan tipe aset yang cocok untuk kamu. Jika kamu pakai aset yang terlalu agresif tapi profilnya konservatif, kamu cenderung panik dan jual pada saat salah.

Tiga Tipe Profil Risiko

1. Konservatif
Cocok untuk kamu yang prioritasnya keamanan dana, tidak suka kerugian, dan siap terima return yang lebih kecil. Contoh aset: deposito, obligasi pemerintah, emas, reksadana pendapatan tetap.
Analogi: Seperti membawa payung saat cerah — lebih aman, tapi tidak akan kena panas terik.

2. Moderat
Cocok untuk kamu yang mau mencampur aset stabil dan agak berisiko. Siap terima fluktuasi sedang imbal return yang cukup baik. Contoh aset: campuran emas + saham blue chip + reksadana campuran + sedikit kripto.
Analogi: Seperti makan nasi dengan lauk — ada sumber karbohidrat (stabil) dan lauk (berisiko) yang seimbang.

3. Agresif
Cocok untuk kamu yang memiliki toleransi risiko tinggi, fokus pertumbuhan maksimal, dan siap mengalami kerugian signifikan dalam jangka pendek untuk potensi keuntungan besar. Contoh aset: saham pertumbuhan, crypto, forex.
Analogi: Seperti memasang balap motor — kecepatan tinggi, tapi risiko jatuh juga lebih besar.

Mengidentifikasi Profilmu
Tanya diri sendiri: Jika investasi Rp 1 juta turun jadi Rp 700 ribu dalam satu minggu, apa reaksimu?
• Panik dan langsung jual → Konservatif
• Tenang, nunggu pulih, bahkan tambahin kalau dana cukup → Moderat atau Agresif
• Lihat sebagai kesempatan beli lebih banyak → Agresif

Kesimpulan
Tidak ada profil yang "terbaik". Yang terbaik adalah profil yang sesuai dengan jiwa dan situasi finansial kamu. Setelah tahu profilnya, kamu bisa menyesuaikan strategi investasi dan alat bantu seperti DCA atau diversifikasi.
KONTEN
                ,
                'order_index' => 2,
                'icon' => '⚖️',
                'category' => 'dasar',
            ],
            [
                'slug' => 'apa-itu-dca',
                'title' => 'Apa Itu DCA (Dollar Cost Averaging)?',
                'description' => 'Strategi beli aset secara rutin di nominal tetap.',
                'content' => <<<'KONTEN'
DCA atau Dollar Cost Averaging adalah strategi investasi di mana kamu membeli suatu aset secara rutin dengan nominal tetap, terlepas dari naik turunnya harga. Tujuannya adalah merata-risiko dan menghindari "timing the market" (menebak waktu terbaik beli).

Bagaimana Cara Kerja DCA?
Misalnya, kamu memutuskan untuk beli Bitcoin sebesar Rp 200.000 tiap hari Senin, tidak peduli harga Bitcoin naik atau turun.
• Minggu 1: Harga BTC $60.000 → kamu dapat 0,0033 BTC
• Minggu 2: Harga BTC turun jadi $50.000 → kamu dapat 0,0040 BTC (dapat lebih banyak karena murah!)
• Minggu 3: Harga BTC naik jadi $70.000 → kamu dapat 0,0028 BTC

Dengan DCA, harga beli rata-ratamu akan lebih stabil dibandingkan beli sekaligus dengan nominal besar pada satu waktu saja.

Kenapa DCA Cocok untuk Mahasiswa?
1. Nominal kecil — bisa mulai dari Rp 50.000 atau Rp 100.000 per minggu.
2. Tidak perlu memantau harga 24 jam — jadwal beli sudah tetap.
3. Mengurangi stres — tidak perlu nembus "titik terendah" yang susah ditebak.
4. Membangun disiplin — jadwal rutin membuat investasi menjadi kebiasaan.

Contoh Perhitungan Sederhana
Kamu investasi Rp 100.000 per bulan selama 12 bulan di aset X:
Total modal = Rp 1.200.000
Karena beli rutin, rata-rata harga beli diturunkan saat harga turun dan dibatasi saat harga naik. Akhirnya, kamu mendapat lebih banyak aset dibanding beli sekaligus saat harga puncak.

Keterbatasan DCA
DCA tidak menjamin untung. Jika aset yang kamu pilih terus turun lama, DCA juga akan mengalami kerugian. Pilih aset dengan fundamental yang baik dan gabungkan dengan diversifikasi.

Kesimpulan
DCA adalah strategi "perlahan tapi pasti". Cocok banget untuk pemula dan mahasiswa yang ingin mulai investasi tanpa stres menebak pasar.
KONTEN
                ,
                'order_index' => 3,
                'icon' => '📈',
                'category' => 'strategi',
            ],
            [
                'slug' => 'diversifikasi-portofolio',
                'title' => 'Diversifikasi Portofolio',
                'description' => 'Jangan taruh semua telur dalam satu keranjang.',
                'content' => <<<'KONTEN'
Diversifikasi adalah strategi membagi investasi ke berbagai aset agar risiko tidak terpusat di satu tempat. Frase populer "jangan taruh semua telur dalam satu keranjang" adalah inti dari diversifikasi.

Mengapa Diversifikasi Penting?
Setiap aset memiliki risiko dan karakteristik yang berbeda. Saat saham turun, emas mungkin naik. Saat crypto rontok, obligasi tetap stabil. Dengan campuran aset, kerugian di satu aset bisa ditimbang oleh keuntungan atau stabilitas di aset lain.

Contoh Tanpa Diversifikasi
Kamu puts semua uang Rp 5 juta ke satu saham teknologi. Tiba-tiba perusahaan itu mengalami skandal dan sahamnya turun 60% dalam seminggu. Portofolio kamu jadi Rp 2 juta. Kerugian besar karena semua telur di satu keranjang.

Contoh Dengan Diversifikasi
Kamu bagi Rp 5 juta jadi:
• Rp 1,5 juta emas (stabil, lindung nilai)
• Rp 1,5 juta saham blue chip (BBCA, BBRI, TLKM)
• Rp 1 juta reksadana campuran
• Rp 1 juta kripto (BTC, ETH)

Jika kripto turun 40%, kerugiannya hanya Rp 400 ribu dari total Rp 5 juta. Emas dan saham masih menahan portofolio.

Cara Diversifikasi yang Tepat
1. Berbeda jenis aset — campur saham, obligasi, emas, properti, kripto.
2. Berbeda sektor — jangan semua saham di sektor teknologi, campur dengan perbankan, konsumsi, energi.
3. Berbeda geografis — campur aset lokal (IHSG) dan internasional (S&P 500).
4. Sesuaikan profil risiko — konservatif lebih banyak aset stabil, agresif lebih banyak aset berisiko.

Keterbatasan
Diversifikasi mengurangi risiko, tapi tidak menghapusnya sepenuhnya. Saat krisis keuangan global, banyak aset cenderung turun bersama.

Kesimpulan
Diversifikasi bukan untuk mencari keuntungan maksimal, tapi untuk melindungi portofolio dari kerugian besar. Mulai dari yang sederhana, kemudian luaskan seiring bertambahnya pengalaman.
KONTEN
                ,
                'order_index' => 4,
                'icon' => '🥚',
                'category' => 'strategi',
            ],
            [
                'slug' => 'mengenal-saham-reksadana',
                'title' => 'Mengenal Saham dan Reksadana',
                'description' => 'Jenis-jenis aset investasi yang populer di Indonesia.',
                'content' => <<<'KONTEN'
Saham dan reksadana adalah dua instrumen investasi yang paling populer di Indonesia, terutama untuk pemula. Kedua-duanya bisa diakses dengan modal yang relatif kecil lewat aplikasi sekuritas atau reksa dana online.

Apa Itu Saham?
Saham adalah bukti kepemilikan sebagian dari sebuah perusahaan. Jika kamu beli saham BBCA, kamu menjadi pemilik parsial Bank Central Asia. Keuntungan dari saham berasal dari dua sumber:
1. Laba selisih jual beli (capital gain) — beli murah, jual mahal.
2. Dividen — bagian laba perusahaan yang dibagikan kepada pemilik saham.

Contoh saham di Indonesia: BBCA (perbankan), BBRI (perbankan), TLKM (telekomunikasi), INDF (pangan). Semua ini terdaftar di Bursa Efek Indonesia (BEI) dan bisa dilacak melalui indeks IHSG.

Apa Itu Reksadana?
Reksadana (reksa dana) adalah kumpulan dana dari banyak investor yang dikelola oleh manajer investasi profesional. Dana itu kemudian diinvestasikan ke berbagai aset seperti saham, obligasi, atau uang.
Keuntungan reksadana: kamu tidak perlu memilih saham sendiri, ada profesional yang mengelola. Cocok untuk pemula yang tidak punya waktu menganalisis pasar.

Jenis Reksadana
• Reksadana Saham — sebagian besar diinvestasikan ke saham. Risiko tinggi, potensi return tinggi.
• Reksadana Campuran — campuran saham dan obligasi. Risiko sedang.
• Reksadana Obligasi / Pendapatan Tetap — fokus ke obligasi. Risiko rendah, return stabil.
• Reksadana Uang — investasi di instrumen pendek (deposito, obr). Risiko sangat rendah.

Perbandingan Singkat
| Aspek | Saham | Reksadana |
|-------|-------|-----------|
| Pengelolaan | Sendiri | Profesional |
| Biaya | Komisi transaksi | Biaya manajemen |
| Risiko | Tergantung pilihan kamu | Tergantung jenis reksadana |
| Modal awal | Boleh kecil | Boleh kecil (Rp 10.000+) |

Kesimpulan
Jika kamu suka menganalisis perusahaan dan punya waktu, saham bisa jadi pilihan. Jika ingin lebih santai dan menyerahkan pada profesional, reksadana adalah solusi yang tepat. Keduanya bisa dikombinasikan dalam satu portofolio.
KONTEN
                ,
                'order_index' => 5,
                'icon' => '🏛️',
                'category' => 'aset',
            ],
            [
                'slug' => 'cryptocurrency-peluang-risiko',
                'title' => 'Cryptocurrency: Peluang dan Risiko',
                'description' => 'Mengenal BTC, ETH, dan aset digital lainnya.',
                'content' => <<<'KONTEN'
Cryptocurrency atau "crypto" adalah aset digital yang menggunakan teknologi blockchain untuk transaksi yang terdesentralisasi. Tidak ada bank pusat yang mengaturnya — jaringan komputer di seluruh dunia yang memvalidasi transaksi.

Apa Itu Bitcoin dan Ethereum?
• Bitcoin (BTC) — Crypto pertama dan terbesar, sering disebut "emas digital". Suplai terbatas 21 juta koin, jadi dianggap sebagai lindung nilai digital.
• Ethereum (ETH) — Platform blockchain yang memungkinkan pembuatan smart contract dan aplikasi terdesentralisasi (dApp). ETH adalah "bahan bakar" jaringannya.

Selain keduanya ada ribuan crypto lain seperti Solana (SOL), Cardano (ADA), Polkadot (DOT), Chainlink (LINK), dan masih banyak lagi.

Peluang Crypto
1. Potensi pertumbuhan tinggi — beberapa crypto pernah naik ratusan atau ribuan persen.
2. Akses 24/7 — pasar crypto tidak pernah tutup, bisa bertransaksi kapan saja.
3. Inovasi keuangan — DeFi, NFT, dan tokenisasi aset nyata.

Risiko Crypto
1. Volatilitas ekstrem — harga bisa naik atau turun 20% dalam sehari.
2. Tidak terregulasi secara penuh di beberapa negara — perlindungan investor terbatas.
3. Risiko keamanan — dompet digital bisa diretas, private key hilang berarti uang hilang selamanya.
4. Risiko regulasi — pemerintah dapat melarang atau membatasi perdagangan crypto.

Tips untuk Mahasiswa
Jika ingin coba crypto, alokasikan hanya sebagian kecil dari portofolio (misal maksimal 10-20%). Gunakan DCA agar tidak kena puncak harga. Simpan di exchange terpercaya atau dompet sendiri (non-custodial) jika sudah lebih paham.

Kesimpulan
Crypto adalah aset berisiko tinggi dengan potensi return tinggi. Cocok untuk bagian agresif portofolio, bukan untuk dana darurat atau uang yang dibutuhkan segera.
KONTEN
                ,
                'order_index' => 6,
                'icon' => '₿',
                'category' => 'aset',
            ],
            [
                'slug' => 'emas-lindung-nilai',
                'title' => 'Emas sebagai Lindung Nilai',
                'description' => 'Mengapa emas dikenal sebagai safe haven.',
                'content' => <<<'KONTEN'
Emas adalah salah satu aset tertua dalam sejarah manusia yang hingga kini tetap bernilai. Di dunia investasi, emas dikenal sebagai "safe haven" atau aset lindung nilai ketika kondisi ekonomi tidak stabil.

Mengapa Emas Dicatat sebagai Safe Haven?
Saat terjadi krisis — misal inflasi tinggi, konflik geopolitik, atau pasar saham runtuh — investor cenderung lari ke aset yang dianggap aman. Emas adalah salah satunya. Nilai emas cenderung stabil atau bahkan naik ketika aset lain (saham, mata uang)経� anxiety.

Contoh: Saat pandemi COVID-19 dimulai, banyak saham turun drastis, tapi harga emas justru naik karena investor mencari perlindungan.

Cara Investasi Emas
1. Emas Fisik — koin atau batangan emas. Keuntungan: milikmu sepenuhnya. Kerugian: perlu tempat simpan yang aman dan ada risiko pencurian.
2. Emas Digital (e-gold / gold savings) — beli emas lewat aplikasi bank atau platform, disimpan secara digital. Mudah dan bisa mulai dari nominal kecil.
3. Reksadana Emas / ETF Emas — investasi lewat reksadana yang melacak harga emas. Tidak perlu menyimpan emas fisik.
4. Antam / Pegadaian — lembaga terpercaya di Indonesia untuk beli emas fisik atau tabungan emas.

Keuntungan Emas
• Lindung nilai dari inflasi — saat nilai uang menurun, harga emas cenderung naik.
• Likuiditas tinggi — mudah dijual kembali kapan saja.
• Nilai intrinsik — emas memiliki nilai nyata, bukan cuma angka di layar.

Keterbatasan Emas
• Emas tidak menghasilkan dividen atau bunga — keuntungan cuma dari selisih harga jual beli.
• Harga bisa stagnan lama saat 경제 sedang stabil dan investor lebih suka aset berisiko.

Kesimpulan
Emas cocok sebagai "penstabil" dalam portofolio. Jangan semua uang di emas, tapi alokasikan sebagian (misal 10-20%) sebagai cadangan saat krisis. Untuk mahasiswa, emas digital bisa jadi pilihan awal karena nominalnya kecil dan mudah diakses.
KONTEN
                ,
                'order_index' => 7,
                'icon' => '🥇',
                'category' => 'aset',
            ],
            [
                'slug' => 'pajak-regulasi-investasi-indonesia',
                'title' => 'Pajak dan Regulasi Investasi di Indonesia',
                'description' => 'OJK, pajak, dan aturan yang perlu diketahui investor.',
                'content' => <<<'KONTEN'
Investasi tidak cuma soal untung dan rugi, tapi juga soal aturan. Di Indonesia, aktivitas investasi diatur oleh beberapa lembaga dan dikenai pajak yang perlu dipahami setiap investor.

Lembaga Pengatur
1. OJK (Otoritas Jasa Keuangan) — Pengawas sekuritas, asuransi, dan reksadana di Indonesia. Semua perusahaan sekuritas dan manajer reksa dana harus terdaftar di OJK.
2. IDX / BEI (Bursa Efek Indonesia) — Tempat perdagangan saham dan obligasi.
3. Bappebti (Badan Pengawas Perdagangan Berjangka Komoditas) — Pengawas perdagangan berjangka, termasuk crypto asset di Indonesia.

Pajak pada Investasi
1. Pajak Penghasilan Final (PPh Final) — Dividen saham dan bunga obligasi biasanya sudah dipotong pajak oleh pihak penerbit, jadi kamu tidak perlu melaporkannya lagi dalam SPT tahunan.
2. PPN (Pajak Pertambahan Nilai) — Transaksi kripto di Indonesia dikenakan PPN 0,1% dan PPh 0,1% dari nilai transaksi (menurut regulasi terkini).
3. Pajak pada Laba Jual Beli Aset — Secara umum, laba investasi di BEI untuk investor individu tidak dikenakan pajak tambahan, tapi selalu periksa regulasi terbaru.

Cara Investasi Aman di Indonesia
• Pilih sekuritas atau manajer investasi yang terdaftar dan diawasi OJK. Cek di situs resmi OJK.
• Untuk crypto, gunakan bursa crypto yang sudah resmi terdaftar di Bappebti (daftar 14+ bursa resmi).
• Jangan percaya janji "untung pasti" atau investasi yang meminta kamu mencari anggota (skema Ponzi / investasi ilegal).

Contoh Kasus
Temanmu menawarkan investasi "jaminan untung 50% per bulan" di aset misterius. Ini adalah ciri-ciri penipuan. Investasi yang legit selalu ada risiko, dan tidak ada yang bisa jaminan untung pasti.

Kesimpulan
Selalu investasi melalui entitas yang terregulasi, pahami kewajiban pajakmu, dan waspadai janji-janji terlalu indah. Keamanan dana sama pentingnya dengan keuntungan investasi.
KONTEN
                ,
                'order_index' => 8,
                'icon' => '📋',
                'category' => 'regulasi',
            ],
        ];

        foreach ($modules as $module) {
            EducationModule::updateOrCreate(
                ['slug' => $module['slug']],
                $module
            );
        }
    }
}
