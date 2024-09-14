# Back-End Engineer Test

## Daftar Isi
[Arsitektur & Design](#arsitektur--design)
- [Framework](#framework)
- [Routing & Endpoint](#routing--endpoint)
- [Database & Model](#database--model)
- [Validasi](#validasi)

[Cara Instalasi dan Menjalankan Project](#cara-instalasi-dan-menjalankan-project)
- [Persyaratan](#persyaratan)
- [Langkah-langkah Instalasi](#langkah-langkah-instalasi)
    - [Clone Repository](#1-clone-repository)
    - [Instal Dependensi Backend](#2-instal-dependensi-backend)
    - [Konfigurasi Environment](#3-konfigurasi-environment)
    - [Migrasi Database](#4-migrasi-database)
    - [Jalankan Server Development](#5-jalankan-server-development)
    - [Troubleshooting](#6-troubleshooting)

[Unit Testing](#unit-testing)

---

## Arsitektur & Design
## Framework
API ini dibangun dengan bahasa pemrogramman PHP menggunakan framework **Laravel**, yang menggunakan pola desain **MVC (Model-View-Controller)**. API ini memisahkan logika aplikasi ke dalam beberapa bagian:
- **Model**: Berinteraksi langsung dengan database.
- **Controller**: Mengelola logika yang menangani permintaan (request) dan respons.
- **Routes**: Memetakan endpoint API ke controller yang sesuai.

Dengan pendekatan ini, Laravel memudahkan pengembangan aplikasi berskala besar dan memelihara kode secara efisien.

## Routing & Endpoint
Semua rute API didefinisikan di dalam file `routes/web.php`. Berikut beberapa contoh endpoint yang tersedia:
```
Route::apiResource('authors', AuthorController::class);
```

## Database & Model
API ini menggunakan Eloquent ORM untuk berinteraksi dengan database. Setiap model terkait dengan tabel di database dan memudahkan operasi query.

## Validasi
Validasi dilakukan di controller untuk memastikan data yang dikirim valid. Namun untuk detail logika validasinya disimpan di dalam file terpisah di `app/Http/Request/` Jika validasi gagal, API akan mengembalikan error `422 Unprocessable Entity`.

## Cara Instalasi dan Menjalankan Project
## Persyaratan
Sebelum memulai, pastikan sistem kamu sudah memenuhi persyaratan berikut:
- **PHP** versi 8.2 atau lebih baru
- **Composer** versi terbaru
- **Database**: MySQL
- **Git** (opsional, untuk cloning repository)

## Langkah-langkah Instalasi

#### 1. Clone Repository
Pertama, clone repository project ini dari GitHub atau sumber lain ke dalam direktori lokal:
```
git clone https://github.com/pram212/be-technical-test.git
```
masuk ke directorty project
```
cd be-technical-test
```

#### 2. Instal Dependensi Backend
```
composer install
```

#### 3. Konfigurasi Environment
buat file `.env` dengan mengcopy-nya dari .env.example
```
cp .env.example .env
```
generate app key
```
php artisan key:generate
```
Kemudian buka file `.env` dan sesuaikan detail koneksi database, misalnya:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database
```
Matikan mode debug 
```
APP_DEBUG=false
```

#### 4. Migrasi Database
Jalankan migrasi database untuk membuat tabel-tabel yang diperlukan dan insert data dummy. Anda dapat melakukannya dengan menjalankan command:
```
php artisan migrate --seed
```

#### 5. Jalankan Server Development
Sekarang kamu bisa menjalankan server lokal Laravel dengan command:
```
php artisan serve
```
Server akan berjalan pada http://localhost:8000.

#### 6. Troubleshooting
Jika mengalami masalah saat instalasi atau konfigurasi, pastikan:
- Versi PHP kompatibel dengan Laravel.
- Dependensi sudah terinstal dengan benar (`composer install` tidak mengeluarkan error).
- Koneksi database sudah diatur dengan benar di `.env`.

## Unit Testing
Dalam case ini saya menggunakan `PHPUnit` sebagai unit testing aplikasi yang sudah tersedia dalam framework laravel. Logic dari setiap test yang diperlukan disimpan dalam folder `test/`. Test dapat dilakukan dengan menjalankan perintah `php artisan test`. 