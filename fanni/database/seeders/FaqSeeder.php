<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Apakah tersedia layanan pembelian dalam jumlah besar (bulk order)?',
                'answer'   => 'Ya, kami melayani pembelian partai besar dengan harga khusus. Hubungi tim penjualan kami melalui WhatsApp atau email untuk mendapatkan penawaran harga (RFQ) sesuai volume kebutuhan Anda. Minimum order untuk harga grosir dimulai dari 1 karton (isi tergantung jenis produk).',
            ],
            [
                'question' => 'Berapa lama waktu pengiriman ke luar Jawa?',
                'answer'   => 'Untuk area Jawa, Bali, dan Lombok pengiriman memakan waktu 2–3 hari kerja. Wilayah Sumatera, Kalimantan, dan Sulawesi 3–5 hari kerja. Papua dan Maluku 5–8 hari kerja. Estimasi ini berlaku untuk pengiriman reguler via ekspedisi mitra kami (JNE Trucking, SiCepat Cargo, atau Wahana).',
            ],
            [
                'question' => 'Apakah produk yang dijual sudah berstandar internasional?',
                'answer'   => 'Seluruh produk yang kami distribusikan telah memenuhi standar internasional EN 12413 (untuk bonded abrasives) dan EN 13743 (untuk coated abrasives), serta tersertifikasi ISO 9001:2015 dari produsen. Produk tertentu juga memiliki sertifikasi MPA (Mechanical Products Assurance) dari Jerman.',
            ],
            [
                'question' => 'Bisakah saya meminta sampel produk sebelum order besar?',
                'answer'   => 'Kami menyediakan program sampel produk untuk calon pembeli korporat dengan order potensial di atas 500 pcs. Pengiriman sampel dikenakan biaya ongkir, namun biaya tersebut akan dikreditkan ke invoice pertama Anda apabila jadi melakukan pembelian.',
            ],
            [
                'question' => 'Apakah bisa pesan dengan spesifikasi khusus (custom)?',
                'answer'   => 'Ya, kami menerima pesanan custom untuk ukuran diameter, ketebalan, grit, dan komposisi abrasif yang tidak tersedia di katalog standar. Minimum order untuk produksi custom umumnya 500 pcs dan memerlukan waktu lead time 21–30 hari kerja tergantung spesifikasi.',
            ],
            [
                'question' => 'Metode pembayaran apa saja yang diterima?',
                'answer'   => 'Kami menerima pembayaran melalui transfer bank (BCA, Mandiri, BNI, BRI), virtual account, dan untuk pelanggan korporat kami juga menyediakan fasilitas termin pembayaran NET-30 setelah melewati proses verifikasi kredit.',
            ],
            [
                'question' => 'Bagaimana cara klaim garansi jika produk cacat atau tidak sesuai?',
                'answer'   => 'Klaim garansi dapat diajukan dalam 7 hari kerja setelah barang diterima. Syaratnya: foto atau video bukti kerusakan/ketidaksesuaian, nomor invoice, dan kondisi produk belum digunakan. Kami akan proses penggantian atau kredit nota dalam 5 hari kerja setelah klaim disetujui.',
            ],
            [
                'question' => 'Apakah tersedia konsultasi teknis untuk memilih produk yang tepat?',
                'answer'   => 'Tentu. Tim technical support kami siap membantu Anda menentukan jenis abrasif, grit, dan bond yang paling efisien untuk material dan proses kerja Anda. Konsultasi dapat dilakukan via WhatsApp, telepon, atau kunjungan langsung ke workshop/pabrik Anda untuk klien korporat.',
            ],
        ];

        foreach ($faqs as $data) {
            Faq::firstOrCreate(['question' => $data['question']], $data);
        }
    }
}
