# E-Commerce Platform

Platform e-commerce lengkap yang dibangun dengan CodeIgniter 4, dilengkapi dengan sistem manajemen produk, keranjang belanja, checkout, integrasi pembayaran Midtrans, dan panel admin dengan role-based access control (RBAC).

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Struktur Project](#-struktur-project)
- [Penggunaan](#-penggunaan)
- [Role dan Akses](#-role-dan-akses)
- [Database Migration](#-database-migration)
- [Troubleshooting](#-troubleshooting)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

## ✨ Fitur Utama

### 🛍️ Frontend (Customer)

- **Beranda Shop**
  - Tampilan produk dengan pagination
  - Pencarian produk berdasarkan nama, deskripsi, atau SKU
  - Filter produk berdasarkan kategori
  - Tampilan detail produk

- **Keranjang Belanja**
  - Tambah produk ke keranjang
  - Update jumlah produk
  - Hapus produk dari keranjang
  - Kosongkan keranjang
  - Keranjang otomatis dikosongkan setelah pembayaran berhasil

- **Checkout & Pembayaran**
  - Proses checkout dengan validasi alamat pengiriman
  - Integrasi pembayaran Midtrans (Snap)
  - Dukungan berbagai metode pembayaran (Bank Transfer, E-Wallet, Credit Card, dll)
  - Notifikasi status pembayaran real-time
  - Auto-clear cart setelah pembayaran berhasil

- **Manajemen Akun**
  - Registrasi dan login customer
  - Profil pengguna dengan update data
  - Ubah password
  - Riwayat pesanan
  - Detail pesanan

- **UI/UX**
  - Desain responsif untuk mobile dan desktop
  - SweetAlert2 untuk notifikasi yang user-friendly
  - Navigation menu yang profesional dengan dropdown user

### 👨‍💼 Backend (Admin Panel)

- **Dashboard**
  - Statistik total users, produk, kategori, dan pesanan
  - Total revenue dan monthly revenue
  - Daftar pesanan terbaru
  - Produk dengan stok rendah

- **Manajemen Produk**
  - CRUD produk lengkap
  - Upload gambar produk
  - Manajemen stok
  - Multiple kategori per produk
  - Validasi form dengan SweetAlert2

- **Manajemen Kategori**
  - CRUD kategori
  - Upload gambar kategori
  - Validasi form dengan SweetAlert2

- **Manajemen Pesanan**
  - Daftar semua pesanan
  - Detail pesanan
  - Update status pesanan (pending, processing, shipped, completed, cancelled)
  - Update status pembayaran (pending, paid, failed, expired, cancelled)
  - Filter berdasarkan status

- **Manajemen Customer**
  - Daftar semua customer
  - CRUD customer
  - Upload avatar customer
  - Validasi form dengan SweetAlert2

- **Manajemen User (Admin Only)**
  - Daftar semua user (admin, manager, staff)
  - CRUD user
  - Assign role ke user
  - Upload avatar user
  - Validasi form dengan SweetAlert2

- **Manajemen Role (Admin Only)**
  - Daftar semua role
  - CRUD role
  - Validasi form dengan SweetAlert2
  - Tampilan responsif untuk mobile dan desktop

- **Laporan Penjualan (Admin & Manager Only)**
  - Laporan penjualan dengan filter tanggal
  - Export laporan ke Excel (XLSX) menggunakan PhpSpreadsheet
  - Statistik penjualan

- **Activity Log (Admin Only)**
  - Log semua aktivitas sistem
  - Filter berdasarkan action, user, dan tanggal
  - Pagination dengan opsi 5, 10, 50, 100, 500 per halaman
  - Tampilan responsif untuk mobile dan desktop

- **UI/UX Admin**
  - Desain responsif untuk mobile dan desktop
  - SweetAlert2 untuk semua notifikasi dan konfirmasi
  - Sidebar menu dengan role-based access control
  - Logout dengan konfirmasi SweetAlert

### 🔐 Sistem Keamanan

- **Role-Based Access Control (RBAC)**
  - 4 level role: Admin, Manager, Staff, Customer
  - Filter-based route protection
  - Helper functions untuk pengecekan role di view
  - Custom 403 Forbidden page

- **Authentication & Authorization**
  - Multi-role login (admin/staff/manager bisa login dari shop)
  - Session management
  - Password hashing dengan bcrypt
  - Protected routes dengan filters

- **Audit Logging**
  - Log semua aktivitas penting
  - Tracking user actions
  - Timestamp untuk setiap log

## 🛠️ Teknologi yang Digunakan

### Backend
- **CodeIgniter 4** - PHP Framework
- **PHP 8.1+** - Programming Language
- **MySQL** - Database

### Frontend
- **Tailwind CSS** - CSS Framework
- **SweetAlert2** - Beautiful alert dialogs
- **JavaScript (Vanilla)** - Client-side scripting

### Third-Party Libraries
- **Midtrans PHP SDK** - Payment gateway integration
- **PhpSpreadsheet** - Excel export functionality

### Tools & Utilities
- **Composer** - PHP Dependency Manager
- **Git** - Version Control

## 📦 Persyaratan Sistem

- PHP >= 8.1
- MySQL >= 5.7 atau MariaDB >= 10.3
- Composer
- Web Server (Apache/Nginx)
- Extension PHP yang diperlukan:
  - `ext-intl`
  - `ext-mbstring`
  - `ext-curl` (untuk Midtrans)
  - `ext-gd` atau `ext-imagick` (untuk image processing)
  - `ext-zip` (untuk PhpSpreadsheet)

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Lapadde/E-Commerce-Website
cd e-commerce
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

```bash
# Copy file env.example menjadi .env
cp env.example .env

# Edit file .env dan sesuaikan konfigurasi
# - Database credentials
# - Midtrans credentials
# - Base URL (opsional, akan auto-detect jika dikosongkan)
```

### 4. Setup Database

```bash
# Buat database baru
mysql -u root -p
CREATE DATABASE e_commerce CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
EXIT;

# Jalankan migration
php spark migrate

# (Opsional) Jalankan seeder untuk data dummy
php spark db:seed DatabaseSeeder
```

### 5. Setup Folder Permissions

Pastikan folder berikut memiliki permission write:
- `writable/`
- `public/uploads/`

**Linux/Mac:**
```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

**Windows:**
Folder sudah otomatis writable, tidak perlu setup khusus.

### 6. Konfigurasi Web Server

#### Apache
Pastikan mod_rewrite diaktifkan dan file `.htaccess` di folder `public/` sudah ada.

#### Nginx
Tambahkan konfigurasi berikut:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 7. Akses Aplikasi

- **Frontend:** `http://localhost/e-commerce/public/`
- **Admin Panel:** `http://localhost/e-commerce/public/admin/login`

## ⚙️ Konfigurasi

### Database Configuration

Edit file `.env`:

```env
database.default.hostname = localhost
database.default.database = e_commerce
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### Midtrans Configuration

1. Daftar di [Midtrans](https://dashboard.midtrans.com/)
2. Dapatkan Server Key dan Client Key
3. Edit file `.env`:

```env
MIDTRANS_SERVER_KEY = 'SB-Mid-server-xxxxxxxxxxxxx'
MIDTRANS_CLIENT_KEY = 'SB-Mid-client-xxxxxxxxxxxxx'
MIDTRANS_IS_PRODUCTION = false  # true untuk production
```

**Catatan:**
- Gunakan Sandbox keys untuk development (`MIDTRANS_IS_PRODUCTION = false`)
- Gunakan Production keys untuk production (`MIDTRANS_IS_PRODUCTION = true`)

### Base URL Configuration

Base URL akan otomatis terdeteksi dari server. Jika ingin manual, edit file `.env`:

```env
app.baseURL = 'https://yourdomain.com/'
```

### Environment

Edit file `.env`:

```env
CI_ENVIRONMENT = development  # development atau production
```

## 📁 Struktur Project

```
e-commerce/
├── app/
│   ├── Config/          # Konfigurasi aplikasi
│   ├── Controllers/      # Controller files
│   │   ├── Admin/       # Admin controllers
│   │   └── ...          # Customer controllers
│   ├── Database/
│   │   ├── Migrations/  # Database migrations
│   │   └── Seeds/       # Database seeders
│   ├── Filters/         # Route filters
│   ├── Helpers/         # Helper functions
│   ├── Models/          # Database models
│   └── Views/           # View templates
│       ├── admin/       # Admin views
│       ├── shop/        # Shop views
│       └── layouts/     # Layout templates
├── public/              # Public assets
│   ├── index.php       # Entry point
│   └── uploads/        # Uploaded files
├── system/             # CodeIgniter core
├── vendor/             # Composer dependencies
├── writable/           # Writable directories
├── .env                # Environment configuration
├── composer.json       # Composer dependencies
└── README.md           # This file
```

## 📖 Penggunaan

### Customer (Frontend)

1. **Registrasi/Login**
   - Akses `/register` untuk registrasi
   - Akses `/login` untuk login
   - Admin/Manager/Staff juga bisa login dari halaman shop

2. **Berbelanja**
   - Browse produk di beranda
   - Gunakan search untuk mencari produk
   - Filter berdasarkan kategori
   - Klik produk untuk melihat detail

3. **Keranjang & Checkout**
   - Tambah produk ke keranjang
   - Akses `/cart` untuk melihat keranjang
   - Update jumlah atau hapus produk
   - Klik "Checkout" untuk proses checkout
   - Isi alamat pengiriman
   - Pilih metode pembayaran di Midtrans
   - Selesaikan pembayaran

4. **Pesanan**
   - Akses `/orders` untuk melihat riwayat pesanan
   - Klik pesanan untuk melihat detail

5. **Profil**
   - Akses `/profile` untuk mengelola profil
   - Update data pribadi
   - Ubah password

### Admin Panel

1. **Login Admin**
   - Akses `/admin/login`
   - Login dengan credentials admin/manager/staff

2. **Dashboard**
   - Lihat statistik dan ringkasan
   - Monitor pesanan terbaru
   - Cek produk dengan stok rendah

3. **Manajemen Produk**
   - Tambah, edit, hapus produk
   - Upload gambar produk
   - Kelola stok
   - Assign kategori ke produk

4. **Manajemen Kategori**
   - Tambah, edit, hapus kategori
   - Upload gambar kategori

5. **Manajemen Pesanan**
   - Lihat semua pesanan
   - Update status pesanan
   - Update status pembayaran

6. **Manajemen Customer**
   - Lihat daftar customer
   - Tambah, edit, hapus customer

7. **Manajemen User (Admin Only)**
   - Kelola user admin/manager/staff
   - Assign role ke user

8. **Manajemen Role (Admin Only)**
   - Kelola role sistem

9. **Laporan (Admin & Manager Only)**
   - Lihat laporan penjualan
   - Export ke Excel

10. **Activity Log (Admin Only)**
    - Monitor aktivitas sistem
    - Filter dan search log

## 👥 Role dan Akses

### Admin
- Akses penuh ke semua fitur
- Manajemen user dan role
- Lihat activity log
- Lihat laporan penjualan

### Manager
- Manajemen produk, kategori, pesanan, customer
- Lihat laporan penjualan
- **Tidak bisa** mengelola user dan role
- **Tidak bisa** melihat activity log

### Staff
- Manajemen produk, kategori, pesanan, customer
- **Tidak bisa** melihat laporan penjualan
- **Tidak bisa** mengelola user dan role
- **Tidak bisa** melihat activity log

### Customer
- Akses frontend shop
- Berbelanja, checkout, pembayaran
- Lihat riwayat pesanan
- Kelola profil

## 🗄️ Database Migration

### Menjalankan Migration

```bash
# Jalankan semua migration
php spark migrate

# Rollback migration terakhir
php spark migrate:rollback

# Refresh migration (rollback semua lalu migrate lagi)
php spark migrate:refresh

# Refresh dengan seed
php spark migrate:refresh --seed
```

### Migration Files

- `2024-01-01-000001_CreateUsersTable.php` - Tabel users
- `2024-01-01-000002_CreateRolesTable.php` - Tabel roles
- `2024-01-01-000003_CreateUserRolesTable.php` - Tabel user_roles
- `2024-01-01-000004_CreateCategoriesTable.php` - Tabel categories
- `2024-01-01-000005_CreateProductsTable.php` - Tabel products
- `2024-01-01-000006_CreateProductCategoriesTable.php` - Tabel product_categories
- `2024-01-01-000007_CreateOrdersTable.php` - Tabel orders
- `2024-01-01-000008_CreateOrderDetailsTable.php` - Tabel order_details
- `2024-01-01-000009_CreateAuditLogsTable.php` - Tabel audit_logs

## 🔧 Troubleshooting

### Error: "Base URL not set"
- Base URL akan otomatis terdeteksi. Jika masih error, set manual di `.env`:
  ```env
  app.baseURL = 'http://localhost/e-commerce/public/'
  ```

### Error: "Midtrans configuration not found"
- Pastikan `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` sudah di-set di `.env`
- Pastikan helper `midtrans_helper.php` sudah ada di `app/Helpers/`

### Error: "Permission denied" pada upload
- Pastikan folder `public/uploads/` dan `writable/` memiliki permission write
- Linux/Mac: `chmod -R 755 public/uploads/ writable/`

### Error: "Not unique table/alias"
- Pastikan query builder di-reset dengan benar
- Gunakan `\Config\Database::connect()->table()` untuk builder baru

### Cart tidak dikosongkan setelah pembayaran
- Pastikan method `clearCart()` dipanggil di `PaymentController::updateOrderToPaid()`
- Pastikan session customer_id sudah benar

### Error: "Undefined type 'Midtrans\Exception'"
- Midtrans SDK mungkin tidak memiliki class Exception khusus
- Gunakan `\Exception` dengan pengecekan error message


## 📝 Lisensi

Project ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.

## 👤 Author

- **Tpk** - [GitHub](https://github.com/Lapadde)
- **Me On Insta** - [Instagram](https://instagram.com/taaufiik25)

## 🙏 Acknowledgments

- CodeIgniter 4 Framework
- Midtrans untuk payment gateway
- Tailwind CSS untuk styling
- SweetAlert2 untuk beautiful alerts
- PhpSpreadsheet untuk Excel export

---

**Note:** Pastikan untuk mengubah credentials default sebelum deploy ke production!
