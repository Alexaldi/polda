# Specify: Penyesuaian Tampilan Detail Page Pelaporan
path: resources\views\pages\pelaporan\show.blade.php

## Goal
1. Fokus di penyesuaian html tampilannya. 
2. Penyesuaian backend nanti saja.

## Detail
Di HTML ini ada 3 tab:
1. Detail Laporan
2. Update Progress
3. Timeline
   
### Specify: Detail Laporan
Di tab Detail Laporan ada 3 section data:
1. Data Identitas Pelapor
   - Nama Pelapor
   - Alamat Pelapor
   - No Telepon Pelapor
2. Data Laporan
   - Judul Laporan
   - Kategori Laporan
   - Kronologi
   - Tanggal
   - Kota
3. Data Identitas Terlapor (Berupa Table seperti di page create)
   - Nama Terlapor
   - Alamat Terlapor
   - No Telepon Terlapor
   - Jenis Satuan [Satker, Satwil]
   Jika Pilih Satker maka akan muncul:
   - Dropdown Satker
   Jika Pilih Satwil maka akan muncul:
   - Dropdown Satwil
  
### Specify: Update Progress
Di tab Update Progress ada 2 section data:
1. Upload Document Pemeriksaan
   - No Dokumen Pemeriksaan
   - Tanggal Dokumen Pemeriksaan
   - Upload File
2. Input Kesimpulan Gelar Perkara (Textarea)

## Specify: Timeline
Di tab Timeline ada 1 section data:
1. Timeline Penanganan (Existing)

## Notes
- Setiap Section Data dipisahkan dengan line break
- Data Identitas Terlapor bisa lebih dari 1
- Gunakan Table untuk menampilkan data identitas terlapor