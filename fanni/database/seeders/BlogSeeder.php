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
                'meta'  => 'Panduan Penggunaan',
                'title' => 'Teknik Pengamplasan yang Benar untuk Hasil Finishing Sempurna',
                'desc'  => 'Finishing permukaan yang baik dimulai dari teknik pengamplasan yang tepat. Mulailah selalu dengan grit kasar untuk menghilangkan cacat, karat, atau cat lama, kemudian lanjutkan secara bertahap ke grit yang lebih halus. Jangan melompat terlalu jauh dalam urutan grit — misalnya dari 60 langsung ke 220 — karena goresan dari grit sebelumnya tidak akan hilang sempurna. Untuk pengamplasan kayu, gerakan searah serat memberikan hasil terbaik. Untuk logam, gerakan melingkar pada tahap awal dan lurus pada tahap finishing. Gunakan blok sanding untuk permukaan datar agar tekanan merata. Ganti amplas secara berkala; amplas yang sudah tumpul bekerja lebih lambat dan menghasilkan panas berlebih.',
                'img'   => null,
                'created_at' => now()->subDays(25),
            ],
            [
                'meta'  => 'Tips & Teknik',
                'title' => 'Perbedaan Abrasif Bonded, Coated, dan Non-Woven: Kapan Menggunakannya?',
                'desc'  => 'Dunia abrasif terbagi menjadi tiga kategori utama yang masing-masing memiliki keunggulan berbeda. Bonded abrasive (seperti grinding wheel dan cutting disc) menggunakan butiran abrasif yang terikat dalam matriks keras, cocok untuk pengerjaan agresif dan pemotongan. Coated abrasive (seperti amplas kertas, kain, dan sanding belt) memiliki butiran abrasif yang dilapisi di atas substrat fleksibel, ideal untuk finishing dan pengamplasan permukaan luas. Non-woven abrasive (seperti scotch-brite) menggunakan serat sintetis yang dikombinasikan dengan butiran abrasif, sempurna untuk deburring, cleaning, dan polishing tanpa merusak dimensi komponen. Pemilihan kategori yang tepat bergantung pada material, tahap pengerjaan, dan hasil akhir yang diinginkan.',
                'img'   => null,
                'created_at' => now()->subDays(40),
            ],
            [
                'meta'  => 'Tips & Teknik',
                'title' => 'Panduan Memilih Grit Amplas untuk Kayu, Besi, dan Dinding',
                'desc'  => '<p>Angka pada kertas amplas menunjukkan ukuran grit, yaitu kerapatan butiran abrasif. Semakin kecil angkanya, semakin kasar amplasnya. Memilih grit yang tepat membuat pekerjaan lebih cepat dan permukaan lebih rata.</p>
<h2>Pembagian grit secara umum</h2>
<ul>
<li><strong>Kasar (40–80):</strong> menghilangkan cat lama, karat tebal, atau meratakan permukaan yang sangat kasar.</li>
<li><strong>Sedang (100–180):</strong> menghaluskan permukaan setelah tahap kasar dan menyiapkan permukaan sebelum dempul atau cat dasar.</li>
<li><strong>Halus (220–400):</strong> penghalusan akhir pada kayu, serta antar lapisan cat dasar atau vernis.</li>
<li><strong>Sangat halus (600 ke atas):</strong> finishing cat dan pemolesan, umumnya memakai amplas tahan air.</li>
</ul>
<h2>Tips pemakaian</h2>
<p>Mulailah dari grit yang paling kasar yang diperlukan, lalu naik bertahap tanpa melompat terlalu jauh, misalnya 80, 120, lalu 180. Melompat terlalu jauh meninggalkan goresan dalam yang tidak terhapus oleh grit halus.</p>
<p>Untuk kayu, amplas searah serat. Untuk besi dan cat, amplas waterproof biasanya dipakai dengan air agar debu terbawa dan permukaan lebih halus. Ganti amplas begitu butirannya aus atau tersumbat, karena amplas yang sudah tumpul justru membuat permukaan panas dan tidak rata.</p>',
                'img'   => null,
                'created_at' => now()->subDays(3),
            ],
            [
                'meta'  => 'Panduan Penggunaan',
                'title' => 'Cara Memilih Batu Gerinda: Potong, Asah, atau Poles?',
                'desc'  => '<p>Batu gerinda dibuat untuk fungsi yang berbeda. Memakai jenis yang salah menurunkan hasil kerja dan membahayakan operator.</p>
<h2>Kenali fungsinya</h2>
<ul>
<li><strong>Batu potong (cutting disc):</strong> tipis, khusus untuk memotong. Jangan dipakai untuk menggerinda dari samping karena bisa pecah.</li>
<li><strong>Batu gerinda asah (grinding wheel):</strong> lebih tebal, dipakai untuk meratakan, membentuk, dan mengasah.</li>
<li><strong>Flap disc:</strong> lembaran amplas tersusun pada piringan, untuk menggerinda sekaligus menghaluskan permukaan.</li>
</ul>
<h2>Yang perlu dicek sebelum memakai</h2>
<ul>
<li><strong>Diameter dan lubang as:</strong> harus sesuai dengan mesin dan pelindung (guard) yang terpasang.</li>
<li><strong>Kecepatan maksimum (RPM):</strong> tertera pada batu gerinda dan harus sama dengan atau lebih tinggi dari RPM mesin.</li>
<li><strong>Bahan yang dikerjakan:</strong> pilih batu yang sesuai, misalnya untuk besi, stainless steel, atau beton dan batu. Untuk stainless steel pilih jenis yang bebas besi agar tidak mengotori permukaan.</li>
<li><strong>Kondisi fisik:</strong> jangan pakai batu yang retak, gompal, atau pernah jatuh.</li>
</ul>
<p>Simpan di tempat kering dan datar, dan selalu pasang pelindung mesin saat bekerja.</p>',
                'img'   => null,
                'created_at' => now()->subDays(10),
            ],
            [
                'meta'  => 'Tips & Teknik',
                'title' => 'Mengenal Roda Poles (Buffing Wheel) dan Tahapan Memolesnya',
                'desc'  => '<p>Roda poles dipakai untuk menghaluskan dan mengkilapkan permukaan logam, misalnya stainless steel, kuningan, dan aluminium. Hasilnya bergantung pada kombinasi roda dan compound yang dipakai.</p>
<h2>Tiga tahap memoles</h2>
<ul>
<li><strong>Tahap potong (cutting):</strong> menghilangkan goresan memakai roda yang lebih keras dan compound yang lebih kasar.</li>
<li><strong>Tahap penghalusan (coloring):</strong> menghaluskan bekas tahap pertama memakai roda katun dan compound yang lebih halus.</li>
<li><strong>Tahap akhir (finishing):</strong> memberi kilap akhir memakai roda yang lembut dan compound paling halus.</li>
</ul>
<h2>Tips penting</h2>
<p>Gunakan satu roda untuk satu jenis compound agar partikel kasar tidak terbawa ke tahap halus. Tekan benda dengan ringan dan jaga agar tidak panas berlebihan karena bisa mengubah warna logam. Benda yang dipoles sebaiknya ditempatkan di bagian bawah roda, bukan di bagian atas, agar tidak terlempar.</p>
<p>Pakai kacamata pelindung dan sarung tangan yang pas, dan jangan memakai pakaian atau perhiasan yang bisa tersangkut roda yang berputar.</p>',
                'img'   => null,
                'created_at' => now()->subDays(17),
            ],
            [
                'meta'  => 'Keamanan Kerja',
                'title' => 'Perlengkapan Safety Dasar Saat Menggerinda dan Mengelas',
                'desc'  => '<p>Pekerjaan menggerinda, memotong, dan mengelas menghasilkan percikan api, serpihan logam, debu, dan suara keras. Perlengkapan pelindung diri yang tepat mengurangi risiko cedera.</p>
<h2>Perlengkapan yang dianjurkan</h2>
<ul>
<li><strong>Kacamata pelindung atau face shield:</strong> melindungi mata dari serpihan dan percikan api. Untuk menggerinda, face shield dipakai bersama kacamata.</li>
<li><strong>Sarung tangan:</strong> pilih yang sesuai pekerjaan. Sarung tangan kulit untuk pengelasan dan menggerinda.</li>
<li><strong>Masker atau respirator:</strong> mengurangi debu dan asap las yang terhirup.</li>
<li><strong>Pelindung telinga:</strong> penting untuk pekerjaan gerinda yang lama.</li>
<li><strong>Pakaian kerja dan sepatu safety:</strong> berlengan panjang dan tidak mudah terbakar, dengan sepatu yang melindungi jari kaki.</li>
</ul>
<h2>Kebiasaan aman</h2>
<p>Pastikan pelindung mesin terpasang, benda kerja terjepit kuat, dan area sekitar bebas dari bahan mudah terbakar. Periksa kondisi batu gerinda sebelum dipakai, dan matikan mesin serta tunggu sampai berhenti berputar sebelum meletakkannya.</p>',
                'img'   => null,
                'created_at' => now()->subDays(24),
            ],
        ];

        foreach ($blogs as $data) {
            Blog::firstOrCreate(['title' => $data['title']], $data);
        }
    }
}
