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
            'company_name'        => 'CV. Aneka Abrasive Andalan',
            'company_full_name'   => 'CV. ANEKA ABRASIVE ANDALAN',
            'company_tagline'     => 'General Trading & Supplier',
            'company_logo_icon'   => 'fa-gear',

            // Hero
            'hero_title'          => 'Material Abrasive <span>Tangguh</span> Untuk Performa Industri Maksimal',
            'hero_subtitle'       => 'Specialist abrasive sejak 2010: amplas, batu gerinda, roda poles, dan perlengkapan industri lainnya dengan penawaran harga dan kualitas terbaik.',
            'hero_btn_primary'    => 'Jelajahi Produk',
            'hero_btn_secondary'  => 'Minta Penawaran',

            // Tentang Perusahaan
            'about_title'         => 'Specialist Abrasive Sejak 2010',
            'about_desc'          => '<p><strong>CV. Aneka Abrasive Andalan</strong> berdiri sejak Februari 2010 dan bergerak di bidang perdagangan umum dan penyediaan barang, khususnya abrasive yang meliputi amplas, batu gerinda, dan lainnya.</p><p>Kami didukung oleh beberapa pabrik dan importir terkemuka, sehingga kami dapat memberikan penawaran harga dan kualitas barang yang terbaik.</p>',
            'about_feature_1'     => 'Berdiri Sejak Februari 2010',
            'about_feature_2'     => 'Didukung Pabrik & Importir Terkemuka',
            'about_feature_3'     => 'Amplas, Batu Gerinda & Roda Poles',
            'about_feature_4'     => 'Safety, Las, Power Tools & Elektrikal',
            'about_cert_1'        => '',
            'about_cert_1_icon'   => 'fa-certificate',
            'about_cert_2'        => '',
            'about_cert_2_icon'   => 'fa-shield-halved',
            'about_cert_3'        => '',
            'about_cert_3_icon'   => 'fa-check-double',
            'about_img'           => '',
            'about_vision' => "Menjalin hubungan kerjasama yang saling menguntungkan antara konsumen dan penyedia barang.\nMenjadi perusahaan yang terdepan dalam pengadaan barang dari segi harga, kualitas, waktu, dan fleksibilitas.",
            'about_mission' => 'Turut membantu konsumen dalam hal penurunan biaya produksi dan konsumsi.',
            'show_milestone' => '0',

            // Milestone/Statistik
            'milestone_years'     => '16',
            'milestone_clients'   => '500',
            'milestone_products'  => '85',
            'milestone_delivery'  => '99',

            // Teks Section
            'categories_title'    => 'Kategori Produk Utama',
            'categories_desc'     => 'Kami membagi produk berdasarkan teknologi manufaktur untuk mempermudah pencarian spesifikasi industri Anda.',
            'products_title'      => 'Katalog Produk Unggulan',
            'products_desc'       => 'Amplas, batu gerinda, roda poles, dan perlengkapan industri lainnya.',
            'video_title'         => 'Video Produk',
            'video_desc'          => 'Video seputar produk abrasive dan penggunaannya.',
            'clients_title'       => 'Klien Kami',
            'clients_desc'        => 'Perusahaan yang sudah bekerja sama dengan kami, dan masih banyak lagi yang tidak bisa kami sebutkan satu persatu.',
            'testimonials_title'  => 'Apa Kata Mereka?',
            'blog_title'          => 'Wawasan Industri',
            'blog_desc'           => 'Panduan memilih dan menggunakan amplas, batu gerinda, dan produk abrasive lainnya.',
            'faq_title'           => 'Pertanyaan Umum (FAQ)',
            'faq_desc'            => 'Temukan jawaban cepat seputar pemesanan, pengiriman, dan layanan purna jual kami.',

            // CTA Banner
            'cta_title'           => 'Butuh Spesifikasi Teknis Lengkap?',
            'cta_desc'            => '<p>Unduh E-Catalog kami, atau hubungi kami untuk info stok dan harga barang yang Anda cari.</p>',
            'ecatalog_file'       => '',

            // Kontak
            'contact_title'       => 'Hubungi Tim Penjualan',
            'contact_desc'        => '<p>Jika ada barang yang ingin dicarikan, info stok dan harga barang, jangan sungkan untuk langsung menghubungi kami.</p>',
            'address'             => "Gedung Lindeteves Trade Center (LTC) Lantai GF 2 Blok B3 No.3\nJl. Hayam Wuruk No.127, Jakarta Barat\nJakarta 11180",
            'phone'               => '021 - 6231 1087',
            'email'               => 'sales@example.com',
            'wa_number'           => '6281234567890',

            // Jam operasional & lokasi
            'business_hours'      => 'Senin – Jumat, 08.00 – 17.00 WIB',
            'address_city'        => 'Jakarta Barat',
            'address_postal'      => '11180',
            'maps_embed'          => '',
            'maps_lat'            => '',
            'maps_lng'            => '',

            // Sosial media
            'social_facebook'     => '',
            'social_instagram'    => '',
            'social_linkedin'     => '',
            'social_youtube'      => '',
            'social_tiktok'       => '',
            'social_twitter'      => '',

            // SEO
            'seo_title'           => 'Supplier Abrasive Industri | CV. Aneka Abrasive Andalan',
            'seo_description'     => 'Specialist abrasive sejak 2010: amplas, batu gerinda, roda poles, serta perlengkapan safety, las, power tools, dan elektrikal. Lindeteves Trade Center, Jakarta Barat.',
            'seo_keywords'        => 'abrasive industri, grinding wheel, cutting disc, flap disc, distributor abrasive, alat poles, mata gerinda',
            'seo_og_image'        => '',
            'favicon'             => '',
            'seo_noindex'         => '0',
            'google_site_verification' => '',
            'bing_site_verification'   => '',
            'google_analytics_id' => '',

            // Footer
            'footer_text'         => '© 2026 CV. Aneka Abrasive Andalan. Seluruh Hak Cipta Dilindungi.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
