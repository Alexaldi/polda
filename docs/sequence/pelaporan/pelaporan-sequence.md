# Sequence Diagram: Fitur Pelaporan

Berdasar dokumen specify di `docs/speckit/pelaporan/specify.md`, berikut sequence diagram mermaid untuk alur penyimpanan data laporan, tersangka, journey, dan evidence.

```mermaid
sequenceDiagram
    autonumber
    actor User as Pengguna
    participant Web as Form Pelaporan (Blade)
    participant RC as ReportController
    participant R as Report (Model)
    participant S as Suspect (Model)
    participant RJ as ReportJourney (Model)
    participant RE as ReportEvidence (Model)
    participant DB as Database

    User->>Web: Buka fitur pelaporan
    Web-->>User: Tampilkan form input laporan

    User->>Web: Isi data Report, Suspects, Journey, Evidence
    User->>Web: Klik Submit
    Web->>RC: POST /reports (payload: report + suspects[] + journey + evidence[])

    RC->>RC: Validasi input (Report, Suspects, Journey, Evidence)
    alt Validasi gagal
        RC-->>Web: Redirect back + errors (validation)
        Web-->>User: Tampilkan pesan error (SweetAlert/alert)
    else Validasi lolos
        RC->>R: create(reportData)
        R->>DB: INSERT INTO reports
        DB-->>R: id (report_id)

        loop Setiap Suspect
            RC->>S: create({ ... , report_id })
            S->>DB: INSERT INTO suspects
            DB-->>S: OK
        end

        RC->>RJ: create({ ... , report_id })
        RJ->>DB: INSERT INTO report_journeys
        DB-->>RJ: OK

        RC-->>Web: Redirect ke detail page untuk update journey + flash success
        Web-->>User: Tampilkan pesan sukses (SweetAlert)
        User->>DB: Verifikasi data tersimpan (melalui UI detail)
    end
```

Catatan:
- Diagram ini menggunakan pendekatan langsung Controller → Model (Eloquent) → Database.
- Jika nantinya ada Service/Repository layer untuk Report, posisinya dapat disisipkan di antara Controller dan Model tanpa mengubah alur utama.
- Tampilkan feedback sukses/gagal menggunakan SweetAlert sesuai integrasi global yang sudah ada.
```