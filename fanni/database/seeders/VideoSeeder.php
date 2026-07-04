<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            [
                'id'    => 'dQw4w9WgXcQ',
                'title' => 'Proses Produksi Grinding Wheel Berkualitas Tinggi',
                'desc'  => 'Lihat bagaimana grinding wheel kami diproduksi dengan teknologi terkini dan pengawasan kualitas ketat di setiap tahap produksi.',
            ],
            [
                'id'    => 'jNQXAC9IVRw',
                'title' => 'Demo Penggunaan Cutting Disc untuk Pemotongan Baja',
                'desc'  => 'Demonstrasi langsung penggunaan cutting disc premium kami pada berbagai jenis baja — baja karbon, stainless steel, dan besi cor.',
            ],
            [
                'id'    => 'M7lc1UVf-VE',
                'title' => 'Teknik Finishing dengan Flap Disc: Dari Kasar ke Halus',
                'desc'  => 'Tutorial lengkap penggunaan flap disc untuk mendapatkan hasil finishing permukaan logam yang sempurna di berbagai aplikasi industri.',
            ],
            [
                'id'    => 'ZZ5LpwO-An4',
                'title' => 'Panduan Keselamatan Kerja Menggunakan Produk Abrasif',
                'desc'  => 'Video edukasi tentang standar keselamatan kerja yang wajib diterapkan saat menggunakan grinding wheel, cutting disc, dan produk abrasif lainnya.',
            ],
            [
                'id'    => 'oHg5SJYRHA0',
                'title' => 'Kunjungan Pabrik: Fasilitas Produksi & Quality Control',
                'desc'  => 'Tur virtual fasilitas produksi kami yang modern, dilengkapi mesin-mesin canggih dan laboratorium pengujian kualitas berstandar internasional.',
            ],
        ];

        foreach ($videos as $data) {
            Video::firstOrCreate(['id' => $data['id']], $data);
        }
    }
}
