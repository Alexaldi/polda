## Specify: Penyesuaian Tampilan Create Page Pelaporan
path: resources\views\pages\pelaporan\create.blade.php

## Goal
1. Fokus di penyesuaian html tampilannya. 
2. Penyesuaian backend nanti saja.

## Detail
Di html ini ada 3 section data:
1. Data Identitas Pelapor
2. Data Laporan
3. Data Identitas Terlapor

# Specify: Data Identitas Pelapor
Section ini berisi data identitas pelapor.
Data identitas pelapor terdiri dari:
- Nama Pelapor
- Alamat Pelapor
- No Telepon Pelapor

# Specify: Data Laporan
Section ini berisi data laporan.
Data laporan terdiri dari:
- Judul Laporan
- Kategori Laporan
- Kronologi
- Tanggal
- Kota

# Specify: Data Identitas Terlapor
Section ini berisi data identitas terlapor.
Data identitas terlapor terdiri dari:
- Nama Terlapor
- Alamat Terlapor
- No Telepon Terlapor
- Jenis Satuan [Satker, Satwil]
Jika Pilih Satker maka akan muncul:
- Dropdown Satker
Jika Pilih Satwil maka akan muncul:
- Dropdown Satwil

## Notes
- Setiap Section Data di pisahkan dengan line break
- Data Identitas Terlapor bisa lebih dari 1
- Gunakan Table untuk menampilkan data identitas terlapor
- Gunakan modal untuk input data identitas terlapor
- Data di table identitas terlapor di handle oleh javascript