# 3. Panduan Teknis (untuk Pengembang)

Bagian ini untuk yang memasang, merawat, atau mengembangkan website. Pengelola konten cukup membaca [Panduan Admin](01-panduan-admin.md).

## 3.1 Teknologi

| Bagian | Yang dipakai |
|---|---|
| Backend | Laravel 12, PHP 8.2 atau lebih baru |
| Database | MySQL / MariaDB |
| Tampilan admin | Blade + Tailwind CSS 4 (di-build dengan Vite) |
| Tampilan website | Blade + CSS biasa di `public/css/` dan JavaScript biasa di `public/js/` |
| Editor artikel | CKEditor 5 (dimuat dari CDN) |
| Kompresi gambar | GD (bawaan PHP) |

> Pada MAMP, pastikan PHP yang dipakai versi 8.2 atau lebih baru (misalnya `/Applications/MAMP/bin/php/php8.3.0/bin/php`). PHP 7.4 akan ditolak oleh Composer.

## 3.2 Pemasangan di komputer lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
# atur DB_* di .env, lalu:
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Akun admin awal dibuat oleh seeder: `admin@example.com` dengan password `password`. **Segera ganti** lewat menu *Pengaturan akun* (lihat [Panduan Admin](01-panduan-admin.md#16-akun-saya)).

## 3.3 Daftar periksa sebelum website tayang

Lakukan semuanya sebelum alamat website dibagikan.

- [ ] `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-anda.com`
- [ ] Ganti password akun admin bawaan dan ganti emailnya ke email asli
- [ ] `.env`: isi pengaturan email (`MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`). Tanpa ini, notifikasi pesan masuk dan testimoni baru tidak terkirim
- [ ] Admin, *Pengaturan*, tab *Kontak*: isi email, nomor WhatsApp, dan telepon yang asli (nomor WhatsApp berformat `628xxxxxxxxxx`)
- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] `npm run build`, lalu pastikan folder `public/build/` ikut ter-upload
- [ ] Upload ulang file E-Catalog PDF lewat *Pengaturan*, tab *CTA*, karena folder `storage/` tidak ikut git
- [ ] Isi foto hero, foto Tentang, dan gambar sampul tiap artikel dengan foto asli
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache`

## 3.4 Perintah artisan yang tersedia

| Perintah | Fungsi |
|---|---|
| `php artisan images:optimize --dry-run` | Menghitung berapa hemat jika semua foto dikecilkan, tanpa mengubah file |
| `php artisan images:optimize` | Mengecilkan foto di `storage/app/public` (maks. 1600 px, kualitas 82%). File asli disalin ke `storage/app/image-backup`. Folder `to_upload` tidak disentuh |
| `php artisan catalog:import` | **Berbahaya.** Mengganti SELURUH produk dan kategori dengan isi `database/data/catalog.php` dan foto di `storage/app/public/to_upload`. Dipakai untuk impor katalog awal. Jangan dijalankan di website yang sudah berisi data asli |

Foto yang diunggah lewat admin otomatis dikecilkan (`app/Support/ImageOptimizer.php`), jadi perintah `images:optimize` biasanya tidak perlu dijalankan lagi.

## 3.5 Peta halaman dan alamat

### Website

| Alamat | Halaman |
|---|---|
| `/` | Beranda |
| `/tentang` | Tentang Kami |
| `/products` | Katalog produk (bisa difilter: `?category=...&q=...`) |
| `/products/{slug}` | Detail produk |
| `/blog` dan `/blog/{slug}` | Daftar artikel dan artikelnya |
| `/t/{kode}` | Form testimoni privat (sekali pakai, tidak terindeks Google) |
| `/sitemap.xml` dan `/robots.txt` | Dibuat otomatis |

Satu alamat bisa menampilkan dua desain: **desktop** dan **mobile**. Pemilihnya `app/Support/Device.php` yang membaca User-Agent. Untuk menguji, tambahkan `?view=mobile` atau `?view=desktop` di akhir alamat.

### Admin

Semua di bawah `/admin` dan wajib login. Menu **Kategori**, **Klien / Mitra**, **Pengguna**, dan **Pengaturan** hanya untuk peran *Superuser*.

## 3.6 Struktur folder penting

```
app/
  Http/Controllers/            Halaman website (Home, Product, Blog, About, Contact, TestimonialForm)
  Http/Controllers/Admin/      Halaman admin
  Models/                      Product, Category, Blog, Video, Faq, Testimonial, TestimonialInvite, Client, ContactMessage, Setting, User
  Support/                     Device (deteksi mobile), ImageOptimizer, ArticleHtml (membersihkan isi artikel)
resources/views/
  home/                        Halaman website desktop dan mobile (*-mobile.blade.php)
  home/layouts/                Layout bersama (inner = desktop, mobile-page = mobile)
  admin/                       Halaman admin
  errors/                      Halaman 404, 403, 419, 429, 500, 503
  testimonial/                 Form testimoni privat
public/css/                    home-base, home-desktop, home-inner, home-products, home-product-show, home-mobile, testimonial-form
public/js/                     Skrip tiap halaman (home-desktop.js, home-mobile.js, dan lainnya)
public/build/                  Hasil build Tailwind untuk admin (hasil `npm run build`)
database/seeders/              Data contoh
```

**Cara kerja CSS dan JS website:** `home-base.css` berisi dasar bersama, lalu setiap halaman memuat file CSS miliknya sendiri. Data dari server dikirim ke JavaScript lewat objek `window.PAGE` di setiap view.

## 3.7 Pengaturan yang tersimpan di database

Hampir semua teks website (judul, deskripsi, kontak, SEO, tombol, dan sebagainya) ada di tabel `settings` dan diedit lewat menu **Pengaturan**. Mengubah teks website tidak memerlukan perubahan kode.

Menyalakan atau mematikan section beranda memakai kunci `show_*` (misalnya `show_milestone`, `show_testimonials`, `show_video`). Nilai `0` menyembunyikan section.

## 3.8 Keamanan yang sudah ada

- Form kontak: batas 5 kiriman per menit, kolom jebakan untuk bot (honeypot), dan validasi panjang.
- Form testimoni privat: kode acak sekali pakai, batas 10 kiriman per menit, honeypot, dan wajib persetujuan.
- Isi artikel dibersihkan oleh `ArticleHtml` (hanya tag yang aman yang dipertahankan).
- Menu master data dibatasi untuk Superuser lewat middleware `EnsureSuperuser`.

## 3.9 Tambahan kelas Tailwind di halaman admin

Admin memakai Tailwind yang dikompilasi. Jika Anda menambah kelas Tailwind baru di view admin, jalankan `npm run build` agar kelas itu ikut masuk ke `public/build/`. Tanpa itu, elemen baru akan tampil tanpa warna atau ukuran.
