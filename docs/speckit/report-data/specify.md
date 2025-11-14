# Fitur "Report Data" — Spesifikasi Teknis

## Ringkasan
- Fitur ini menampilkan daftar data laporan dengan Filter & Advanced Search serta ekspor ke Excel dan PDF.
- Fungsionalitas mirip halaman `Pelaporan`, tetapi dengan opsi filter yang lebih lengkap dan output ekspor.
- Mengikuti pola Service–Repository, Form Request untuk validasi, dan Resource untuk response JSON.

## Rute & Navigasi
- Menu: `Laporan > Report Data` (tambahkan di `resources/views/components/sidebar.blade.php`).
- Halaman utama: `GET /report-data` → menampilkan filter form + tabel.
- DataTables: `GET /datatables/report-data` → server-side data.
- Ekspor Excel: `GET /report-data/export/excel` → unduh `.xlsx`.
- Ekspor PDF: `GET /report-data/export/pdf` → unduh `.pdf`.

Referensi pola DataTables existing: `app/Http/Controllers/PelaporanController.php:50`.

## Advanced Filter — Item & Aturan
Parameter filter disesuaikan dengan data di atas:
- `q` — full-text sederhana untuk `title`, `description`, `code` (LIKE `%q%`).
- `status` — salah satu dari: `SUBMITTED`, `PEMERIKSAAN`, `LIMPAH`, `SIDANG`, `SELESAI` (`app/Repositories/DashboardRepository.php:7`).
- `category_id` — integer, wajib ada pada `report_categories`.
- `province_id`, `city_id`, `district_id` — integer; chainable, jika `province_id` diisi maka opsi `city` diambil dari province terkait (lihat helper di `PelaporanController` untuk cities/districts: `app/Http/Controllers/PelaporanController.php:134`).
- `incident_from`, `incident_to` — rentang tanggal/waktu untuk `incident_datetime`.
- `created_from`, `created_to` — rentang untuk `created_at`.
- `finish_from`, `finish_to` — rentang untuk `finish_time`.
- `sort_by` dan `sort_dir` — default `created_at` `desc`; validasi `sort_by` hanya ke kolom yang diizinkan.
- Semua parameter opsional; jika kosong, tidak diterapkan.

Catatan implementasi:
- Query builder memanfaatkan `whereBetween` untuk rentang tanggal; normalisasi ke awal/akhir hari ketika input tanpa waktu.
- Relasi nama kategori dan wilayah dapat diambil via `with(['category','province','city','district'])` untuk menghindari N+1.

## Ekspor Laporan
- Filter yang digunakan pada ekspor harus identik dengan filter DataTables (gunakan Service yang sama).

### Ekspor Excel
- Endpoint: `GET /report-data/export/excel` dengan semua parameter filter yang sama.
- Rekomendasi package: `maatwebsite/excel` (belum ada di project, perlu instalasi terlebih dahulu).
- Format kolom (disarankan):
  - `No`, `Kode`, `Judul`, `Kategori`, `Status`, `Tanggal Kejadian`, `Provinsi`, `Kota/Kabupaten`, `Kecamatan`, `Dibuat`, `Selesai`.
- File name: `report-data-YYYYMMDD-HHmm.xlsx`.

### Ekspor PDF
- Endpoint: `GET /report-data/export/pdf` dengan semua parameter filter yang sama.
- Rekomendasi package: `barryvdh/laravel-dompdf` (belum ada di project, perlu instalasi terlebih dahulu).
- Template Blade khusus PDF (tabel ringkas, header berisi rentang filter yang dipakai).
- File name: `report-data-YYYYMMDD-HHmm.pdf`.

## Validasi Input (Form Request)
- Berkas: `app/Http/Requests/ReportDataFilterRequest.php`.
- Aturan utama:
  - `q` string max 255.
  - `status` in: `SUBMITTED,PEMERIKSAAN,LIMPAH,SIDANG,SELESAI`.
  - `category_id`, `province_id`, `city_id`, `district_id` integer `exists` di tabel terkait.
  - Rentang tanggal: `incident_from/to`, `created_from/to`, `finish_from/to` bertipe `date`; validasi `from <= to`.
  - `sort_by` in: `created_at,incident_datetime,finish_time,status,code,title`.
  - `sort_dir` in: `asc,desc`.

## View & UX
- Komponen:
  - Form Filter (inline atau collapsible) berisi item di bagian Advanced Filter.
  - Tabel DataTables (server-side) mengikuti styling Pelaporan.
  - Tombol `Export Excel` dan `Export PDF` yang melampirkan parameter filter saat ini.
  - Dependent dropdown untuk lokasi: ambil cities/districts mengikuti pola di Pelaporan (`get-cities`, `get-districts`).

## Catatan Implementasi
- Reuse pola DataTables di Pelaporan untuk konsistensi perilaku user.
- Filter builder berada di Service agar satu sumber kebenaran dipakai bersama oleh tabel dan ekspor.
- Library Excel/PDF belum ada di project; dokumentasi ini mengasumsikan instalasi tambahan sebelum implementasi ekspor.