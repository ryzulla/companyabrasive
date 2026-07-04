<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'text'   => 'Produk grinding wheel dari PT Aneka Abrasive Andalan sangat konsisten kualitasnya. Kami sudah berlangganan lebih dari 3 tahun dan belum pernah mengalami masalah. Pengiriman selalu tepat waktu dan tim teknisnya sangat membantu dalam pemilihan spesifikasi.',
                'author' => 'Budi Harjanto',
                'pos'    => 'Kepala Produksi, PT Krakatau Steel',
            ],
            [
                'text'   => 'Cutting disc yang kami gunakan dari vendor sebelumnya sering cepat habis. Setelah beralih ke produk AAA, ketahanannya meningkat hampir 40%. Ini sangat berdampak positif pada efisiensi dan biaya produksi kami.',
                'author' => 'Ir. Santika Wulandari',
                'pos'    => 'Maintenance Manager, PT PAL Indonesia',
            ],
            [
                'text'   => 'Kami sangat puas dengan flap disc untuk pengerjaan stainless steel di fasilitas kilang kami. Hasil permukaannya halus dan tidak ada kontaminasi. Responsif dalam melayani order mendadak adalah nilai plus yang sangat kami hargai.',
                'author' => 'Rachmat Hidayat',
                'pos'    => 'Supervisor Pemeliharaan, PT Pertamina RU IV Cilacap',
            ],
            [
                'text'   => 'Tim sales dan teknis AAA sangat profesional. Mereka datang langsung ke fasilitas kami untuk melakukan audit kebutuhan abrasif dan memberikan rekomendasi yang tepat sasaran. Biaya kami turun 25% dengan spesifikasi yang lebih sesuai.',
                'author' => 'Dody Firmansyah, S.T.',
                'pos'    => 'Engineering Specialist, PT Toyota Motor Manufacturing Indonesia',
            ],
            [
                'text'   => 'Untuk proyek konstruksi skala besar, kami membutuhkan cutting disc yang bisa diandalkan dalam kondisi lapangan yang keras. Produk dari AAA tidak pernah mengecewakan. Kualitas stabil dari batch ke batch, sangat penting untuk proyek jangka panjang kami.',
                'author' => 'Agung Setiawan',
                'pos'    => 'Project Manager, PT Wijaya Karya',
            ],
            [
                'text'   => 'Sebagai bengkel fabrikasi baja yang melayani industri otomotif, kami sangat bergantung pada kualitas abrasif. AAA memberikan produk yang konsisten dengan harga kompetitif. Layanan after-sales mereka juga sangat responsif ketika ada pertanyaan teknis.',
                'author' => 'Hendra Kusuma',
                'pos'    => 'Owner, CV Baja Teknik Nusantara',
            ],
            [
                'text'   => 'Wire brush dan mounted points dari AAA kami gunakan untuk pekerjaan presisi pada komponen mesin. Kualitasnya jauh di atas produk impor yang harganya lebih mahal. Bangga bisa menggunakan produk abrasif dalam negeri yang berkualitas internasional.',
                'author' => 'Maya Pratiwi',
                'pos'    => 'QC Engineer, PT Pindad',
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::firstOrCreate(['author' => $data['author']], $data);
        }
    }
}
