<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'name'    => 'Budi Santoso',
                'email'   => 'budi.santoso@krakatuasteel.co.id',
                'phone'   => '0811-2233-4455',
                'message' => 'Selamat siang, kami dari divisi pemeliharaan PT Krakatau Steel ingin menanyakan ketersediaan grinding wheel diameter 230mm untuk pemotongan baja. Apakah tersedia dalam jumlah besar? Mohon info harga dan minimum order. Terima kasih.',
                'created_at' => now()->subDays(1),
            ],
            [
                'name'    => 'Siti Rahayu',
                'email'   => 'siti.rahayu@wika.co.id',
                'phone'   => '0812-3344-5566',
                'message' => 'Halo, kami membutuhkan cutting disc untuk proyek konstruksi jembatan di Kalimantan. Mohon info katalog lengkap beserta harga untuk pembelian 500 pcs. Apakah ada diskon khusus untuk volume besar?',
                'created_at' => now()->subDays(2),
            ],
            [
                'name'    => 'Agus Prabowo',
                'email'   => 'agus.p@ptpal.co.id',
                'phone'   => '0813-5566-7788',
                'message' => 'Kami dari PT PAL Indonesia ingin mengetahui lebih lanjut mengenai produk flap disc untuk pengerjaan material baja kapal. Apakah produk Anda sudah tersertifikasi ISO? Kami juga butuh sertifikat produk untuk keperluan tender.',
                'created_at' => now()->subDays(3),
            ],
            [
                'name'    => 'Dewi Kurniawati',
                'email'   => 'dewi.k@astramoto.co.id',
                'phone'   => '0857-1122-3344',
                'message' => 'Permisi, saya ingin memesan amplas gulung untuk pengerjaan body kendaraan. Ukuran yang dibutuhkan grit 120, 240, dan 400. Bisa minta sample terlebih dahulu sebelum order dalam jumlah besar?',
                'created_at' => now()->subDays(4),
            ],
            [
                'name'    => 'Riko Firmansyah',
                'email'   => 'riko@manufaktur-jaya.com',
                'phone'   => '0822-9988-7766',
                'message' => 'Selamat pagi. Perusahaan kami bergerak di bidang fabrikasi logam dan sangat membutuhkan wire brush berkualitas untuk proses pembersihan permukaan. Apakah ada produk khusus untuk stainless steel? Mohon kirimkan penawaran ke email kami.',
                'created_at' => now()->subDays(5),
            ],
            [
                'name'    => 'Hendra Wijaya',
                'email'   => 'hendra.w@pertamina.com',
                'phone'   => '0815-4433-2211',
                'message' => 'Dear Tim Sales, kami dari unit pemeliharaan kilang Pertamina Cilacap memerlukan mounted points untuk pengerjaan presisi pada komponen pompa. Mohon info spesifikasi lengkap dan lead time pengiriman untuk wilayah Jawa Tengah.',
                'created_at' => now()->subDays(6),
            ],
            [
                'name'    => 'Maya Indriati',
                'email'   => 'maya.indriati@freeport.co.id',
                'phone'   => '0856-7788-9900',
                'message' => 'Hi, saya tertarik dengan produk non-woven abrasive untuk finishing permukaan tembaga. Apakah tersedia dalam ukuran roll? Kami butuh dalam jumlah besar dan konsisten setiap bulannya. Mohon hubungi kami untuk pembahasan lebih lanjut.',
                'created_at' => now()->subDays(7),
            ],
            [
                'name'    => 'Andi Prasetya',
                'email'   => 'andi.p@bengkelbaja.id',
                'phone'   => '0819-6655-4433',
                'message' => 'Siang, saya pemilik bengkel fabrikasi baja di Surabaya. Ingin berlangganan produk grinding wheel dan cutting disc secara rutin. Apakah ada program reseller atau distributor untuk wilayah Jawa Timur? Terima kasih.',
                'created_at' => now()->subDays(10),
            ],
            [
                'name'    => 'Fitriani Halim',
                'email'   => 'fitriani@holcim.co.id',
                'phone'   => '0821-3344-5566',
                'message' => 'Halo, kami dari departemen engineering PT Holcim Indonesia membutuhkan rekomendasi produk abrasif untuk pengerjaan pada material beton keras. Apakah ada konsultasi teknis gratis? Kami perlu bantuan dalam memilih spesifikasi yang tepat.',
                'created_at' => now()->subDays(12),
            ],
            [
                'name'    => 'Yusuf Bahari',
                'email'   => 'yusuf.b@pupukkaltim.com',
                'phone'   => '0812-7788-9900',
                'message' => 'Selamat sore. Kami dari PT Pupuk Kaltim Bontang mengalami keausan cepat pada grinding wheel yang kami gunakan saat ini. Apakah Anda memiliki produk yang lebih tahan lama untuk material aluminium oksida? Mohon rekomendasi teknisnya.',
                'created_at' => now()->subDays(15),
            ],
        ];

        foreach ($messages as $data) {
            ContactMessage::create($data);
        }
    }
}
