<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Identitas Perusahaan
            'company_name'        => 'Aneka Abrasive Andalan',
            'company_full_name'   => 'PT Aneka Abrasive Andalan',
            'company_tagline'     => 'Premium Industrial Abrasives',
            'company_logo_icon'   => 'fa-gear',

            // Hero
            'hero_title'          => 'Material Abrasive <span>Tangguh</span> Untuk Performa Industri Maksimal',
            'hero_subtitle'       => 'Distributor utama alat potong, poles, dan pengasahan standar internasional. Kami menghadirkan efisiensi tinggi untuk lini produksi Anda.',
            'hero_btn_primary'    => 'Jelajahi Produk',
            'hero_btn_secondary'  => 'Minta Penawaran',

            // Tentang Perusahaan
            'about_title'         => 'Lebih Dari 15 Tahun Menjadi Solusi Abrasive Terpercaya',
            'about_desc'          => '<strong>PT Aneka Abrasive Andalan</strong> adalah penyedia perkakas abrasive premium yang melayani sektor manufaktur otomotif, fabrikasi logam, industri kayu, hingga konstruksi skala besar nasional.',
            'about_feature_1'     => 'Produk Berstandar ISO',
            'about_feature_2'     => 'Distribusi Seluruh Indonesia',
            'about_feature_3'     => 'Custom Grid & Ukuran',
            'about_feature_4'     => 'Dukungan Teknis Ahli',
            'about_cert_1'        => 'ISO 9001:2015',
            'about_cert_1_icon'   => 'fa-certificate',
            'about_cert_2'        => 'EN 12413',
            'about_cert_2_icon'   => 'fa-shield-halved',
            'about_cert_3'        => 'SNI',
            'about_cert_3_icon'   => 'fa-check-double',
            'about_img'           => '',

            // Milestone/Statistik
            'milestone_years'     => '15',
            'milestone_clients'   => '500',
            'milestone_products'  => '85',
            'milestone_delivery'  => '99',

            // Teks Section
            'categories_title'    => 'Kategori Produk Utama',
            'categories_desc'     => 'Kami membagi produk berdasarkan teknologi manufaktur untuk mempermudah pencarian spesifikasi industri Anda.',
            'products_title'      => 'Katalog Produk Unggulan',
            'products_desc'       => 'Daftar perkakas yang paling banyak digunakan oleh klien korporasi kami dengan jaminan stok selalu tersedia.',
            'video_title'         => 'Video Spesifikasi & Performa',
            'video_desc'          => 'Saksikan langsung pengujian daya tahan dan spesifikasi teknis dari produk kami melalui kompilasi video berikut.',
            'clients_title'       => 'Mitra & Klien Korporat Kami',
            'clients_desc'        => 'Dipercaya oleh ratusan perusahaan manufaktur, fabrikasi, dan konstruksi di seluruh Indonesia.',
            'testimonials_title'  => 'Apa Kata Mereka?',
            'blog_title'          => 'Wawasan Industri',
            'blog_desc'           => 'Artikel dan panduan teknis terbaru untuk meningkatkan produktivitas bengkel dan pabrik Anda.',
            'faq_title'           => 'Pertanyaan Umum (FAQ)',
            'faq_desc'            => 'Temukan jawaban cepat seputar pemesanan, pengiriman, dan layanan purna jual kami.',

            // CTA Banner
            'cta_title'           => 'Butuh Spesifikasi Teknis Lengkap?',
            'cta_desc'            => 'Unduh E-Catalog terbaru kami yang berisi ratusan produk beserta parameter teknis dan panduan keselamatan kerja.',
            'ecatalog_file'       => '',

            // Kontak
            'contact_title'       => 'Hubungi Tim Penjualan',
            'contact_desc'        => 'Konsultasikan kebutuhan teknis Anda atau minta penawaran harga (RFQ) formal khusus untuk suplai B2B.',
            'address'             => "Gedung Lindeteves Trade Center (LTC) Lantai GF 2 Blok B3 No.3\nJl. Hayam Wuruk No.127, Jakarta Barat\nJakarta 11180",
            'phone'               => '021 - 6231 1087',
            'email'               => 'sales@example.com',
            'wa_number'           => '6281234567890',

            // Footer
            'footer_text'         => '© 2026 PT Aneka Abrasive Andalan. Seluruh Hak Cipta Dilindungi.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
