# Inventory Management System — P5 Laravel

Proyek P5 adalah pemindahan dan perapian Inventory Management System P4 (PHP Native) ke Laravel. Business logic yang dipertahankan mencakup produk, kategori, pemasok, pengguna dengan peran admin/staff, transaksi stok masuk/keluar, dashboard, dan laporan.

## Menjalankan secara lokal
1. Buka folder proyek ini di VS Code.
2. Untuk mempertahankan koneksi dan data P5, salin file `.env` dari proyek Laravel P5 lama ke folder ini. Jangan mengganti nilai `DB_DATABASE` jika ingin tetap menggunakan database P5 yang sama. Jika tidak memiliki `.env`, salin `.env.example` menjadi `.env` lalu atur database secara manual.
3. Jalankan `composer install`.
4. Jalankan `php artisan key:generate` hanya jika `APP_KEY` di `.env` masih kosong.
5. Cadangkan database terlebih dahulu, lalu jalankan `php artisan migrate` untuk menambahkan struktur yang dibutuhkan. Perintah ini tidak menghapus tabel/data yang sudah ada.
6. Jalankan `php artisan db:seed --class=AdminUserSeeder` jika perlu membuat akun admin demo. Seeder tidak mengubah akun yang emailnya sudah ada.
7. Jalankan `php artisan serve --host=127.0.0.1 --port=8080`, lalu buka http://127.0.0.1:8080.

## Akun pengujian
- Username: `admin`
- Email: `admin@inventory.test`
- Password: `Admin123!`

Ganti kata sandi ini sebelum aplikasi digunakan bersama orang lain.

## Menggunakan MySQL (opsional)
Buat database baru khusus Laravel, misalnya `inventory_laravel`, lalu ubah `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=inventory_laravel`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`. Jangan arahkan aplikasi ke database P4/`inventory_app` sebelum backup dan pemetaan data selesai.

## Catatan penting migrasi
Migrasi Laravel mengatur struktur tabel, bukan menyalin otomatis isi database P4. Data lama tetap harus dipetakan dan diimpor secara terencana. Buat salinan cadangan file SQLite atau ekspor database MySQL sebelum migrasi. Jangan menjalankan `php artisan migrate:fresh` atau menghapus `database/database.sqlite` jika ada data yang ingin dipertahankan.
## Arsitektur MVC

Project ini menggunakan pola Model–View–Controller (MVC) yang disediakan Laravel.

- **Model:** merepresentasikan data dan berinteraksi dengan database, misalnya model produk dan kategori.
- **View:** menggunakan Blade Template Engine untuk menampilkan halaman, formulir, tabel, dan pesan validasi kepada pengguna.
- **Controller:** menerima request dari pengguna, memvalidasi input, memproses operasi aplikasi melalui model, dan mengembalikan view atau redirect.

Alur request dimulai dari `routes/web.php`, diteruskan ke controller, kemudian controller berinteraksi dengan model jika diperlukan. Hasilnya ditampilkan melalui Blade View atau pengguna diarahkan ke halaman lain.

## Migration dan Seeder

- **Migration:** mengelola perubahan struktur tabel database secara terkontrol melalui file di `database/migrations/`.
- **Seeder:** memasukkan data awal atau akun pengujian melalui file di `database/seeders/`.
- **Pencadangan:** lakukan backup database sebelum menjalankan perubahan struktur. Gunakan database khusus pengujian ketika mencoba instalasi dari awal.

## Pengujian CRUD dan Validasi

Alur pengujian penambahan produk:

1. Pengguna membuka halaman produk dan memilih form tambah produk.
2. Pengguna mengisi form. Input yang tidak valid harus ditolak dan pesan validasi ditampilkan.
3. Pengguna memperbaiki input dan mengirim form kembali.
4. Controller memvalidasi data dan menyimpannya melalui model.
5. Aplikasi melakukan redirect setelah penyimpanan berhasil.
6. Produk yang baru ditambahkan tampil pada halaman daftar produk.

Pengujian juga mencakup pembaruan dan penghapusan data, kategori, supplier, transaksi stok, laporan, serta pembatasan akses berdasarkan peran pengguna.

## Bukti Screenshot

Screenshot pengujian disimpan dalam folder `screenshots/`.

- (screenshoot/screenshots/screenshots01-route-list.png)(screenshoot/screenshots/screenshots01-route-list.png) — daftar route Laravel.
- (screenshoot/screenshots/02-crud-produk.png) — halaman daftar dan form produk.
- (screenshoot/screenshots/03-blade-view.png) — contoh kode Blade View.
-(screenshoot/screenshots/04c-data-tersimpan.png)
(screenshoot/screenshots/04b-validasi.png)
(screenshoot/screenshots/04a-form-produk.png) — bukti validasi, penyimpanan, redirect, dan data yang tampil.

Nama file di atas merupakan format yang disarankan. Pastikan setiap screenshot diambil dari aplikasi dan source code proyek ini.