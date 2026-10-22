# Inventory Management System — Laravel UTS

## 1. Project Overview

Inventory Management System adalah aplikasi pengelolaan persediaan barang berbasis web yang dikembangkan menggunakan Laravel 12 dan MySQL/MariaDB. Proyek ini merupakan pengembangan aplikasi inventaris dengan penambahan dan penataan fitur menggunakan arsitektur Laravel.

Aplikasi membantu pengguna mengelola produk, kategori, pemasok, pembelian, penjualan, pergerakan stok, serta laporan persediaan. Struktur aplikasi menggunakan pola Model–View–Controller (MVC).

## 2. Requirements

Perangkat lunak yang diperlukan:

- PHP 8.2 atau lebih baru.
- Composer untuk mengelola dependensi PHP.
- MySQL atau MariaDB, misalnya melalui XAMPP.
- Web browser, seperti Google Chrome.
- Node.js dan npm jika diperlukan untuk mengelola atau membangun aset frontend.

## 3. Installation

1. Clone repository GitHub:

   ```bash
   git clone https://github.com/aisah250180056-prog/inventory-laravel-uts.git
   ```

2. Masuk ke folder proyek:

   ```bash
   cd inventory-laravel-uts
   ```

3. Instal dependensi Laravel:

   ```bash
   composer install
   ```

4. Jika file `.env` belum tersedia, buat salinannya dari `.env.example`:

   ```powershell
   Copy-Item .env.example .env
   ```

5. Jika `APP_KEY` masih kosong, jalankan:

   ```bash
   php artisan key:generate
   ```

6. Pastikan konfigurasi database sudah benar sebelum menjalankan aplikasi.

7. Jalankan server lokal:

   ```bash
   php artisan serve --host=127.0.0.1 --port=8080
   ```

8. Buka aplikasi melalui browser:

   http://127.0.0.1:8080

## 4. Environment Setup

Atur konfigurasi database di file `.env` sesuai lingkungan lokal:

```dotenv
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

Nilai di atas merupakan contoh konfigurasi lokal. Sesuaikan nama database, username, dan password dengan pengaturan MySQL/MariaDB yang digunakan.

Pastikan layanan database aktif dan database tujuan sudah tersedia. Jangan mengganti konfigurasi `.env` yang sedang digunakan jika ingin mempertahankan koneksi ke database yang sudah ada.

File `.env` berisi konfigurasi lokal dan tidak boleh diunggah ke repository GitHub.

## 5. Migration & Seeding

Migration digunakan untuk mengelola perubahan struktur database, sedangkan seeder digunakan untuk memasukkan data awal.

Sebelum menjalankan migration pada database yang sudah berisi data:

1. Buat backup database terlebih dahulu.
2. Periksa status migration:

   ```bash
   php artisan migrate:status
   ```

3. Jalankan migration yang masih pending setelah memastikan perubahan struktur aman:

   ```bash
   php artisan migrate
   ```

Seeder yang tersedia di proyek:

- `CategorySeeder` — data awal kategori.
- `ProductSeeder` — data awal produk.
- `AdminUserSeeder` — akun admin demo.

`DatabaseSeeder` menjalankan `CategorySeeder` dan `ProductSeeder`. Untuk database pengujian baru yang memang disiapkan untuk data awal, jalankan:

```bash
php artisan db:seed
```

Untuk membuat atau memperbarui akun demo secara terpisah:

```bash
php artisan db:seed --class=AdminUserSeeder
```

**Peringatan:** seeder dapat menambah atau memperbarui data, bergantung pada implementasinya. Periksa kode seeder dan pastikan database yang digunakan sesuai sebelum menjalankannya. Jangan menjalankan `php artisan migrate:fresh` atau `php artisan db:wipe` pada database yang datanya ingin dipertahankan.

Migration tidak otomatis mengimpor seluruh data dari aplikasi PHP Native sebelumnya.

## 6. Demo Account

Gunakan akun berikut untuk pengujian lokal jika akun demo sudah tersedia:

- **Username:** `admin`
- **Email:** `admin@inventory.test`
- **Password:** `Admin123!`
- **Role:** `admin`

Akun tersebut didefinisikan dalam `AdminUserSeeder.php`. Jika akun belum tersedia, jalankan seeder khusus admin setelah memastikan database tujuan benar.

Kredensial ini ditujukan untuk demonstrasi lokal. Ganti kata sandi sebelum aplikasi digunakan di lingkungan bersama atau produksi.

## 7. Features

Fitur utama aplikasi meliputi:

- Login dan logout pengguna.
- Pengaturan akses berdasarkan peran admin dan staff.
- Pengelolaan produk: tambah, lihat, ubah, dan hapus.
- Pengelolaan kategori dan pemasok.
- Relasi data produk dengan kategori dan pemasok.
- Transaksi pembelian untuk menambah stok.
- Transaksi penjualan untuk mengurangi stok.
- Pencatatan pergerakan stok masuk dan keluar.
- Dashboard dan laporan inventaris.
- Validasi input untuk membantu mencegah data tidak valid.
- Antarmuka berbasis Blade Template Engine.

Alur CRUD produk: pengguna membuka formulir, mengisi data, mengirim permintaan, lalu controller melakukan validasi dan menyimpan data melalui model. Setelah berhasil, aplikasi mengarahkan pengguna kembali ke halaman terkait untuk menampilkan data terbaru.

## 8. Known Limitations

- Aplikasi ditujukan untuk pengembangan dan demonstrasi lokal; konfigurasi produksi memerlukan pengamanan tambahan.
- Aplikasi memerlukan PHP, Composer, dan layanan MySQL/MariaDB yang sesuai.
- Migration mengelola struktur tabel, tetapi tidak otomatis memindahkan data dari aplikasi lama.
- Hasil seeding bergantung pada implementasi masing-masing seeder dan database yang digunakan.
- Data transaksi dan stok perlu dijaga konsistensinya ketika menguji pembelian maupun penjualan.
- Screenshot dokumentasi harus diperbarui jika tampilan atau fitur aplikasi berubah.


## Screenshots

Dokumentasi visual berikut merupakan bukti implementasi dan pengujian aplikasi Inventory Management System berbasis Laravel untuk UTS. Seluruh screenshot diambil dari proyek ini dan disimpan di folder `screenshots/`.

### A. Tampilan dan CRUD Produk
- `screenshots/01-dashboard-uts.png` — dashboard aplikasi.
- `screenshots/02-crud-produk-uts.png` — halaman pengelolaan produk.
- `screenshots/03-form-produk-uts.png` — formulir produk.
- `screenshots/04-validasi-produk-uts.png` — bukti validasi input.
- `screenshots/05-produk-tersimpan-uts.png` — bukti penyimpanan produk.

### B. Transaksi dan Persediaan
- `screenshots/06-pembelian-uts.png` — halaman transaksi pembelian.
- `screenshots/07-penjualan-uts.png` — halaman transaksi penjualan.
- `screenshots/08-riwayat-stok-uts.png` — riwayat pergerakan stok.
- `screenshots/09-laporan-uts.png` — halaman laporan inventaris.

### C. Dokumentasi Teknis dan Pengujian
- `screenshots/10-route-list-uts.png` — daftar route Laravel.
- `screenshots/11-blade-view-uts.png` — contoh kode Blade View.
- `screenshots/12-migration-uts.png` — contoh migration database.
- `screenshots/13-hasil-pengujian-uts.png` — bukti hasil pengujian aplikasi.
