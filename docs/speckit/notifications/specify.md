# Fitur Notifikasi — Saran Trigger & Lokasi Implementasi

## Ringkasan
- Tujuan: memberi tahu pihak terkait saat ada perubahan penting dalam siklus hidup laporan.
- Pendekatan: implementasi di level Service dengan Event/Notification agar konsisten, testable, dan tidak menambah kompleksitas di Controller.
- Channel: in-app (database), email (opsional), push (opsional, tergantung integrasi).

## Daftar Trigger Utama
- Laporan dibuat (SUBMITTED)
  - Deskripsi: saat `Report` pertama kali dibuat, sistem otomatis menambah journey `SUBMITTED`. Cocok untuk notifikasi kepada pelapor dan petugas penerima.

- Perubahan status laporan
  - Deskripsi: perubahan ke `PEMERIKSAAN`, `LIMPAH/TRANSFER`, `SIDANG`, `SELESAI/COMPLETED` memicu notifikasi sesuai state baru.

## Target Penerima Notifikasi
- Pelapor (creator laporan) — progres & status.
- Kasubbid — perubahan signifikan (SELESAI/COMPLETED, perubahan kategori/izin).

## Pesan Notifikasi (Template)
- Pelapor:
  - Status SUBMITTED: `Laporan [{code}] {title} telah diterima oleh sistem.`
  - Status PEMERIKSAAN: `Laporan [{code}] {title} sedang diperiksa oleh {institution_name}/{division_name}.`
  - Status TRANSFER: `Laporan [{code}] {title} dilimpahkan ke {institution_target_name}/{division_target_name}.`
  - Status SIDANG: `Laporan [{code}] {title} memasuki tahap sidang pada {hearing_date}.`
  - Status SELESAI: `Laporan [{code}] {title} selesai pada {finish_time}. Terima kasih atas partisipasi Anda.`
  - Bukti ditambahkan: `Bukti baru ditambahkan pada laporan [{code}] {title}: {file_names}.`
  - Data laporan diperbarui: `Data laporan [{code}] {title} diperbarui: {changed_fields}.`

- Kasubbid:
  - Status PEMERIKSAAN: `Laporan [{code}] {title} memasuki tahap pemeriksaan. PIC: {division_name}.`
  - Status TRANSFER (diterima): `Laporan [{code}] {title} diterima dari {institution_source_name}/{division_source_name}.`
  - Status TRANSFER (keluar): `Laporan [{code}] {title} dilimpahkan ke {institution_target_name}/{division_target_name}.`
  - Status SIDANG: `Laporan [{code}] {title} dijadwalkan sidang pada {hearing_date}. Mohon persiapan berkas.`
  - Status SELESAI: `Laporan [{code}] {title} telah selesai pada {finish_time}. Tindak lanjut: {follow_up_note}.`
  - Bukti ditambahkan oleh tim: `Bukti baru diunggah untuk laporan [{code}] {title}: {file_names}.`
  - Perubahan data penting: `Perubahan data pada laporan [{code}] {title}: {changed_fields}.`
