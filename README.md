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

[Performance Tuning](#performance-tuning)
- [Query Optimization](#1-query-optimization)
- [Caching](#2-caching)

[API Specification](#api-specification)
- [Endpoint](#endpoint)
    - [Author Endpoint](#author)
    - [Book Endpoint](#book)
- [Error](#error)
    - [Error Code 500](#1-status-code-500--internal-server-error)
    - [Error Code 422](#2-status-code-422--unprocessable-content)
    - [Error Code 404](#3-status-code-404--not-found)
    - [Error Code 409](#3-status-code-409--conflict)

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
```php
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
```bash
git clone https://github.com/pram212/be-technical-test.git
```
masuk ke directorty project
```bash
cd be-technical-test
```

#### 2. Instal Dependensi Backend
```bash
composer install
```

#### 3. Konfigurasi Environment
buat file `.env` dengan mengcopy-nya dari .env.example
```
cp .env.example .env
```
generate app key
```bash
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
```bash
php artisan migrate --seed
```

#### 5. Jalankan Server Development
Sekarang kamu bisa menjalankan server lokal Laravel dengan command:
```bash
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

## Performance Tuning
Untuk mengoptimalkan performa API, berikut ini teknik yang saya terapkan.
### 1. Query Optimization
#### Pagination

Pagination memecah data besar menjadi beberapa halaman yang lebih kecil. Setiap kali pengguna berpindah halaman, hanya data untuk halaman tersebut yang diambil, mengurangi beban server dan mempercepat waktu respon. Laravel menyediakan beberapa metode untuk menangani pagination, seperti paginate(), simplePaginate(), dan cursorPaginate(). 
Contoh:
```php
app/Http/Controllers/AuthorController.php

return AuthorResource::collection(Author::cursorPaginate(15));
```

Kelebihan:
- Mengurangi beban pengambilan data sekaligus.
- Respons yang lebih cepat untuk setiap permintaan.
- Pengguna bisa menavigasi antara halaman data.

Contoh respon:
```json
{
    "data": [
        {...}, // data
    ],
    "meta": {
        "current_page": 1,
        "last_page": 20,
        "per_page": 50,
        "total": 1000
    }
}
```

#### Indexing Database

Indexing database diterapkan pada kolom-kolom yang sering digunakan untuk pencarian, penyortiran, atau filtering. Indexing membantu mempercepat query terhadap tabel yang sangat besar. Pada kasus ini saya menerapkannya pada kolom-kolom yang mungkin digunakan untuk pencarian oleh user pada tabel authors dan books. Ada 2 index yang saya terapkan yakni `index` untuk `authors.name, books.title, books.publish_date` dan `foreign key` untuk kolom `books.author_id`.

### 2. Caching
Saya berencana menggunakan **Redis** sebagai solusi caching untuk meningkatkan performa API dalam menangani data yang besar dan sering diakses. **Redis** adalah in-memory data store yang sangat cepat dan cocok untuk kebutuhan caching, khususnya pada skenario REST API yang memerlukan akses data cepat dan minim latency Namun karena keterbatasan waktu selama pengembangan, saya tidak sempat melakukan konfigurasi dan integrasi Redis.

## API Specification
### Endpoint
#### Author
#### 1.  GET /authors
Digunakan untuk mengambil data authors.

Request
- Method: GET
- Endpoint: `http://localhost:8000/authors`
- Headers: 
    - `Content-Type : application/json`
- Response: 
```json
{
    "data": [
        {
            "id": 1,
            "name": "pramono",
            "bio": "fullstack developer",
            "birth_date": "1994-09-16"
        },
        ...
    ],
    "links": {
        "first": null,
        "last": null,
        "prev": null,
        "next": "http://localhost:8000/authors?cursor=eyJhdXRob3JzLmlkIjoxOCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ"
    },
    "meta": {
        "path": "http://localhost:8000/authors",
        "per_page": 15,
        "next_cursor": "eyJhdXRob3JzLmlkIjoxOCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ",
        "prev_cursor": null
    }
}
```
#### 2. POST /authors
Digunakan untuk membuat author baru

Request
- Method: POST
- Endpoint: `http://localhost:8000/authors`
- Headers: 
    - `Content-Type: application/json`
- Body:
```json
{
    "name" : "john doe",
    "bio" : "lecture",
    "birth_date" : "1994-09-16"
}
```
- Response: 
```json
{
    "message": "data saved successfully",
    "data": {
        "name": "john doe",
        "bio": "lecture",
        "birth_date": "1994-09-16",
        "updated_at": "2024-09-15T06:53:10.000000Z",
        "created_at": "2024-09-15T06:53:10.000000Z",
        "id": 12747
    }
}
```
#### 3. GET /authors/{id}
Digunakan untuk menampilkan data author tertentu.

Request
- Method: GET
- Endpoint: `http://localhost:8000/authors/{id}`
- Headers: 
    - `Content-Type : application/json`
- Response: 
```json
{
    "id": 1,
    "name": "pramono",
    "bio": "fullstack developer",
    "birth_date": "1994-09-16"
}
```

#### 4. PUT /authors/{id}
Digunakan untuk menampilkan data author tertentu.

Request
- Method: PUT/PATCH
- Endpoint: `http://localhost:8000/authors/{id}`
- Headers: 
    - `Content-Type : application/json`
- Body:
```json
{
    "name" : "john doe",
    "bio" : "lecture",
    "birth_date" : "1994-09-16"
}
```
- Response: 
```json
{
    "message": "data updated successfully",
    "data": {
        "name": "john doe",
        "bio": "lecture",
        "birth_date": "1994-09-16",
        "updated_at": "2024-09-15T06:53:10.000000Z",
        "created_at": "2024-09-15T06:53:10.000000Z",
        "id": 12747
    }
}
```

#### 5. DELETE /authors/{id}
Digunakan untuk menampilkan data author tertentu.

Request
- Method: DELETE
- Endpoint: `http://localhost:8000/authors/{id}`
- Headers: 
    - `Content-Type : application/json`
- Response: 
```json
{
    "message": "data deleted successfully"
}
```
#### Book
#### 1.  GET /books
Digunakan untuk mengambil data books.

Request
- Method: GET
- Endpoint: `http://localhost:8000/books`
- Headers: 
    - `Content-Type : application/json`
- Response: 
```json
{
    "data": [
        {
            "id": 4,
            "title": "Dormouse.",
            "description": "The door led right into a sort of thing never happened, and now here I am so VERY tired of swimming about here, O Mouse!' (Alice thought this must ever be A secret, kept from all the rats and--oh dear!' cried Alice (she was.",
            "publish_date": "2003-06-05",
            "author_id": 10
        },
        ...
    ],
    "links": {
        "first": null,
        "last": null,
        "prev": null,
        "next": "http://localhost:8000/books?cursor=eyJhdXRob3JzLmlkIjoxOCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ"
    },
    "meta": {
        "path": "http://localhost:8000/books",
        "per_page": 15,
        "next_cursor": "eyJhdXRob3JzLmlkIjoxOCwiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ",
        "prev_cursor": null
    }
}
```
#### 2. POST /books
Digunakan untuk membuat author baru

Request
- Method: POST
- Endpoint: `http://localhost:8000/books`
- Headers: 
    - `Content-Type: application/json`
- Body:
```json
{
    "title" : "belajar pemrograman php",
    "description" : "buku tutorial php",
    "author_id": 3,
    "publish_date": "2000-01-01"
}
```
- Response: 
```json
{
    "message": "data saved successfully",
    "data": {
        "title": "belajar pemrograman php",
        "description": "buku tutorial php",
        "author_id": 3,
        "publish_date": "2000-01-01",
        "updated_at": "2024-09-15T07:13:12.000000Z",
        "created_at": "2024-09-15T07:13:12.000000Z",
        "id": 26
    }
}
```
#### 3. GET /books/{id}
Digunakan untuk menampilkan data author tertentu.

Request
- Method: GET
- Endpoint: `http://localhost:8000/books/{id}`
- Headers: 
    - `Content-Type : application/json`
- Response: 
```json
{
    "message": "data saved successfully",
    "data": {
        "title": "belajar pemrograman php",
        "description": "buku tutorial php",
        "author_id": 3,
        "publish_date": "2000-01-01",
        "updated_at": "2024-09-15T07:13:12.000000Z",
        "created_at": "2024-09-15T07:13:12.000000Z",
        "id": 26
    }
}
```

#### 4. PUT /books/{id}
Digunakan untuk menampilkan data author tertentu.

Request
- Method: PUT/PATCH
- Endpoint: `http://localhost:8000/books/{id}`
- Headers: 
    - `Content-Type : application/json`
- Body:
```json
{
    "title" : "belajar pemrograman laravel",
    "description" : "buku tutorial php",
    "author_id": 3,
    "publish_date": "2000-01-01"
}
```
- Response: 
```json
{
    "message": "data updated successfully",
    "data": {
        "id": 5,
        "title": "belajar pemrograman laravel",
        "description": "buku tutorial php",
        "publish_date": "2000-01-01",
        "author_id": 3,
        "created_at": "2024-09-14T15:02:11.000000Z",
        "updated_at": "2024-09-15T07:15:03.000000Z"
    }
}
```

#### 5. DELETE /books/{id}
Digunakan untuk menampilkan data author tertentu.

Request
- Method: DELETE
- Endpoint: `http://localhost:8000/books/{id}`
- Headers: 
    - `Content-Type : application/json`
- Response: 
```json
{
    "message": "data deleted successfully"
}
```

## Error

### 1. Status code `500` | Internal Server Error
- Issue : Internal Server Error
- Response:
```json
{
    "message": "internal server error"
}
```
### 2. Status code `422` | Unprocessable Content
- Issue : Error valisadi input
- Response:
```json
{
    "message": "The selected author id is invalid.",
    "errors": {
        "author_id": [
            "The selected author id is invalid."
        ]
    }
}
```
### 3. Status code `404` | Not Found
- Issue : Data tidak ditemukan atau endpoint salah
- Response:
```json
{
    "message": "Data not found"
}
```
### 3. Status code `409` | Conflict
- Issue : Menghapus data yang memiliki relasi ke tabel lain
- Response:
```json
{
    "message": "Cannot delete author because they have associated books"
}
```









