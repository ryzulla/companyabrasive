<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            [
                'meta'  => 'Tips & Teknik',
                'title' => 'Cara Memilih Grinding Wheel yang Tepat untuk Jenis Material',
                'desc'  => 'Memilih grinding wheel yang salah dapat menyebabkan hasil kerja buruk, keausan cepat, bahkan kecelakaan kerja. Artikel ini membahas cara memilih grinding wheel berdasarkan jenis material: baja karbon, stainless steel, aluminium, dan besi cor. Perhatikan faktor-faktor seperti kode abrasif (A untuk aluminium oksida, C untuk silicon carbide), ukuran grit, dan kecepatan maksimum (RPM). Untuk baja biasa, gunakan grinding wheel berbahan aluminium oksida dengan grit 36–60 untuk pengerjaan kasar dan grit 80–120 untuk finishing. Stainless steel memerlukan grinding wheel bebas besi dan belerang untuk mencegah kontaminasi. Selalu periksa label "Maximum RPM" pada wheel dan pastikan tidak melebihi kecepatan mesin Anda.',
                'img'   => null,
                'created_at' => now()->subDays(5),
            ],
            [
                'meta'  => 'Keamanan Kerja',
                'title' => 'Standar Keselamatan Penggunaan Cutting Disc di Industri',
                'desc'  => 'Kecelakaan akibat cutting disc yang pecah saat digunakan masih sering terjadi di industri. Artikel ini membahas standar keselamatan internasional (EN 12413 dan ANSI B7.1) yang wajib dipatuhi operator. Selalu gunakan pelindung mata (kacamata safety atau face shield), sarung tangan, dan pelindung wajah saat menggunakan cutting disc. Jangan pernah menggunakan cutting disc yang rusak, retak, atau telah melewati tanggal kedaluwarsa. Pastikan pelindung mesin terpasang dengan benar dan tidak dilepas. Simpan cutting disc di tempat yang kering dan terhindar dari benturan untuk mencegah kerusakan tersembunyi.',
                'img'   => null,
                'created_at' => now()->subDays(12),
            ],
            [
                'meta'  => 'Produk & Inovasi',
                'title' => 'Keunggulan Flap Disc Dibanding Grinding Wheel Konvensional',
                'desc'  => 'Flap disc menjadi pilihan populer di kalangan profesional fabrikasi logam karena kemampuannya menggabungkan fungsi grinding, blending, dan finishing dalam satu alat. Berbeda dengan grinding wheel konvensional yang keras dan cenderung membuat permukaan kasar, flap disc menghasilkan permukaan yang lebih halus dengan kontrol lebih baik. Struktur "kelopak" (flap) dari aluminium oksida memberikan pendinginan yang lebih baik, mengurangi panas berlebih pada material. Flap disc juga lebih aman karena tidak mudah pecah seperti bonded abrasive. Tersedia dalam berbagai grit dari 40 (agresif) hingga 120 (halus) untuk berbagai tahap pengerjaan.',
                'img'   => null,
                'created_at' => now()->subDays(18),
            ],
            [
                'meta'  => 'Panduan Penggunaan',
                'title' => 'Teknik Pengamplasan yang Benar untuk Hasil Finishing Sempurna',
                'desc'  => 'Finishing permukaan yang baik dimulai dari teknik pengamplasan yang tepat. Mulailah selalu dengan grit kasar untuk menghilangkan cacat, karat, atau cat lama, kemudian lanjutkan secara bertahap ke grit yang lebih halus. Jangan melompat terlalu jauh dalam urutan grit — misalnya dari 60 langsung ke 220 — karena goresan dari grit sebelumnya tidak akan hilang sempurna. Untuk pengamplasan kayu, gerakan searah serat memberikan hasil terbaik. Untuk logam, gerakan melingkar pada tahap awal dan lurus pada tahap finishing. Gunakan blok sanding untuk permukaan datar agar tekanan merata. Ganti amplas secara berkala; amplas yang sudah tumpul bekerja lebih lambat dan menghasilkan panas berlebih.',
                'img'   => null,
                'created_at' => now()->subDays(25),
            ],
            [
                'meta'  => 'Industri & Aplikasi',
                'title' => 'Aplikasi Produk Abrasif di Industri Perkapalan Indonesia',
                'desc'  => 'Industri perkapalan Indonesia yang terus berkembang membutuhkan produk abrasif berkualitas tinggi untuk berbagai proses produksi. Mulai dari persiapan permukaan (surface preparation) lambung kapal sebelum pengecatan, pemotongan pelat baja tebal, pengelasan dan penggerindaan sambungan, hingga finishing interior kapal. Grinding wheel berkecepatan tinggi digunakan untuk memotong pelat baja kapal yang tebal. Flap disc digunakan untuk menghaluskan area las dan mempersiapkan permukaan sebelum dicat. Wire brush digunakan untuk membersihkan karat dan sisa terak las. PT PAL Indonesia dan galangan kapal swasta di Surabaya, Batam, dan Makassar menjadi pengguna utama produk abrasif industri grade.',
                'img'   => null,
                'created_at' => now()->subDays(32),
            ],
            [
                'meta'  => 'Tips & Teknik',
                'title' => 'Perbedaan Abrasif Bonded, Coated, dan Non-Woven: Kapan Menggunakannya?',
                'desc'  => 'Dunia abrasif terbagi menjadi tiga kategori utama yang masing-masing memiliki keunggulan berbeda. Bonded abrasive (seperti grinding wheel dan cutting disc) menggunakan butiran abrasif yang terikat dalam matriks keras, cocok untuk pengerjaan agresif dan pemotongan. Coated abrasive (seperti amplas kertas, kain, dan sanding belt) memiliki butiran abrasif yang dilapisi di atas substrat fleksibel, ideal untuk finishing dan pengamplasan permukaan luas. Non-woven abrasive (seperti scotch-brite) menggunakan serat sintetis yang dikombinasikan dengan butiran abrasif, sempurna untuk deburring, cleaning, dan polishing tanpa merusak dimensi komponen. Pemilihan kategori yang tepat bergantung pada material, tahap pengerjaan, dan hasil akhir yang diinginkan.',
                'img'   => null,
                'created_at' => now()->subDays(40),
            ],
        ];

        foreach ($blogs as $data) {
            Blog::create($data);
        }
    }
}
