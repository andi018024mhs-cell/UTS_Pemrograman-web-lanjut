# 🎓 SISTEM CRUD MAHASISWA - CodeIgniter 4 dengan AJAX jQuery

Sistem manajemen data mahasiswa lengkap menggunakan CodeIgniter 4 dengan konsep MVC murni, AJAX jQuery, dan Bootstrap 5.

## 📋 Fitur Utama

✅ **Tampil Data** - Menampilkan semua data mahasiswa secara otomatis saat page load  
✅ **Tambah Data** - Tambah data baru menggunakan Modal Bootstrap tanpa reload  
✅ **Edit Data** - Edit data existing dengan pre-fill form dari database  
✅ **Hapus Data** - Hapus data dengan konfirmasi untuk mencegah kesalahan  
✅ **Validasi** - Validasi client-side dan server-side  
✅ **Alert Notifikasi** - Alert dinamis untuk feedback user  
✅ **Responsive UI** - Bootstrap 5 untuk tampilan yang responsif  

## 🏗️ Struktur Direktori & File

```
CRUD-M/
├── app/
│   ├── Config/
│   │   └── Routes.php (UPDATE)         # Route configuration
│   ├── Controllers/
│   │   └── Mahasiswa.php (NEW)         # Controller CRUD
│   ├── Models/
│   │   └── MahasiswaModel.php (NEW)    # Model database
│   └── Views/
│       └── mahasiswa_view.php (NEW)    # Frontend view
├── DATABASE_SCHEMA.sql (NEW)           # SQL untuk database
├── DOKUMENTASI_AJAX_ALUR.md (NEW)     # Dokumentasi lengkap
└── README.md                           # File ini
```

## 🗄️ Setup Database

### 1. Buat Database

```bash
# Buka MySQL/MariaDB client
mysql -u root -p

# Buat database
CREATE DATABASE IF NOT EXISTS db_kampus;
USE db_kampus;

# Import SQL schema
source DATABASE_SCHEMA.sql;
```

**Atau** copy-paste script dari file `DATABASE_SCHEMA.sql` ke MySQL client.

### 2. Update Konfigurasi Database di CI4

Edit file `app/Config/Database.php`:

```php
public $default = [
    'DSN'      => '',
    'hostname' => 'localhost',
    'username' => 'root',        // Sesuaikan dengan user MySQL Anda
    'password' => '',            // Sesuaikan dengan password MySQL
    'database' => 'db_kampus',   // Harus sama dengan nama database
    'DBDriver' => 'MySQLi',
    'DBPrefix' => '',
    'pConnect' => false,
    'DBDebug'  => (ENVIRONMENT !== 'production'),
    'charset'  => 'utf8mb4',
    'DBCollat' => 'utf8mb4_unicode_ci',
    'swapPre'  => '',
    'encrypt'  => false,
    'compress' => false,
    'strictOn' => false,
    'failover' => [],
    'port'     => 3306,
];
```

## 🚀 Setup & Running

### 1. Pastikan CodeIgniter 4 sudah terinstall

```bash
# Jika belum, install via Composer
composer create-project codeigniter4/appstarter crud-mahasiswa
cd crud-mahasiswa
```

### 2. Copy file-file yang sudah dibuat

- Copy `MahasiswaModel.php` ke `app/Models/`
- Copy `Mahasiswa.php` ke `app/Controllers/`
- Copy `mahasiswa_view.php` ke `app/Views/`
- Update `app/Config/Routes.php` dengan route baru

### 3. Jalankan Development Server

```bash
# Di terminal, masuk ke root project
cd C:\uts\CRUD-M

# Jalankan Spark (built-in server CI4)
php spark serve

# Output:
# Starting CodeIgniter development server
# Server running at http://localhost:8080/
```

### 4. Buka di Browser

```
http://localhost:8080/mahasiswa
```

Selesai! Sistem CRUD sudah siap digunakan.

## 💻 Panduan Penggunaan

### Tampil Data

1. Buka halaman `http://localhost:8080/mahasiswa`
2. Data mahasiswa akan dimuat otomatis saat page load
3. Tabel menampilkan: No, NIM, Nama, Jurusan, dan Tombol Aksi

### Tambah Data

1. Klik tombol **"+ Tambah Data"**
2. Modal form akan terbuka
3. Isi field:
   - **NIM**: Nomor induk (8-12 karakter)
   - **Nama**: Nama lengkap (min 3 karakter)
   - **Jurusan**: Pilih dari dropdown
4. Klik **"Simpan"**
5. Jika ada error, pesan akan ditampilkan di bawah form
6. Jika berhasil, modal akan tertutup dan table auto-refresh

### Edit Data

1. Di tabel, klik tombol **Edit** (icon pensil) pada baris yang ingin diedit
2. Modal akan terbuka dan form terisi dengan data existing
3. Ubah field yang perlu diubah
4. Klik **"Update"**
5. Jika berhasil, table akan otomatis refresh

### Hapus Data

1. Di tabel, klik tombol **Hapus** (icon trash) pada baris yang ingin dihapus
2. Modal konfirmasi akan muncul
3. Baca konfirmasi dengan seksama
4. Klik **"Ya, Hapus Data"** untuk confirm, atau **"Batal"** untuk cancel
5. Jika berhasil, data akan hilang dari table

## 📝 Penjelasan Code

### Controller - Mahasiswa.php

#### Method `index()`
- Menampilkan view halaman utama
- Route: `GET /mahasiswa`

#### Method `getData()`
- Mengambil semua data mahasiswa
- Route: `GET /mahasiswa/getData`
- Return: JSON array data

#### Method `store()`
- Insert data mahasiswa baru
- Route: `POST /mahasiswa/store`
- Validasi server-side
- Return: JSON {status, message}

#### Method `show($id)`
- Mengambil data mahasiswa berdasarkan ID (untuk edit)
- Route: `GET /mahasiswa/show/{id}`
- Return: JSON data single record

#### Method `update($id)`
- Update data mahasiswa
- Route: `POST /mahasiswa/update`
- Validasi server-side
- Return: JSON {status, message}

#### Method `delete($id)`
- Hapus data mahasiswa
- Route: `POST /mahasiswa/delete`
- Return: JSON {status, message}

### Model - MahasiswaModel.php

#### Property
```php
protected $table = 'mahasiswa';           // Nama tabel
protected $primaryKey = 'id';             // Primary key
protected $returnType = 'array';          // Return type
protected $allowedFields = ['nim', 'nama', 'jurusan'];  // Mass assignment
protected $useTimestamps = true;          // Auto timestamp
```

#### Validation Rules
```php
protected $validationRules = [
    'nim' => 'required|string|min_length[8]|max_length[12]|is_unique[mahasiswa.nim]',
    'nama' => 'required|string|min_length[3]|max_length[100]',
    'jurusan' => 'required|string',
];
```

#### Method Utility
- `getMahasiswa()` - Ambil semua data dengan pagination
- `getMahasiswaById($id)` - Ambil satu data
- `tambahMahasiswa($data)` - Insert data
- `updateMahasiswa($id, $data)` - Update data
- `hapusMahasiswa($id)` - Delete data

### Frontend - mahasiswa_view.php

#### JavaScript Functions

**`loadData()`**
- AJAX GET request ke `/mahasiswa/getData`
- Render table dengan data
- Dipanggil saat page load

**`simpanData()`**
- Ambil data form
- Validasi client-side
- AJAX POST ke `/mahasiswa/store` (insert) atau `/mahasiswa/update` (update)

**`editData(id)`**
- AJAX GET ke `/mahasiswa/show/{id}`
- Isi form dengan data existing
- Buka modal

**`hapusData(id)`**
- Tampilkan modal konfirmasi
- AJAX POST ke `/mahasiswa/delete`

**`resetForm()`**
- Reset form ke keadaan awal
- Clear error messages
- Ubah modal title menjadi "Tambah"

## 🔍 Alur Request-Response AJAX

Lihat file `DOKUMENTASI_AJAX_ALUR.md` untuk penjelasan lengkap dengan diagram flow.

### Quick Summary:

| Operasi | Method | URL | Request | Response |
|---------|--------|-----|---------|----------|
| **Tampil** | GET | `/mahasiswa/getData` | - | `{status, data:[]}` |
| **Tambah** | POST | `/mahasiswa/store` | `{nim, nama, jurusan}` | `{status, message}` |
| **Ambil Edit** | GET | `/mahasiswa/show/{id}` | - | `{status, data:{}}` |
| **Update** | POST | `/mahasiswa/update` | `{id, nim, nama, jurusan}` | `{status, message}` |
| **Hapus** | POST | `/mahasiswa/delete` | `{id}` | `{status, message}` |

## ⚙️ Konfigurasi Penting

### 1. Routes (app/Config/Routes.php)

```php
$routes->resource('mahasiswa', [
    'controller' => 'Mahasiswa',
    'only' => ['index']
]);
```

### 2. Database Config (app/Config/Database.php)

Pastikan `hostname`, `username`, `password`, dan `database` sudah sesuai.

### 3. CSRF Protection (Jika diaktifkan)

Jika CI4 CSRF protection aktif, tambahkan CSRF token di AJAX:

```javascript
$.ajax({
    url: BASE_URL + '/store',
    type: 'POST',
    data: {
        [csrf_token_name]: csrf_hash,
        nim: nim,
        nama: nama,
        jurusan: jurusan
    }
});
```

## 🐛 Troubleshooting

### Masalah: Database tidak terkoneksi
**Solusi:**
- Pastikan MySQL/MariaDB running
- Check file `app/Config/Database.php`
- Pastikan database `db_kampus` sudah dibuat
- Pastikan user dan password MySQL benar

### Masalah: 404 Not Found saat akses `/mahasiswa`
**Solusi:**
- Check `app/Config/Routes.php` sudah update
- Clear browser cache (Ctrl+Shift+Delete)
- Restart development server

### Masalah: AJAX request error
**Solusi:**
- Buka DevTools (F12) → Console tab
- Cek error message
- Buka Network tab untuk lihat request/response
- Pastikan Controller method ada

### Masalah: Data tidak muncul di table
**Solusi:**
- Check di MySQL apakah ada data di tabel `mahasiswa`
- Cek Console browser untuk error JavaScript
- Cek Network tab di DevTools untuk response AJAX

## 📚 Database Schema

```sql
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(12) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Field:**
- `id`: Identitas unik (auto increment)
- `nim`: Nomor Induk Mahasiswa (unique, required)
- `nama`: Nama lengkap (required)
- `jurusan`: Program studi (required)
- `created_at`: Tanggal dibuat
- `updated_at`: Tanggal diupdate

## 🎨 Fitur UI/UX

✨ **Bootstrap 5** - Responsive framework  
✨ **Font Awesome Icons** - Icon library  
✨ **Modal Bootstrap** - Dialog form  
✨ **Alert Notifications** - Feedback user  
✨ **Loading Spinner** - Indikator loading  
✨ **Smooth Animations** - Fade-in effects  
✨ **Form Validation Errors** - Highlight invalid fields  
✨ **Confirmation Dialog** - Prevent accidental delete  

## 📦 Dependencies

- **PHP 7.4+** (atau versi lebih tinggi)
- **MySQL/MariaDB** 5.7+ atau 10.3+
- **CodeIgniter 4** latest version
- **jQuery 3.6+** (via CDN)
- **Bootstrap 5** (via CDN)
- **Font Awesome 6+** (via CDN)

## 🔐 Security Considerations

1. **Input Validation** - Validasi di server-side (Model)
2. **SQL Injection Prevention** - Query Builder CI4 melindungi
3. **XSS Prevention** - Escape output di view
4. **CSRF Protection** - Bisa diaktifkan di CI4 config
5. **Authentication** - Tambahkan auth sesuai kebutuhan

## 📝 Validasi Rules

### NIM
- Required
- String
- Min length: 8 karakter
- Max length: 12 karakter
- Unique (tidak boleh ada duplikat)

### Nama
- Required
- String
- Min length: 3 karakter
- Max length: 100 karakter

### Jurusan
- Required
- String
- Min length: 3 karakter
- Max length: 50 karakter

## 🚀 Pengembangan Lebih Lanjut

Ide untuk extend sistem:

1. **Authentication** - Login system untuk mahasiswa/admin
2. **Export Data** - Export ke CSV/Excel
3. **Search & Filter** - Cari mahasiswa berdasarkan kriteria
4. **Pagination** - Handle data besar dengan pagination
5. **Image Upload** - Tambahkan foto mahasiswa
6. **API Documentation** - Dokumentasi API lengkap
7. **Unit Testing** - Test cases untuk functions
8. **Dark Mode** - Toggle tema gelap

## 📞 Support & Contact

Jika ada pertanyaan atau bug report:
1. Cek error di Console browser (F12)
2. Lihat Network tab untuk request/response
3. Check file `DOKUMENTASI_AJAX_ALUR.md` untuk penjelasan

## 📄 License

MIT License - Bebas digunakan untuk project personal maupun komersial

---

**Dibuat dengan ❤️ menggunakan CodeIgniter 4**

Enjoy! 🎉
