# Dokumentasi Perubahan: Upload Dokumentasi ke Google Drive Dosen

## Ringkasan Perubahan
Sistem upload dokumentasi telah diubah sehingga file yang diupload oleh mahasiswa akan langsung masuk ke Google Drive folder milik **dosen pengampu** (ketua penelitian/pengabdian), bukan ke folder umum. Ini membuat dokumentasi lebih terorganisir dan mudah diakses dosen.

## Alur Kerja Baru

1. **Dosen Login & Konfigurasi Google Drive**
   - Dosen login ke sistem dengan akun Google
   - Dosen mengatur/mengisi **Google Drive Folder ID** di profile mereka
   - ID ini disimpan di database untuk setiap dosen

2. **Mahasiswa Upload File**
   - Mahasiswa memilih penelitian/pengabdian untuk upload dokumentasi
   - Mahasiswa upload file (foto/video) melalui form
   - Sistem otomatis mengambil folder ID dari dosen pengampu
   - File langsung di-upload ke Google Drive folder dosen

3. **Validasi & Error Handling**
   - Jika dosen belum mengatur Google Drive folder, mahasiswa akan mendapat pesan error
   - Mahasiswa diminta menghubungi dosen untuk konfigurasi

## Perubahan File

### 1. Database Migration
**File**: `database/migrations/2026_01_20_100000_add_google_drive_folder_id_to_dosens_table.php`

Menambahkan kolom baru di tabel `dosens`:
```
google_drive_folder_id (nullable string)
```

### 2. Model Dosen
**File**: `app/Models/Dosen.php`

Menambahkan `google_drive_folder_id` ke dalam `$fillable` array.

### 3. Controller MahasiswaController
**File**: `app/Http/Controllers/MahasiswaController.php`

#### a. Method `uploadToGoogleDrive()`
- Parameter baru: `$folderId = null`
- Jika `$folderId` diberikan, gunakan folder dosen
- Jika tidak, gunakan default folder dari `.env`

#### b. Method `storeDokumentasi()`
- Mengambil dosen dari `$penelitian->dosen`
- Validasi apakah dosen sudah set `google_drive_folder_id`
- Pass folder ID ke method `uploadToGoogleDrive()`
- Pesan sukses: "✅ Dokumentasi berhasil diupload ke Google Drive dosen!"

#### c. Method `storeDokumentasiPengabdian()`
- Mengambil dosen dari `$pengabdian->ketuaDosen`
- Logika sama dengan `storeDokumentasi()`
- Pesan sukses: "✅ Dokumentasi Pengabdian berhasil diupload ke Google Drive dosen!"

## Implementasi Steps

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Dosen Konfigurasi Google Drive (Opsional - Future)
Admin perlu membuat form/halaman untuk dosen bisa mengisi Google Drive Folder ID mereka.

Contoh script untuk test:
```php
// Update dosen's Google Drive folder ID
$dosen = Dosen::find(1);
$dosen->update([
    'google_drive_folder_id' => 'FOLDER_ID_DARI_GOOGLE_DRIVE'
]);
```

### 3. Testing
- Pastikan dosen sudah set `google_drive_folder_id`
- Mahasiswa upload file → seharusnya masuk ke folder dosen

## Error Handling

Jika dosen belum set folder ID:
```
Error: "Dosen pengampu belum mengatur Google Drive folder. Hubungi dosen Anda."
```

Jika ada error upload:
```
Error: "Gagal mengupload ke Google Drive. Error: [pesan error]"
```

## Benefits

✅ File dokumentasi tersentralisasi per dosen
✅ Lebih mudah dosen mengakses dokumentasi mahasiswa mereka
✅ Struktur folder lebih rapi dan terorganisir
✅ Mahasiswa tidak perlu login ke Google Drive terpisah

## TODO (Future Enhancement)

- [ ] Buat halaman admin/dosen untuk setup Google Drive Folder ID
- [ ] Auto-create folder di Google Drive saat dosen login pertama kali
- [ ] Tambah permission management untuk dosen lain yang terlibat
