# Presensi Pegawai - Laravel 11

Aplikasi presensi berbasis web menggunakan Laravel 11.
Project ini dirancang untuk memudahkan manajemen presensi pegawai dengan fitur validasi lokasi, foto saat presensi, serta rekapitulasi kehadiran.

---

## Tim Pengembang

* **ViscaDwipayanti**
* **NoviWulandari123**
* **widyayu19**

---

## Fitur Utama

* **Autentikasi** (login admin & pegawai)
* **Dashboard** khusus admin & pegawai
* **Data Pegawai** (relasi dengan jabatan & lokasi presensi)
* **Presensi Masuk & Keluar** dengan validasi lokasi & foto
* **Rekap Presensi & Ketidakhadiran**
* **CRUD Pengajuan Ketidakhadiran** (pegawai upload lampiran, admin approval)

---

## Struktur Project

* `app/` → Controller, Model, Service, dll
* `database/migrations/` → Struktur tabel (menggunakan migration)
* `resources/views/` → Blade template (UI aplikasi)
* `public/` → Asset publik (CSS, JS, gambar, build front-end)
* `storage/app/public/` → Tempat file upload (foto pegawai, foto presensi, lampiran)

---

## Instalasi

1. Clone repository:

   ```bash
   git clone https://github.com/USERNAME/presensi-laravel11.git
   cd presensi-laravel11
   ```

2. Install dependency:

   ```bash
   composer install
   npm install && npm run dev
   ```

3. Duplikat file `.env.example` menjadi `.env`:

   ```bash
   cp .env.example .env
   ```

4. Generate key aplikasi:

   ```bash
   php artisan key:generate
   ```

5. Atur koneksi database di file `.env`, lalu jalankan migration:

   ```bash
   php artisan migrate --seed
   ```

6. Buat symbolic link untuk upload:

   ```bash
   php artisan storage:link
   ```

7. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

---

## Upload File (Foto & Lampiran)

* Semua file upload (foto pegawai, foto presensi, lampiran ketidakhadiran) **tidak disimpan di GitHub**.
* File-file ini tersimpan di folder `storage/app/public` dan bisa diakses via `public/storage`.
* Pastikan setelah clone repo jalankan:

  ```bash
  php artisan storage:link
  ```

---

## Teknologi yang Digunakan

* **Laravel 11** (PHP Framework)
* **MySQL** (Database)
* **Blade + Bootstrap** (UI)
* **JavaScript + jQuery**
* **Composer & NPM** untuk dependency

---

## Keamanan

* File `.env` tidak dipush ke repository
* Folder `vendor/`, `node_modules/`, `storage/logs`, dan file upload diabaikan
* Log & cache tidak disimpan di repo

---

## Lisensi

Project ini dibuat untuk kebutuhan pembelajaran & pengembangan aplikasi internal.


