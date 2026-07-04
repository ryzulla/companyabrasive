<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id'    => 'grinding-wheels',
                'title' => 'Grinding Wheels',
                'desc'  => 'Batu gerinda untuk pengikisan, penghalusan, dan pembentukan logam. Tersedia dalam berbagai ukuran dan tingkat kekerasan.',
                'img'   => '',
            ],
            [
                'id'    => 'cutting-discs',
                'title' => 'Cutting Discs',
                'desc'  => 'Mata potong tipis berkecepatan tinggi untuk pemotongan baja, besi, dan logam campuran secara presisi.',
                'img'   => '',
            ],
            [
                'id'    => 'flap-discs',
                'title' => 'Flap Discs',
                'desc'  => 'Amplas rotari berlapis untuk penggerindaan dan penghalusan permukaan secara bersamaan dengan hasil akhir optimal.',
                'img'   => '',
            ],
            [
                'id'    => 'sandpaper',
                'title' => 'Amplas & Sandpaper',
                'desc'  => 'Kertas amplas dan kain abrasif untuk finishing permukaan kayu, logam, dempul, dan cat dengan berbagai grit.',
                'img'   => '',
            ],
            [
                'id'    => 'wire-brushes',
                'title' => 'Wire Brushes',
                'desc'  => 'Sikat kawat baja untuk pembersihan karat, bekas las, dan cat pada permukaan logam maupun beton.',
                'img'   => '',
            ],
            [
                'id'    => 'mounted-points',
                'title' => 'Mounted Points',
                'desc'  => 'Batu gerinda bertangkai untuk penggerindaan dalam, deburring, dan finishing rongga sempit pada mould, dies, dan komponen presisi.',
                'img'   => '',
            ],
            [
                'id'    => 'non-woven',
                'title' => 'Non-Woven Abrasives',
                'desc'  => 'Produk abrasif berbasis serat sintetis untuk finishing, deburring, dan pemolesan tanpa merusak dimensi benda kerja.',
                'img'   => '',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['id' => $cat['id']], $cat);
        }
    }
}
