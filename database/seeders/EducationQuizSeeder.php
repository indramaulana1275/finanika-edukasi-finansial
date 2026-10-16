<?php

namespace Database\Seeders;

use App\Models\EducationModule;
use App\Models\EducationQuiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = [
            // === MODUL 1: Dasar Investasi untuk Mahasiswa ===
            [
                'module_slug' => 'dasar-investasi-mahasiswa',
                'quizzes' => [
                    [
                        'question' => 'Apa tujuan utama dari investasi?',
                        'option_a' => 'Menabung uang di dompet',
                        'option_b' => 'Memperta-nnggung uang agar nilainya tumbuh',
                        'option_c' => 'Menghabiskan semua uang saku',
                        'option_d' => 'Pinjam uang ke teman',
                        'correct_answer' => 'b',
                        'explanation' => 'Investasi bertujuan mempertumbuhkan nilai uang melalui aset yang nilainya dapat naik seiring waktu.',
                    ],
                    [
                        'question' => 'Apa beda utama menabung dan investasi?',
                        'option_a' => 'Keduanya sama',
                        'option_b' => 'Menabung nilai tetap, investasi nilai bisa tumbuh',
                        'option_c' => 'Investasi lebih aman dari tabungan',
                        'option_d' => 'Tabungan tidak ada risiko sama sekali',
                        'correct_answer' => 'b',
                        'explanation' => 'Tabungan nilai tetap (bisa tergerus inflasi), sedangkan investasi nilainya dapat berkembang tapi ada risiko.',
                    ],
                    [
                        'question' => 'Apa itu compound interest?',
                        'option_a' => 'Bunga yang hanya dihitung sekali',
                        'option_b' => 'Bunga atas bunga yang sudah tersimpan',
                        'option_c' => 'Bunga yang diturunkan bank',
                        'option_d' => 'Bunga untuk pinjaman',
                        'correct_answer' => 'b',
                        'explanation' => 'Compound interest adalah bunga majemuk di mana bunga dihitung atas modal ditambah bunga yang telah tersimpan sebelumnya.',
                    ],
                    [
                        'question' => 'Mengapa mahasiswa disarankan mulai investasi muda?',
                        'option_a' => 'Sudah punya uang banyak',
                        'option_b' => 'Waktu masih panjang, compound interest bekerja maksimal',
                        'option_c' => 'Tidak ada risiko sama sekali',
                        'option_d' => 'Wajib dari kampus',
                        'correct_answer' => 'b',
                        'explanation' => 'Memulai muda memberikan waktu yang lebih lama untuk pertumbuhan dana melalui efek bunga majemuk.',
                    ],
                    [
                        'question' => 'Contoh aset investasi yang cocok untuk pemula adalah?',
                        'option_a' => 'Aset misterius janji untung 100%',
                        'option_b' => 'Reksadana pendapatan tetap atau emas',
                        'option_c' => 'Semua uang ke satu saham sembarangan',
                        'option_d' => 'Simpan di bawah bantal',
                        'correct_answer' => 'b',
                        'explanation' => 'Reksadana pendapatan tetap dan emas adalah aset yang relatif stabil dan cocok untuk pemula.',
                    ],
                ],
            ],
            // === MODUL 2: Memahami Profil Risiko ===
            [
                'module_slug' => 'memahami-profil-risiko',
                'quizzes' => [
                    [
                        'question' => 'Apa itu profil risiko dalam investasi?',
                        'option_a' => 'Berapa uang yang kamu punya',
                        'option_b' => 'Toleransi seseorang terhadap kerugian investasi',
                        'option_c' => 'Jenis aset yang paling mahal',
                        'option_d' => 'Umur investor',
                        'correct_answer' => 'b',
                        'explanation' => 'Profil risiko mengukur seberapa besar toleransi seseorang terhadap fluktuasi dan kerugian dalam investasi.',
                    ],
                    [
                        'question' => 'Aset yang cocok untuk profil Konservatif adalah?',
                        'option_a' => 'Crypto speculatif',
                        'option_b' => 'Deposito, obligasi pemerintah, emas',
                        'option_c' => 'Saham pertumbuhan agresif',
                        'option_d' => 'Forex dengan leverage tinggi',
                        'correct_answer' => 'b',
                        'explanation' => 'Profil Konservatif prioritasnya keamanan dana, cocok untuk aset stabil seperti deposito, obligasi, dan emas.',
                    ],
                    [
                        'question' => 'Profil Moderat cenderung?',
                        'option_a' => 'Hanya aset stabil',
                        'option_b' => 'Campuran aset stabil dan berisiko',
                        'option_c' => 'Hanya crypto',
                        'option_d' => 'Tidak investasi sama sekali',
                        'correct_answer' => 'b',
                        'explanation' => 'Profil Moderat mencampur aset stabil dan aset berisiko secara seimbang.',
                    ],
                    [
                        'question' => 'Jika investasi Rp 1 juta turun jadi Rp 700 ribu dan kamu panik langsung jual, profilmu cenderung?',
                        'option_a' => 'Agresif',
                        'option_b' => 'Moderat',
                        'option_c' => 'Konservatif',
                        'option_d' => 'Netral',
                        'correct_answer' => 'c',
                        'explanation' => 'Reaksi panik dan langsung jual saat menurun adalah ciri khas profil Konservatif.',
                    ],
                    [
                        'question' => 'Tidak ada profil risiko yang terbaik, karena?',
                        'option_a' => 'Semua profil sama',
                        'option_b' => 'Setiap orang memiliki situasi dan jiwa keuangan yang berbeda',
                        'option_c' => 'Profil risiko tidak berpengaruh',
                        'option_d' => 'Hanya satu profil yang ada',
                        'correct_answer' => 'b',
                        'explanation' => 'Profil risiko yang terbaik adalah yang sesuai dengan situasi finansial dan toleransi risiko masing-masing individu.',
                    ],
                ],
            ],
            // === MODUL 3: Apa Itu DCA ===
            [
                'module_slug' => 'apa-itu-dca',
                'quizzes' => [
                    [
                        'question' => 'Apa itu DCA (Dollar Cost Averaging)?',
                        'option_a' => 'Beli aset sekali dengan modal besar',
                        'option_b' => 'Beli aset secara rutin dengan nominal tetap',
                        'option_c' => 'Jual aset setiap hari',
                        'option_d' => 'Pinjam uang untuk beli aset',
                        'correct_answer' => 'b',
                        'explanation' => 'DCA adalah strategi beli aset secara rutin dengan nominal tetap, terlepas dari naik turunnya harga.',
                    ],
                    [
                        'question' => 'Manfaat utama DCA adalah?',
                        'option_a' => 'Jaminan untung 100%',
                        'option_b' => 'Merata-risiko dan menghindari menebak waktu pasar',
                        'option_c' => 'Harga selalu naik',
                        'option_d' => 'Tidak ada risiko',
                        'correct_answer' => 'b',
                        'explanation' => 'DCA membantu merata-risiko dan menghindari kebutuhan menebak "titik terendah" pasar.',
                    ],
                    [
                        'question' => 'DCA cocok untuk mahasiswa karena?',
                        'option_a' => 'Perlu modal besar',
                        'option_b' => 'Bisa mulai dengan nominal kecil dan jadwal tetap',
                        'option_c' => 'Tidak perlu memikirkan risiko',
                        'option_d' => 'Harga selalu turun',
                        'correct_answer' => 'b',
                        'explanation' => 'DCA memungkinkan memulai dengan nominal kecil dan jadwal beli yang tetap tanpa perlu memantau harga terus-menerus.',
                    ],
                    [
                        'question' => 'Keterbatasan DCA adalah?',
                        'option_a' => 'Selalu untung',
                        'option_b' => 'Tidak menjamin untung jika aset terus turun lama',
                        'option_c' => 'Hanya untuk saham',
                        'option_d' => 'Perlu modal besar',
                        'correct_answer' => 'b',
                        'explanation' => 'DCA tidak menjamin untung, terutama jika aset yang dipilih mengalami penurunan jangka panjang.',
                    ],
                    [
                        'question' => 'Dicairkan atau tidak, DCA paling efektif jika?',
                        'option_a' => 'Beli sekaligus pada puncak harga',
                        'option_b' => 'Dilakukan secara konsisten dan rutin',
                        'option_c' => 'Hanya beli saat harga turun',
                        'option_d' => 'Tidak ada jadwal pasti',
                        'correct_answer' => 'b',
                        'explanation' => 'Konsistensi dan rutinitas adalah kunci keberhasilan strategi DCA.',
                    ],
                ],
            ],
            // === MODUL 4: Diversifikasi Portofolio ===
            [
                'module_slug' => 'diversifikasi-portofolio',
                'quizzes' => [
                    [
                        'question' => 'Apa inti dari diversifikasi portofolio?',
                        'option_a' => 'Semua uang ke satu aset',
                        'option_b' => 'Jangan taruh semua telur dalam satu keranjang',
                        'option_c' => 'Hanya investasi di crypto',
                        'option_d' => 'Jual semua aset sekaligus',
                        'correct_answer' => 'b',
                        'explanation' => 'Diversifikasi berarti membagi investasi ke berbagai aset agar risiko tidak terpusat di satu tempat.',
                    ],
                    [
                        'question' => 'Mengapa diversifikasi penting?',
                        'option_a' => 'Untung pasti',
                        'option_b' => 'Kerugian di satu aset bisa ditimbang oleh aset lain',
                        'option_c' => 'Harga selalu naik',
                        'option_d' => 'Tidak perlu menganalisis aset',
                        'correct_answer' => 'b',
                        'explanation' => 'Dengan campuran aset, kenaikan di satu aset dapat menimbangkan penurunan di aset lain.',
                    ],
                    [
                        'question' => 'Contoh diversifikasi yang tepat adalah?',
                        'option_a' => 'Semua uang ke satu saham teknologi',
                        'option_b' => 'Campuran emas, saham blue chip, reksadana, dan sedikit kripto',
                        'option_c' => 'Semua uang ke Dogecoin',
                        'option_d' => 'Simpan semua di bawah bantal',
                        'correct_answer' => 'b',
                        'explanation' => 'Campuran berbagai jenis aset mengurangi risiko konsentrasi di satu aset.',
                    ],
                    [
                        'question' => 'Diversifikasi mengurangi risiko, tapi?',
                        'option_a' => 'Menghapus semua risiko',
                        'option_b' => 'Tidak menghapus risiko sepenuhnya',
                        'option_c' => 'Jaminan untung',
                        'option_d' => 'Hanya untuk kaya',
                        'correct_answer' => 'b',
                        'explanation' => 'Diversifikasi mengurangi risiko konsentrasi, tapi tidak bisa menghapus risiko pasar secara keseluruhan.',
                    ],
                    [
                        'question' => 'Saat krisis global, banyak aset cenderung?',
                        'option_a' => 'Naik bersama',
                        'option_b' => 'Turun bersama',
                        'option_c' => 'Tetap stabil',
                        'option_d' => 'Tidak berubah',
                        'correct_answer' => 'b',
                        'explanation' => 'Pada krisis global, korelasi antar aset cenderung naik dan banyak aset turun bersama-sama.',
                    ],
                ],
            ],
            // === MODUL 5: Mengenal Saham dan Reksadana ===
            [
                'module_slug' => 'mengenal-saham-reksadana',
                'quizzes' => [
                    [
                        'question' => 'Apa itu saham?',
                        'option_a' => 'Pinjaman ke bank',
                        'option_b' => 'Bukti kepemilikan sebagian perusahaan',
                        'option_c' => 'Tabungan di bank',
                        'option_d' => 'Asuransi jiwa',
                        'correct_answer' => 'b',
                        'explanation' => 'Saham adalah bukti kepemilikan parsial atas sebuah perusahaan.',
                    ],
                    [
                        'question' => 'Keuntungan dari saham berasal dari?',
                        'option_a' => 'Hanya dividen',
                        'option_b' => 'Capital gain dan dividen',
                        'option_c' => 'Hanya bunga bank',
                        'option_d' => 'Tidak ada keuntungan',
                        'correct_answer' => 'b',
                        'explanation' => 'Keuntungan saham berasal dari laba selisih jual beli (capital gain) dan dividen.',
                    ],
                    [
                        'question' => 'Apa itu reksadana?',
                        'option_a' => 'Pinjaman online',
                        'option_b' => 'Kumpulan dana investor yang dikelola secara profesional',
                        'option_c' => 'Tabungan rencana',
                        'option_d' => 'Aset kripto',
                        'correct_answer' => 'b',
                        'explanation' => 'Reksadana adalah wadah koleksi dana dari banyak investor yang dikelola oleh manajer investasi profesional.',
                    ],
                    [
                        'question' => 'Keuntungan reksadana bagi pemula adalah?',
                        'option_a' => 'Perlu memilih saham sendiri',
                        'option_b' => 'Ada profesional yang mengelola dana',
                        'option_c' => 'Modal yang sangat besar',
                        'option_d' => 'Risiko nol',
                        'correct_answer' => 'b',
                        'explanation' => 'Reksadana cocok untuk pemula karena pengelolaan diserahkan kepada profesional.',
                    ],
                    [
                        'question' => 'Jenis reksadana dengan risiko tertinggi biasanya?',
                        'option_a' => 'Reksadana Uang',
                        'option_b' => 'Reksadana Saham',
                        'option_c' => 'Reksadana Obligasi',
                        'option_d' => 'Reksadana Tabungan',
                        'correct_answer' => 'b',
                        'explanation' => 'Reksadana Saham sebagian besar diinvestasikan ke saham, sehingga risiko dan potensi return-nya lebih tinggi.',
                    ],
                ],
            ],
            // === MODUL 6: Cryptocurrency: Peluang dan Risiko ===
            [
                'module_slug' => 'cryptocurrency-peluang-risiko',
                'quizzes' => [
                    [
                        'question' => 'Apa itu Bitcoin?',
                        'option_a' => 'Mata uang kertas',
                        'option_b' => 'Crypto pertama dan terbesar, emas digital',
                        'option_c' => 'Saham di Amerika',
                        'option_d' => 'Emas fisik',
                        'correct_answer' => 'b',
                        'explanation' => 'Bitcoin adalah cryptocurrency pertama dan terbesar, sering disebut emas digital karena suplai terbatas.',
                    ],
                    [
                        'question' => 'Apa itu Ethereum?',
                        'option_a' => 'Mata uang digital China',
                        'option_b' => 'Platform blockchain untuk smart contract dan dApp',
                        'option_c' => 'Saham teknologi',
                        'option_d' => 'Deposito bank',
                        'correct_answer' => 'b',
                        'explanation' => 'Ethereum adalah platform blockchain yang mendukung smart contract dan aplikasi terdesentralisasi.',
                    ],
                    [
                        'question' => 'Risiko utama crypto adalah?',
                        'option_a' => 'Harga selalu naik',
                        'option_b' => 'Volatilitas ekstrem dan risiko keamanan',
                        'option_c' => 'Tidak bisa dijual',
                        'option_d' => 'Hanya untuk orang tua',
                        'correct_answer' => 'b',
                        'explanation' => 'Harga crypto dapat naik atau turun drastis dalam waktu singkat, dan ada risiko kehilangan akses dompet digital.',
                    ],
                    [
                        'question' => 'Tips untuk mahasiswa yang ingin coba crypto adalah?',
                        'option_a' => 'Semua uang ke satu crypto',
                        'option_b' => 'Alokasikan sebagian kecil dan gunakan DCA',
                        'option_c' => 'Believe janji untung pasti',
                        'option_d' => 'Simpan private key di media sosial',
                        'correct_answer' => 'b',
                        'explanation' => 'Alokasikan sebagian kecil portofolio dan gunakan DCA untuk mengurangi risiko timing pasar.',
                    ],
                    [
                        'question' => 'Crypto cocok untuk bagian mana dalam portofolio?',
                        'option_a' => 'Dana darurat',
                        'option_b' => 'Bagian agresif portofolio',
                        'option_c' => 'Semua portofolio',
                        'option_d' => 'Tidak sama sekali',
                        'correct_answer' => 'b',
                        'explanation' => 'Karena risiko tinggi, crypto cocok sebagai bagian agresif portofolio, bukan dana darurat.',
                    ],
                ],
            ],
            // === MODUL 7: Emas sebagai Lindung Nilai ===
            [
                'module_slug' => 'emas-lindung-nilai',
                'quizzes' => [
                    [
                        'question' => 'Mengapa emas disebut safe haven?',
                        'option_a' => 'Harga selalu turun',
                        'option_b' => 'Nilai cenderung stabil atau naik saat krisis',
                        'option_c' => 'Hanya untuk perhiasan',
                        'option_d' => 'Tidak ada nilai',
                        'correct_answer' => 'b',
                        'explanation' => 'Emas dianggap aman saat krisis ekonomi karena nilainya cenderung stabil atau naik ketika aset lain runtuh.',
                    ],
                    [
                        'question' => 'Cara investasi emas yang mudah untuk pemula?',
                        'option_a' => 'Beli tambang emas',
                        'option_b' => 'Emas digital lewat aplikasi bank',
                        'option_c' => 'Beli emas di toko sembako',
                        'option_d' => 'Pinjam emas',
                        'correct_answer' => 'b',
                        'explanation' => 'Emas digital mudah diakses dan bisa dimulai dengan nominal kecil melalui aplikasi bank.',
                    ],
                    [
                        'question' => 'Keterbatasan emas sebagai investasi adalah?',
                        'option_a' => 'Selalu naik',
                        'option_b' => 'Tidak menghasilkan dividen atau bunga',
                        'option_c' => 'Tidak bisa dijual',
                        'option_d' => 'Hanya untuk kaya',
                        'correct_answer' => 'b',
                        'explanation' => 'Keuntungan emas hanya berasal dari selisih harga jual beli, tidak ada dividen atau bunga rutin.',
                    ],
                    [
                        'question' => 'Emas cocok sebagai apa dalam portofolio?',
                        'option_a' => 'Aset spekulatif utama',
                        'option_b' => 'Penstabil / lindung nilai',
                        'option_c' => 'Hanya untuk trading harian',
                        'option_d' => 'Tidak perlu ada',
                        'correct_answer' => 'b',
                        'explanation' => 'Emas berfungsi sebagai penstabil dan lindung nilai dalam portofolio saat krisis.',
                    ],
                    [
                        'question' => 'Alokasi emas yang disarankan dalam portofolio adalah?',
                        'option_a' => '100%',
                        'option_b' => 'Sebagian kecil, misal 10-20%',
                        'option_c' => 'Tidak perlu sama sekali',
                        'option_d' => 'Semua dana darurat',
                        'correct_answer' => 'b',
                        'explanation' => 'Alokasi sebagian kecil (10-20%) emas cukup sebagai cadangan tanpa mengkonentrasikan risiko.',
                    ],
                ],
            ],
            // === MODUL 8: Pajak dan Regulasi Investasi di Indonesia ===
            [
                'module_slug' => 'pajak-regulasi-investasi-indonesia',
                'quizzes' => [
                    [
                        'question' => 'Lembaga yang mengawasi sekuritas di Indonesia adalah?',
                        'option_a' => 'Bank Indonesia saja',
                        'option_b' => 'OJK (Otoritas Jasa Keuangan)',
                        'option_c' => 'BPPK',
                        'option_d' => 'Pemerintah desa',
                        'correct_answer' => 'b',
                        'explanation' => 'OJK adalah lembaga yang mengawasi aktivitas jasa keuangan termasuk sekuritas dan reksadana di Indonesia.',
                    ],
                    [
                        'question' => 'Pajak pada transaksi kripto di Indonesia mencakup?',
                        'option_a' => 'Tidak ada pajak',
                        'option_b' => 'PPN 0,1% dan PPh 0,1% dari nilai transaksi',
                        'option_c' => 'Pajak 50%',
                        'option_d' => 'Hanya PPN',
                        'correct_answer' => 'b',
                        'explanation' => 'Transaksi kripto di Indonesia dikenakan PPN 0,1% dan PPh 0,1% dari nilai transaksi sesuai regulasi.',
                    ],
                    [
                        'question' => 'Cara memastikan investasi aman di Indonesia?',
                        'option_a' => 'Pilih entitas terdaftar dan diawasi OJK',
                        'option_b' => 'Terima janji untung pasti',
                        'option_c' => 'Cari anggota baru untuk bonus',
                        'option_d' => 'Investasi di aset misterius',
                        'correct_answer' => 'a',
                        'explanation' => 'Selalu verifikasi apakah perusahaan sekuritas atau bursa crypto terdaftar di OJK/Bappebti.',
                    ],
                    [
                        'question' => 'Ciri-ciri investasi penipuan (Ponzi) adalah?',
                        'option_a' => 'Ada risiko yang jelas',
                        'option_b' => 'Janji untung pasti dan mencari anggota',
                        'option_c' => 'Terdaftar di OJK',
                        'option_d' => 'Laporan keuangan transparan',
                        'correct_answer' => 'b',
                        'explanation' => 'Janji "untung pasti" dan sistem referral/anggotakan adalah ciri khas skema penipuan.',
                    ],
                    [
                        'question' => 'Dividen saham di Indonesia biasanya?',
                        'option_a' => 'Tidak ada pajak sama sekali',
                        'option_b' => 'Sudah dipotong PPh Final oleh penerbit',
                        'option_c' => 'Pajak 100%',
                        'option_d' => 'Hanya untuk orang asing',
                        'correct_answer' => 'b',
                        'explanation' => 'Dividen saham biasanya sudah dipotong pajak final oleh pihak penerbit, jadi tidak perlu dilaporkan lagi.',
                    ],
                ],
            ],
        ];

        foreach ($quizzes as $group) {
            $module = EducationModule::where('slug', $group['module_slug'])->first();

            if (! $module) {
                continue;
            }

            foreach ($group['quizzes'] as $index => $quiz) {
                EducationQuiz::updateOrCreate(
                    [
                        'module_id' => $module->id,
                        'order_index' => $index + 1,
                    ],
                    [
                        'question' => $quiz['question'],
                        'option_a' => $quiz['option_a'],
                        'option_b' => $quiz['option_b'],
                        'option_c' => $quiz['option_c'],
                        'option_d' => $quiz['option_d'],
                        'correct_answer' => $quiz['correct_answer'],
                        'explanation' => $quiz['explanation'],
                    ]
                );
            }
        }
    }
}
