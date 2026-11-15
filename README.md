# Nirwana Garage

**Sistem Manajemen Bengkel Sepeda Motor Berbasis Web** yang dirancang untuk membantu mengelola proses operasional bengkel secara terintegrasi.

Sistem mencakup pengelolaan **booking servis, pelanggan, kendaraan, Work Order, proses servis, serta inventaris suku cadang dan material untuk kebutuhan cat**.

## Features

- Booking dan tracking servis
- Pengelolaan pelanggan dan kendaraan
- Pengelolaan Work Order
- Pemeriksaan dan diagnosis kendaraan
- Pencatatan pekerjaan servis
- Pengelolaan inventaris suku cadang
- Pencatatan pergerakan stok
- Monitoring proses operasional bengkel

## Tech Stack
+-------------------------------------------------------------+
| Kategori                  | Teknologi                       |
+---------------------------|---------------------------------+
| Backend                   | PHP 8.3, Laravel                |
| Frontend                  | Blade, Tailwind CSS, JavaScript |
| Asset Bundler             | Vite                            |
| JavaScript Runtime        | Node.js 22                      |
| Database                  | MySQL 8.4                       |
| Web Server                | Nginx                           |
| PHP Process Manager       | PHP-FPM                         |
| Containerization          | Docker                          |
| Container Orchestration   | Docker Compose                  |
| Dependency Management     | Composer, npm                   |
| Version Control           | Git                             |
+-------------------------------------------------------------+
## Architecture

Project ini menggunakan pendekatan **Monolithic Web Application**, dengan Laravel sebagai backend sekaligus framework utama untuk membangun aplikasi web.

Aplikasi dijalankan menggunakan Docker Compose dengan tiga layanan utama:

- **App:** Menjalankan aplikasi Laravel menggunakan PHP-FPM.
- **Nginx:** Menangani HTTP request dan menyajikan halaman aplikasi.
- **Database:** Menggunakan MySQL 8.4 untuk penyimpanan data.

Dockerfile menggunakan pendekatan Multi-Stage Build untuk proses instalasi dependency PHP, pembangunan frontend assets menggunakan Vite, dan konfigurasi web server.

## Requirements

Pastikan perangkat telah memiliki:

- Docker Engine
- Docker Compose V2

## Installation

### 1. Extract Project

Ekstrak file ZIP source code Nirwana Garage ke direktori kerja.

### 2. Environment Configuration

Buat file .env berdasarkan .env.example:

cp .env.example .env

Sesuaikan konfigurasi database berikut:

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=nama_user
DB_PASSWORD=password_database
DB_ROOT_PASSWORD=password_root

Sesuaikan juga konfigurasi API key dan layanan eksternal yang diperlukan.

### 3. Build and Run Containers

Jalankan perintah berikut pada direktori root project:

docker compose up -d --build

Perintah tersebut akan membangun image dan menjalankan container aplikasi berdasarkan konfigurasi Docker Compose.

### 4. Generate Application Key

Jalankan perintah:

docker compose exec app php artisan key:generate

### 5. Database Migration

Jalankan migration dan seeder:

docker compose exec app php artisan migrate --seed

### 6. Access Application

Setelah container berjalan, aplikasi dapat diakses melalui browser:

http://localhost

## Docker Commands

Melihat status container:

docker compose ps

Melihat logs aplikasi:

docker compose logs -f app

Menghentikan container:

docker compose down

Menjalankan kembali container:

docker compose up -d

## Important Notes

- File .env asli tidak disertakan dalam source code.
- Gunakan .env.example sebagai referensi konfigurasi environment.
- API key dan credential layanan eksternal harus dikonfigurasi secara mandiri.
- Pastikan port 80 tersedia sebelum menjalankan aplikasi.
- Database menggunakan Docker named volume untuk penyimpanan data.

## Project Status

**Completed**

Project ini dikembangkan sebagai bagian dari kegiatan **Kerja Praktik (KP)** dan tugas project mata kuliah **Rekayasa Perangkat Lunak**.