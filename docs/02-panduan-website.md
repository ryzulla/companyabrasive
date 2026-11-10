# 2. Panduan Website (Tampilan untuk Pengunjung)

Bagian ini menjelaskan isi website yang dilihat pengunjung, dan **dari menu admin mana tiap bagian diatur**. Website punya dua desain yang dipilih otomatis: **desktop** (komputer dan tablet) dan **mobile** (ponsel).

**Daftar isi**

1. [Beranda (desktop)](#21-beranda-desktop)
2. [Katalog produk](#22-katalog-produk)
3. [Detail produk](#23-detail-produk)
4. [Blog](#24-blog)
5. [Tentang Kami](#25-tentang-kami)
6. [Formulir testimoni (link privat)](#26-formulir-testimoni-link-privat)
7. [Halaman tidak ditemukan](#27-halaman-tidak-ditemukan)
8. [Tampilan mobile](#28-tampilan-mobile)

---

## 2.1 Beranda (desktop)

Beranda terdiri dari beberapa bagian dari atas ke bawah. Setiap bagian bisa dimatikan di *Pengaturan, Judul Seksi*, dan bagian yang belum ada datanya otomatis tersembunyi.

### Hero (bagian paling atas)

Judul besar, deskripsi, dan dua tombol. Tombol WhatsApp melayang di pojok kanan bawah.

![Hero beranda](images/web-beranda-hero.jpg)

| Bagian | Diatur di |
|---|---|
| Gambar latar, judul, deskripsi, teks tombol | Admin, *Pengaturan, Hero* |
| Logo dan nama perusahaan | Admin, *Pengaturan, Identitas* |
| Tombol WhatsApp melayang | Admin, *Pengaturan, Kontak* (nomor WhatsApp) |

### Kategori Produk

Kartu kategori. Mengklik kartu langsung menyaring katalog di bawahnya.

![Kategori produk](images/web-beranda-kategori.jpg)

Diatur di: Admin, **Kategori**.

### Katalog Produk

Tombol kategori di atas menyaring produk. Produk diambil dari server saat kategori diklik, sehingga semua kategori selalu menampilkan produknya. Tersedia dua tampilan: **kotak (thumbnail)** dan **daftar**. Pilihan tampilan diingat browser. Tombol *Lihat Semua Produk* membuka katalog lengkap.

![Katalog produk di beranda](images/web-beranda-katalog.jpg)

Diatur di: Admin, **Produk**, dan judul seksinya di *Pengaturan, Judul Seksi*.

### Video, E-Catalog, Klien, Testimoni, FAQ, dan Blog

- **Video Produk**: thumbnail YouTube yang diputar dalam jendela di halaman. Diatur di *Video*.
- **Banner E-Catalog**: tombol *Download E-Catalog (PDF)* dan *Minta via WhatsApp*. Diatur di *Pengaturan, CTA*.
- **Klien Kami**: deretan logo yang bergerak. Diatur di *Klien / Mitra*.

![Klien kami](images/web-beranda-klien.jpg)

- **Testimoni**: kartu ulasan klien. Diatur di *Testimoni*.
- **FAQ**: pertanyaan yang bisa dibuka-tutup. Diatur di *FAQ*.

![FAQ](images/web-beranda-faq.jpg)

- **Blog**: 4 artikel terbaru dan tombol **Lihat Semua Artikel** ke halaman `/blog`. Diatur di *Blog*.

### Kontak

Informasi alamat, telepon, email, jam operasional, peta, dan **formulir Hubungi Kami**.

![Bagian kontak](images/web-beranda-kontak.jpg)

Formulir berisi nama, email, telepon, **keperluan** (Permintaan Penawaran, Tanya Produk, Kerja Sama / Distributor, atau Lainnya), dan pesan. Pesan yang dikirim masuk ke Admin, menu **Pesan Masuk**. Formulir dilindungi dari spam (batas 5 kiriman per menit dan kolom jebakan bot).

Informasi kontak diatur di *Pengaturan, Kontak* dan *Pengaturan, Lokasi & Sosial*.

---

## 2.2 Katalog produk

Alamat: `/products`. Pengunjung dapat **mencari** produk, **menyaring** per kategori, dan membuka detail produk. Hasil dibagi per halaman (12 produk per halaman).

![Halaman katalog produk](images/web-produk-daftar.jpg)

Diatur di: Admin, **Produk** dan **Kategori**.

---

## 2.3 Detail produk

Setiap produk punya halaman sendiri dengan alamat dari judulnya, misalnya `/products/nama-produk`. Isinya:

- **Galeri foto** dengan tombol panah dan gambar kecil. Klik foto untuk **memperbesar (zoom)**, lalu geser atau cubit untuk memperbesar.
- Merek, kategori, judul, dan deskripsi.
- **Spesifikasi**: label dan nilai utama, kecepatan maksimum (jika ada), dan material atau aplikasi.
- Tombol **Pesan via WhatsApp** (nama produk sudah terisi di pesan) dan **Minta Penawaran** (membuka formulir kontak dengan pesan yang sudah terisi nama produk).
- **Produk Serupa** dari kategori yang sama.

![Halaman detail produk](images/web-produk-detail.jpg)

Diatur di: Admin, **Produk**.

---

## 2.4 Blog

### Daftar artikel

Alamat: `/blog`. Menampilkan semua artikel, 9 per halaman.

![Daftar artikel](images/web-blog-daftar.jpg)

### Halaman artikel

Setiap artikel punya alamat sendiri (`/blog/judul-artikel`) sehingga bisa dibagikan dan ditemukan lewat Google. Di bawah artikel ada tombol bagikan (WhatsApp, Facebook, salin link) dan **Artikel Lainnya**.

![Halaman artikel](images/web-blog-artikel.jpg)

Diatur di: Admin, **Blog**.

---

## 2.5 Tentang Kami

Alamat: `/tentang`. Berisi profil perusahaan, keunggulan, **visi dan misi**, sertifikasi (jika diisi), statistik (jika dinyalakan), dan ajakan menghubungi.

![Halaman Tentang Kami](images/web-tentang.jpg)

Diatur di: Admin, *Pengaturan, Tentang* dan *Pengaturan, Statistik*.

---

## 2.6 Formulir testimoni (link privat)

Halaman ini **tidak ada di menu website**. Klien hanya bisa membukanya lewat link khusus (`/t/KODE`) yang Anda buat di Admin. Lihat langkah lengkapnya di [Panduan Admin, bagian Testimoni](01-panduan-admin.md#18-testimoni-dan-link-testimoni-untuk-klien).

![Formulir testimoni (desktop)](images/web-testimoni-form.jpg)

Halaman ini otomatis menyesuaikan layar ponsel dan menampilkan pesan yang jelas jika link sudah dipakai, kedaluwarsa, atau tidak valid.

---

## 2.7 Halaman tidak ditemukan

Alamat yang salah menampilkan halaman 404 yang rapi, lengkap dengan tombol kembali ke beranda, katalog produk, dan WhatsApp. Halaman serupa juga tersedia untuk kesalahan lain (akses ditolak, sesi habis, terlalu banyak permintaan, gangguan server, dan pemeliharaan).

![Halaman 404](images/web-404.jpg)

---

## 2.8 Tampilan mobile

Di ponsel, website tampil seperti aplikasi: **bilah menu bawah** (Beranda, Produk, Tentang, Kontak), kartu produk dua kolom, dan tombol aksi yang mudah dijangkau. Untuk mencoba dari komputer, tambahkan `?view=mobile` di akhir alamat.

### Beranda mobile

Beranda dibagi menjadi layar yang berpindah lewat bilah menu bawah, tanpa memuat ulang halaman.

<table>
<tr>
<td align="center"><img src="images/mobile-beranda.jpg" alt="Beranda mobile" width="230"><br><sub>Beranda</sub></td>
<td align="center"><img src="images/mobile-beranda-produk.jpg" alt="Layar produk mobile" width="230"><br><sub>Produk (cari dan filter)</sub></td>
<td align="center"><img src="images/mobile-beranda-tentang.jpg" alt="Layar tentang mobile" width="230"><br><sub>Tentang</sub></td>
<td align="center"><img src="images/mobile-beranda-kontak.jpg" alt="Layar kontak mobile" width="230"><br><sub>Kontak</sub></td>
</tr>
</table>

### Halaman lain di mobile

<table>
<tr>
<td align="center"><img src="images/mobile-produk-daftar.jpg" alt="Daftar produk mobile" width="230"><br><sub>Daftar produk</sub></td>
<td align="center"><img src="images/mobile-produk-detail.jpg" alt="Detail produk mobile" width="230"><br><sub>Detail produk</sub></td>
<td align="center"><img src="images/mobile-blog.jpg" alt="Daftar artikel mobile" width="230"><br><sub>Daftar artikel</sub></td>
<td align="center"><img src="images/mobile-artikel.jpg" alt="Artikel mobile" width="230"><br><sub>Artikel</sub></td>
</tr>
</table>

Yang khas di mobile:

- **Detail produk**: foto digeser ke samping, dan di bagian bawah ada tombol **Minta Penawaran** serta tombol WhatsApp yang selalu terlihat.
- **Daftar produk**: kotak cari di atas, tombol kategori yang digeser ke samping, dan tombol *Sebelumnya / Berikutnya* di bawah.
- Tombol telepon dan WhatsApp ada di pojok kanan atas semua halaman.
