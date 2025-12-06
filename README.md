# 📋 Aplikasi To-Do List

Aplikasi manajemen tugas berbasis web yang modern dan interaktif dengan antarmuka. Dibangun menggunakan **Laravel**, **Bootstrap 5**, dan **JavaScript** untuk pengalaman pengguna yang responsif.

## ✨ Fitur Utama

### 📌 Manajemen Tugas

-   ✅ Buat, edit, dan hapus tugas dengan mudah
-   📅 Atur tanggal jatuh tempo untuk setiap tugas
-   📝 Tambahkan deskripsi lengkap untuk tugas
-   🏷️ Kategorisasi tugas dengan label yang dapat dikustomisasi
-   🎯 Tingkat urgensi otomatis berdasarkan tanggal jatuh tempo

### 🎨 Antarmuka Kanban

-   📊 Tampilan papan Kanban dengan kolom yang dapat disesuaikan
-   🔄 Drag-and-drop antar kolom untuk mengelola alur kerja
-   📱 Desain responsif untuk desktop, tablet, dan mobile
-   🎨 Warna dan ikon kustom untuk setiap kategori

### 🔍 Filter & Pencarian

-   🚨 Filter tugas yang sudah melewati tenggat waktu
-   ⚡ Filter tugas yang mendesak (akan jatuh tempo dalam 2 minggu)
-   📆 Filter tugas minggu ini
-   ❌ Filter tugas tanpa tanggal jatuh tempo

### 📊 Tampilan Tugas

-   🏷️ Badge kategori dengan warna dan ikon
-   📅 Badge tanggal jatuh tempo dengan perhitungan hari otomatis
-   📊 Penghitung tugas di setiap kolom
-   🕐 Waktu pembaruan terakhir untuk setiap tugas

## 🛠️ Teknologi yang Digunakan

### Backend

-   **Laravel 11** - Framework PHP modern
-   **MySQL** - Database relasional

### Frontend

-   **Bootstrap 5** - Framework CSS responsif
-   **Select2 4.1** - Dropdown yang dapat dicari
-   **Sortable.js** - Drag-and-drop yang intuitif
-   **Flatpickr** - Date picker tanpa dependensi
-   **FontAwesome** - Ikon yang indah

### Development Tools

-   **Vite** - Build tool modern
-   **Tailwind CSS** - Utility-first CSS framework
-   **PostCSS** - Alat transformasi CSS

## 📋 Persyaratan Sistem

-   **PHP** 8.2 atau lebih tinggi
-   **Composer** untuk manajemen dependensi
-   **MySQL 8.0** atau lebih tinggi
-   **Node.js 18+** untuk frontend assets

## 🚀 Instalasi & Setup

### 1. Clone Repository

```bash
git clone https://github.com/facchrizhaaripandra/todo-app.git
cd todo-app
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Node.js

```bash
npm install
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Setup Database

Ubah konfigurasi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_app
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 7. Jalankan Seeder (Opsional)

```bash
php artisan db:seed
```

### 8. Build Frontend Assets

```bash
npm run dev
```

### 9. Jalankan Development Server

```bash
php artisan serve
```

Aplikasi akan tersedia di: `http://localhost:8000`

## 📁 Struktur Direktori

```
todo-app/
├── app/
│   ├── Http/
│   │   └── Controllers/     # Controller untuk CRUD operations
│   └── Models/              # Model Eloquent (Task, Category, Column, User)
├── database/
│   ├── migrations/          # File migrasi database
│   ├── factories/           # Factory untuk testing
│   └── seeders/             # Seeder untuk data awal
├── resources/
│   ├── css/                 # File CSS (Tailwind, custom styles)
│   ├── js/                  # File JavaScript
│   └── views/               # Blade templates
│       ├── layouts/         # Layout utama
│       ├── partials/        # Komponen reusable
│       └── tasks/           # View untuk halaman tugas
├── routes/
│   └── web.php              # Route definition
├── config/                  # Konfigurasi aplikasi
├── public/                  # File publik (index.php, assets)
└── storage/                 # File storage (cache, logs, sessions)
```

## 🗄️ Model Database

### Task (Tugas)

-   `id` - ID unik
-   `title` - Judul tugas
-   `description` - Deskripsi lengkap
-   `column_id` - Kolom tempat tugas berada
-   `category_id` - Kategori tugas
-   `due_date` - Tanggal jatuh tempo
-   `created_at`, `updated_at` - Timestamp

### Category (Kategori)

-   `id` - ID unik
-   `name` - Nama kategori
-   `color` - Warna hex (#RRGGBB)
-   `icon` - Kelas FontAwesome icon
-   `created_at`, `updated_at` - Timestamp

### Column (Kolom)

-   `id` - ID unik
-   `name` - Nama kolom
-   `order` - Urutan tampilan kolom
-   `created_at`, `updated_at` - Timestamp

### User (Pengguna)

-   `id` - ID unik
-   `name` - Nama pengguna
-   `email` - Email
-   `password` - Password terenkripsi
-   `created_at`, `updated_at` - Timestamp

## 🎮 Cara Penggunaan

### 1. Membuat Tugas Baru

-   Klik tombol **"+ Tambah Tugas"** di header
-   Isi judul, deskripsi (opsional), pilih kategori, dan tanggal jatuh tempo
-   Klik **"Simpan Tugas"**

### 2. Mengedit Tugas

-   Klik ikon **pencil** pada kartu tugas
-   Ubah informasi yang diperlukan
-   Klik **"Simpan Perubahan"**

### 3. Memindahkan Tugas

-   Drag kartu tugas ke kolom lain
-   Tugas akan otomatis disimpan di kolom baru
-   Tugas akan otomatis diurutkan berdasarkan tanggal jatuh tempo

### 4. Menambah Kategori

-   Klik tombol **"+ Kategori"** di header
-   Pilih warna dan ikon
-   Masukkan nama kategori
-   Klik **"Buat Kategori"**

### 5. Menambah Kolom

-   Klik tombol **"+ Kolom"** di header
-   Masukkan nama kolom
-   Kolom baru akan ditambahkan ke papan Kanban

### 6. Filter Tugas

-   Gunakan tombol filter di header untuk melihat:
    -   **Semua**: Tampilkan semua tugas
    -   **Sudah Melewati Tenggat**: Tugas yang sudah melewati tanggal jatuh tempo
    -   **Mendesak**: Tugas dalam 2 minggu ke depan
    -   **Minggu Ini**: Tugas yang jatuh tempo minggu ini
    -   **Tanpa Tanggal**: Tugas tanpa tanggal jatuh tempo

## 🔄 Fitur Drag-and-Drop

-   **Drag Antar Kolom**: Tarik tugas ke kolom lain untuk mengubah statusnya
-   **Visual Feedback**: Animasi visual saat drag-and-drop
-   **Auto-Sort**: Tugas otomatis disortir berdasarkan tanggal jatuh tempo
-   **Empty State**: Pesan "Tidak ada tugas" muncul saat kolom kosong

## 📝 Catatan Penting

### Perhitungan Hari Jatuh Tempo

-   Sistem menggunakan `floor()` untuk nilai negatif dan `ceil()` untuk nilai positif
-   Ini memastikan perhitungan hari selalu berupa angka bulat (integer), bukan desimal

### Tingkat Urgensi

-   **Overdue**: Tugas yang sudah melewati tanggal jatuh tempo
-   **High**: Tugas akan jatuh tempo dalam 2 hari ke depan
-   **Medium**: Tugas akan jatuh tempo dalam 3-7 hari
-   **Low**: Tugas akan jatuh tempo dalam 8-14 hari
-   **None**: Tugas akan jatuh tempo lebih dari 14 hari atau tanpa tanggal

## 🐛 Troubleshooting

### Database Connection Error

```bash
# Pastikan MySQL running dan konfigurasi .env benar
php artisan migrate:fresh
```

### Frontend Assets Tidak Terload

```bash
# Rebuild assets
npm run dev
php artisan cache:clear
```

### Hasil
#### Halaman Utama
<img width="1894" height="919" alt="image" src="https://github.com/user-attachments/assets/e1caf31f-6003-4fdc-9351-052cdf98e72b" />

#### Halaman Tambah Task (Tugas)
<img width="1001" height="723" alt="image" src="https://github.com/user-attachments/assets/17665ca4-3e52-4d30-a0b6-2b4d323e18e0" />

#### Halaman Tambah Kategori
<img width="636" height="705" alt="image" src="https://github.com/user-attachments/assets/f3b5c84f-96e3-4d58-8ad8-98200d5e18f0" />


