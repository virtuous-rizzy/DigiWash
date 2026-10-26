# DigiWash

**DigiWash** adalah aplikasi web layanan laundry digital. Pelanggan bisa memesan laundry, membayar, dan memantau status cucian dari rumah. Admin mengelola pesanan dan pembayaran, sedangkan kurir mengurus penjemputan dan pengantaran.

Dibangun dengan **CodeIgniter 4** (PHP) dan **MySQL/MariaDB**.

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.7-EF4223?logo=codeigniter&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

## Fitur

### Pelanggan
- Daftar akun dan masuk
- Pilih layanan laundry dan buat pesanan
- Lihat ringkasan pesanan sebelum dikonfirmasi
- Pilih metode pembayaran dan unggah bukti transfer
- Pantau status pesanan (tracking), riwayat, dan notifikasi
- Batalkan pesanan
- Kelola profil, foto, kata sandi, dan beberapa alamat (ada alamat utama)
- Kirim kritik dan saran

### Admin
- Dashboard ringkasan
- Kelola pesanan dan ubah status laundry
- Verifikasi atau tolak pembayaran (transaksi)
- Kelola akun pelanggan dan kurir
- Kelola layanan (tambah, aktif/nonaktif, hapus)
- Kelola kritik dan saran

### Kurir
- Dashboard tugas
- Lihat daftar dan detail tugas, lalu perbarui status antar
- Riwayat pengantaran
- Kelola akun, foto, dan kata sandi

---

## Teknologi

| Bagian | Yang dipakai |
|---|---|
| Bahasa | PHP 8.2 atau lebih baru |
| Framework | CodeIgniter 4.7 |
| Database | MySQL / MariaDB |
| Tampilan | Bootstrap 5, Bootstrap Icons, font Poppins & Roboto |
| Pengujian | PHPUnit 10 |

---

## Alur Status

**Status pesanan:**
`menunggu` → `dikonfirmasi` → `dijemput` → `dicuci` → `disetrika` → `diantar` → `selesai`
(atau `dibatalkan`)

**Status pembayaran:** `belum_dibayar` → `menunggu_verifikasi` → `lunas` (atau `gagal`)

**Status antar:** `menunggu_kurir` → `proses_antar` → `terkirim`

**Layanan bawaan:** Laundry Reguler, Laundry Express, Laundry Satuan, dan Laundry Bed Cover.

**Metode pembayaran:** transfer ke Bank BCA, BRI, BNI, Mandiri, atau e-wallet DANA, GoPay, ShopeePay.

---

## Struktur Folder

```
DigiWash/
├── app/
│   ├── Config/          # Pengaturan aplikasi, rute, database
│   ├── Controllers/     # Admin/, Kurir/, Pelanggan/, Auth, Layanan, dll.
│   ├── Models/          # Model tabel database
│   ├── Views/           # Tampilan (admin, kurir, pelanggan, pesanan)
│   ├── Filters/         # RoleFilter (pembatas akses per peran)
│   ├── Helpers/         # Fungsi bantu (foto)
│   └── Database/        # File SQL dan migrasi
├── public/              # Folder yang dibuka web server (index.php, css, gambar, unggahan)
├── writable/            # Cache, log, sesi
├── tests/               # Pengujian
└── composer.json
```

---

## Cara Menjalankan

### Yang perlu disiapkan
- PHP 8.2+ (ekstensi `intl`, `mbstring`, `mysqlnd`, `curl`)
- [Composer](https://getcomposer.org/)
- MySQL atau MariaDB (bisa lewat XAMPP / Laragon)

### Langkah-langkah

**1. Unduh proyek**
```bash
git clone https://github.com/<username>/DigiWash.git
cd DigiWash
```

**2. Pasang dependensi**
```bash
composer install
```

**3. Siapkan database**

Buat database baru, lalu impor file SQL:

```bash
mysql -u root -p -e "CREATE DATABASE digiwash_db_ziya"
mysql -u root -p digiwash_db_ziya < app/Database/digiwash_db_ziya.sql
```

> Atau buka phpMyAdmin, buat database `digiwash_db_ziya`, lalu impor `app/Database/digiwash_db_ziya.sql`.

**4. Atur koneksi database**

Buka `app/Config/Database.php`, lalu sesuaikan bagian `default`:

```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'digiwash_db_ziya',   // samakan dengan nama database yang diimpor
```

> Secara bawaan, nilai `database` di file itu adalah `digiwash`. Ganti sesuai nama database Anda.

**5. Jalankan server**
```bash
php spark serve
```

Buka **http://localhost:8080** di browser.

---

## Akun Contoh

File SQL berisi akun contoh untuk mencoba tiap peran:

| Peran | Email |
|---|---|
| Admin | `admin@digiwash.test` |
| Pelanggan | `budi@digiwash.test`, `siti@digiwash.test` |
| Kurir | `andi@digiwash.test`, `rudi@digiwash.test`, `kurir@digiwash.test` |

Kata sandi disimpan dalam bentuk hash di file SQL. Jika belum tahu kata sandinya, buat akun pelanggan baru lewat halaman **Daftar**, atau ganti hash di database.

> Catatan: akun ini hanya untuk percobaan. Hapus atau ganti kata sandinya sebelum aplikasi dipakai sungguhan.

---

## Rute Utama

| Alamat | Fungsi |
|---|---|
| `/` | Beranda |
| `/daftar` · `/masuk` · `/keluar` | Daftar, masuk, keluar |
| `/dashboard` | Dashboard pelanggan |
| `/layanan` · `/pesanan/...` | Pilih layanan, buat pesanan, bayar, tracking |
| `/profil` · `/alamat` | Profil dan alamat pelanggan |
| `/admin/...` | Halaman admin |
| `/kurir/...` | Halaman kurir |

Akses tiap halaman dibatasi oleh `RoleFilter` sesuai peran (`pelanggan`, `admin`, `kurir`).

---

## Pengujian

```bash
composer test
```

---

## Catatan untuk Pengembang

Beberapa file masih berisi **tanda konflik merge Git** (`<<<<<<<`, `=======`, `>>>>>>>`) sehingga aplikasi bisa error saat dijalankan. Selesaikan dulu konfliknya sebelum dipakai, terutama di:

- `app/Config/Routes.php`
- `app/Controllers/` (BaseController, Admin, Kurir, Pelanggan)
- `app/Config/Migrations.php`
- `app/Database/digiwash_db.sql` (gunakan `digiwash_db_ziya.sql` yang sudah bersih)

Cara mencari semua konflik yang tersisa:
```bash
grep -rn "^<<<<<<<" app
```

Hal lain yang perlu diperhatikan:
- Nomor rekening dan e-wallet di `Pesanan.php` masih contoh. Ganti dengan data asli.
- Folder `public/uploads/` berisi foto profil contoh. Sebaiknya tidak ikut di-commit.
- Atur `CI_ENVIRONMENT` ke `production` saat aplikasi dipasang di server.

---

## Lisensi

Proyek ini memakai lisensi **MIT**. Lihat file [LICENSE](LICENSE) untuk detailnya.

---

<p align="center">DigiWash - laundry jadi lebih mudah.</p>
