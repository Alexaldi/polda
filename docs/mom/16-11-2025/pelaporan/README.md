# Revisi Ke-1 - Fitur Pelaporan

## Ringkasan
Dokumen ini berisi revisi pertama untuk fitur pelaporan sistem, mencakup perubahan pada antarmuka halaman pembuatan laporan.

## Perubahan Halaman Create Laporan

### 1. Data Identitas Pelapor
Bagian ini mengumpulkan informasi identitas dari pelapor:

- **Nama Pelapor**: Nama lengkap orang yang membuat laporan
- **Alamat Pelapor**: Alamat tempat tinggal pelapor
- **No Telephone Pelapor**: Nomor telepon yang dapat dihubungi

### 2. Data Laporan
Informasi detail tentang laporan yang dibuat:

- **Lokasi**:
  - Hilangkan opsi Provinsi (auto-select Jawa Barat)
  - Sistem akan otomatis menyetel provinsi ke Jawa Barat

- **Deskripsi**:
  - Ubah label "Deskripsi" menjadi "Kronologi"
  - Bagian ini berisi urutan kejadian secara kronologis

### 3. Data Identitas Terlapor
Bagian ini dapat menampung data identitas dari beberapa terlapor (multiple entries):

Untuk setiap terlapor, mencakup:
- **Nama**: Nama lengkap terlapur
- **Jenis Satuan**: Kategori/tipe satuan kerja
- **Unitnya**: Unit kerja spesifik dari terlapor

---

## Catatan Implementasi
- Fitur multiple terlapur memungkinkan satu laporan memiliki beberapa terlapur
- Auto-select Jawa Barat untuk menyederhanakan proses input lokasi
- Perubahan terminologi dari "Deskripsi" ke "Kronologi" untuk lebih mencerminkan konteks pelaporan

## Catatan Alur Data
- **reports**: Menyimpan data laporan utama
- **suspects**: Menyimpan data identitas terlapor
- **report_journeys**: Menyimpan urutan kejadian laporan
- **access_datas**: Menyimpan data akses (is_finish = false)
