# User Journey - Sistem Pelaporan Kepolisian

## Overview
Dokumen ini menggambarkan alur perjalanan pengguna (user journey) dalam sistem pelaporan kepolisian, mencakup dua flow utama: **Pemeriksaan** dan **Limpah**.

---

## Flow 1: Pemeriksaan

### Divisi yang Terlibat
- **Subbid Pamina (POLDA)** - Tingkat Kepolisian Daerah
- **Unit Paminal (POLRES)** - Tingkat Kepolisian Resor

### Alur Proses Pemeriksaan
User menginputkan data pemeriksaan berikut:

| Field | Deskripsi | Required |
|-------|-----------|----------|
| **No Dokumen Pemeriksaan** | Nomor unik dokumen pemeriksaan | ✅ |
| **Tanggal Dokumen Pemeriksaan** | Tanggal pembuatan dokumen | ✅ |
| **Upload File** | File dokumen pemeriksaan (PDF/DOC) | ✅ |
| **Kesimpulan Gelar Perkara** | Hasil analisis dan kesimpulan | ✅ |

#### Step 3: Keputusan Akhir Pemeriksaan
Setelah menginput data, user memilih salah satu opsi:

##### Opsi 1: Simpan dan Selesai
- **Artinya**: Terlapur dinyatakan **TIDAK BERSALAH**
- **Aksi**:
  - Status laporan berubah menjadi "SELESAI"
  - Journey create data baru dengan type SELESAI
- **Flow End**: Laporan ditutup

##### Opsi 2: Simpan dan Limpah
- **Artinya**: Terlapor dinyatakan **BERSALAH**
- **Aksi**:
  - Data dilanjutkan ke tahap Administrasi Penyidikan
- **Flow Continue**: Lanjut ke Flow 2 (Limpah)

---

## Flow 2: Limpah (Administrasi Penyidikan)

### Divisi yang Terlibat
- **Subbid Provos (POLDA)** - Tingkat Kepolisian Daerah
- **Subbid Wabprof (POLDA)** - Tingkat Kepolisian Daerah
- **Unit Provos (POLRES)** - Tingkat Kepolisian Resor

### Alur Proses Limpah

#### Step 1: Administrasi Penyidikan
User dari unit terkait melakukan proses administrasi penyidikan dengan input:

##### Data Dokumen (Multiple Files)
| Field | Deskripsi | Required |
|-------|-----------|----------|
| **Nama Dokumen** | Jenis/nama dokumen | ✅ |
| **No Dokumen** | Nomor dokumen resmi | ✅ |
| **Tanggal Dokumen** | Tanggal pembuatan dokumen | ✅ |
| **File** | Upload file dokumen | ✅ |

**Note**: User dapat mengupload **banyak file** untuk mendukung proses penyidikan.

#### Step 2: Input Data Sidang
Setelah administrasi penyidikan, user melanjutkan dengan input data sidang:

| Field | Deskripsi | Required |
|-------|-----------|----------|
| **No Dokumen Sidang** | Nomor dokumen sidang | ✅ |
| **Tanggal Dokumen Sidang** | Tanggal sidang dilaksanakan | ✅ |
| **Upload File Sidang** | File dokumentasi sidang | ✅ |
| **Putusan** | Hasil keputusan sidang | ✅ |

---

## Catatan Alur Data dan Sistem

### Hubungan antar Flow
1. **Transisi dari Pemeriksaan ke Limpah**:
   - System membuat data baru di table `access_datas`
   - Update `access_datas.is_finish = true` berdasarkan `report_id` sebelumnya
   - Create entry baru di table `access_datas`

2. **Akses Langsung ke Flow Limpah**:
   - Laporan bisa langsung masuk ke tahap Limpah
   - Tergantung pada **divisi pembuat laporan awal**
   - Bypass flow Pemeriksaan jika dibuat oleh unit yang berwenang

## Status Laporan

| Status | Deskripsi | Next Flow |
|--------|-----------|-----------|
| **SUBMIT** | Laporan masuk, menunggu pemeriksaan | Pemeriksaan |
| **PEMERIKSAAN** | Sedang diproses oleh Paminal | Pemeriksaan |
| **SELESAI** | Terlapur tidak bersalah | END |
| **LIMPAH** | Terlapur bersalah, lanjut penyidikan | Limpah |
| **SIDANG** | Proses persidangan | Limpah |
| **SELESAI** | Putusan sidang final | END |