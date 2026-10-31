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
                'id'    => 'eK_ump70x8w',
                'title' => 'Grit Amplas: Apa Bedanya dan Cara Memilih yang Tepat',
                'desc'  => 'Penjelasan arti angka grit pada amplas dan cara memilihnya sesuai pekerjaan (video dari Saindo).',
            ],
            [
                'id'    => '6Z25BiVItMI',
                'title' => 'Cara Memilih Amplas untuk Kayu, Besi, dan Tembok',
                'desc'  => 'Panduan memilih jenis amplas yang tepat untuk tiap bahan (video dari Terrace Interior).',
            ],
            [
                'id'    => 'Kf6K1MueXL4',
                'title' => 'Mengenal Jenis Mata Gerinda Tangan',
                'desc'  => 'Pengenalan berbagai mata gerinda tangan dan fungsinya masing-masing (video dari Tukang Jadi jadian).',
            ],
            [
                'id'    => 'OSqf5cuBiHk',
                'title' => 'Tips Aman dan Teknik Memakai Gerinda Tangan',
                'desc'  => 'Tips keselamatan dan teknik dasar memakai gerinda tangan untuk pemula (video dari Dhi Dhut).',
            ],
            [
                'id'    => 'RjbTyTTRLzc',
                'title' => 'Metal Polishing Compounds & Buffing Wheels Explained',
                'desc'  => 'Penjelasan compound poles dan roda poles untuk logam, berbahasa Inggris (video dari Maverick Abrasives).',
            ],
        ];

        foreach ($videos as $data) {
            Video::updateOrCreate(['id' => $data['id']], $data);
        }
    }
}
