# Product Requirements Document (PRD)
## Aplikasi Admin Perusahaan Properti KPR Rumah Subsidi

| Dokumen | |
|---------|-|
| **Nama Produk** | LeadData |
| **Versi** | 1.0 |
| **Tanggal** | 22 Mei 2026 |
| **Status** | Final |

---

## 1. Ringkasan Eksekutif

Aplikasi admin internal untuk perusahaan properti yang bergerak di bidang KPR rumah subsidi. Aplikasi ini bertujuan mengelola lead dari berbagai sumber, memantau pipeline KPR subsidi, serta melacak pengeluaran operasional dengan kontrol akses berbasis peran (pusat vs cabang).

**Stack Teknologi (Final):**
| Layer | Teknologi |
|-------|-----------|
| **Framework** | Laravel 11 |
| **Admin Panel** | Filament 3 |
| **Database** | MySQL 8 (MariaDB) |
| **Frontend** | Blade + Alpine.js + Vite |
| **CSS** | Tailwind CSS |
| **Auth** | Laravel Breeze (session-based) |
| **RBAC** | Spatie Laravel Permission |
| **CSV Parsing** | league/csv |

---

## 2. Tujuan & Manfaat

- **Sentralisasi Lead** — Menggabungkan lead dari berbagai sumber dalam satu dashboard.
- **Visibilitas Pipeline** — Memantau status setiap lead dari BI Checking hingga Akad & BAST.
- **Kontrol Biaya** — Melacak pengeluaran Meta Ads, biaya event offline, biaya perolehan lead online, dan pengeluaran umum.
- **Hirarki Akses** — Super Admin (pusat) memiliki akses penuh; admin cabang hanya melihat data cabangnya.

---

## 3. Role & Hak Akses

| Role | Level Akses |
|------|------------|
| **Super Admin (Pusat)** | Semua modul, semua cabang, semua data keuangan |
| **Admin Cabang** | Lead & pipeline cabang sendiri, biaya operasional cabang |
| **Marketing / Sales** | Input lead, update pipeline, tidak bisa akses keuangan |
| **Finance (Pusat)** | Hanya modul keuangan & pengeluaran |

**Implementasi:** Menggunakan Spatie Laravel Permission dengan tabel `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`.

---

## 4. Modul Aplikasi

### 4.1 Navigasi Filament

```
Settings
├── Cabang        → CRUD cabang, urutan tampilan
├── Proyek        → CRUD proyek perumahan (Super Admin only)
├── Bank          → Master data bank & KC/unit
├── Sales         → Master data marketing + hierarki koordinator
├── Promo         → Master data promo
└── Lead Time     → Aturan batas waktu pipeline

Master Data
├── Kavling       → Unit rumah, status (Tersedia/Dipesan/Terjual)
└── Konsumen      → Data konsumen, pipeline stage

Proses Penjualan
├── Bi Checking   → Hasil SLIK (hanya KPR)
├── PSJB          → Perjanjian Jual Beli Pendahuluan
├── Pemberkasan   → Dokumen KPR ke bank
├── Proses Bank   → SP3K & approval bank
├── PPJB Developer → Akta notariil
├── Akad          → Akad KPR
└── BAST          → Serah terima unit

Pengeluaran       → Tracking biaya operasional
```

### 4.2 Pipeline KPR Subsidi

**Alur Pipeline — KPR vs Cash:**

Pipeline memiliki dua jalur berdasarkan metode pembayaran konsumen yang ditentukan di `konsumens.status_cash`:

| Jalur | Tahapan yang Dilewati |
|-------|----------------------|
| **KPR (FLPP)** | BI Checking → PSJB → Pemberkasan → Proses Bank → PPJB Developer → Akad → BAST |
| **Cash** | Konsumen → PPJB Developer → Akad → BAST (skip BI Checking, PSJB, Pemberkasan, Proses Bank) |

**Penentuan jalur** dilakukan oleh `PipelineFlowService`:
- Jika `status_cash === 'YA'` → Cash path (langsung PPJB Developer)
- Jika `status_cash !== 'YA'` → KPR path (BI Checking dulu)

**Aturan Pipeline:**
- Setiap perpindahan tahap dilakukan via tombol "Lanjut ke [Tahap Berikutnya]" di halaman edit.
- Sistem otomatis membuat record tahap berikutnya dengan data relevan.
- **Lead Time:** Sistem otomatis menghitung selisih hari kerja antara dua transaksi berurutan via `LeadTimeService`.
- **Guard Proses Bank:** Jika `jenis_respon` adalah `Reject` atau `Revisi`, pipeline berhenti — tombol "Lanjut ke PPJB Developer" tidak muncul dan `PipelineFlowService` return null untuk next stage.
- `status_data` dikomputasi secara dinamis via Eloquent accessor (tidak hanya mengandalkan DB column) — memeriksa mandatory fields setiap model.

#### Tahap 1: BI Checking / SLIK
Cek historis kredit konsumen di OJK. **Hanya untuk konsumen KPR (status_cash = TIDAK).**

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_kavling | VARCHAR(50) (FK → kavling) | ID kavling |
| no_ktp | VARCHAR(20) | No. KTP konsumen |
| id_kons | VARCHAR(50) | ID internal konsumen |
| tanggal_slik | DATE | Tanggal pengecekan SLIK |
| hasil_slik | VARCHAR(20) | Hasil BI Checking: OK, KOL 1, KOL 2, KOL 5, NO BIC |
| keterangan | TEXT | Catatan detail hasil SLIK |
| status_data | ENUM('Data Lengkap','Data Belum Lengkap') | Otomatis via accessor: cek `no_ktp`, `tanggal_slik`, `hasil_slik` |

**Mandatory fields (StatusDataObserver):** `no_ktp`, `tanggal_slik`, `hasil_slik`

#### Tahap 2: PSJB (Pendahuluan)
Penandatanganan Perjanjian Jual Beli Pendahuluan.

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_kavling | VARCHAR(50) (FK → kavling) | ID kavling |
| id_kons | VARCHAR(50) | ID konsumen |
| id_psjb | VARCHAR(50) | Nomor/ID dokumen PSJB |
| tanggal_psjb | DATE | Tanggal tanda tangan PSJB |
| nama_koordinator | VARCHAR(100) | Nama koordinator sales |
| nama_sales | VARCHAR(100) | Nama sales |
| harga_unit | DECIMAL(15,2) | Harga unit rumah (format: Rp1.000.000) |
| tanggal_utj | DATE | Tanggal pembayaran UTJ |
| utj | DECIMAL(15,2) | Jumlah UTJ |
| tanggal_dp_klt | DATE | Tanggal pembayaran DP/KLT |
| dp | DECIMAL(15,2) | Jumlah DP |
| klt | VARCHAR(50) | KLT (Kekurangan Luas Tanah) |
| detail_klt | TEXT | Detail KLT |
| cara_pembayaran | VARCHAR(20) | FLPP, Cash, Cash Bertahap |
| id_promo | VARCHAR(20) (FK → promos) | Promo yang digunakan |
| lead_time_hari | INT | Lead time aktual (hari) |
| status | ENUM('ontime','terlambat') | Status lead time |
| keterangan | TEXT | Catatan |
| status_data | ENUM('Data Lengkap','Data Belum Lengkap') | Otomatis via accessor: cek `tanggal_psjb`, `nama_koordinator`, `nama_sales`, `harga_unit`, `cara_pembayaran` |

**Mandatory fields (StatusDataObserver):** `tanggal_psjb`, `nama_koordinator`, `nama_sales`, `harga_unit`, `cara_pembayaran`

#### Tahap 3: Pemberkasan
Pengumpulan dokumen KPR ke bank.

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_kavling | VARCHAR(50) (FK → kavling) | ID kavling |
| id_psjb | VARCHAR(50) | ID PSJB (FK → psjb) |
| id_berkas | VARCHAR(50) | Nomor register berkas |
| tanggal_terima_bank | DATE | Tanggal berkas diterima bank |
| bank | VARCHAR(50) | Bank tujuan pengajuan (BSN, BTN, BNI, BRI, Mandiri, Bank Jateng) |
| kc_unit | VARCHAR(100) | Kantor cabang bank |
| request_plafond | DECIMAL(15,2) | Plafon KPR yang diajukan (format: Rp1.000.000) |
| request_tenor | VARCHAR(20) | Tenor yang diajukan |
| tipe_pemberkasan | ENUM('registrasi','CASH') | Tipe pemberkasan |
| lead_time_hari | INT | Lead time aktual (hari) |
| status | ENUM('ontime','terlambat') | Status lead time |
| keterangan | TEXT | Catatan |
| status_data | ENUM('Data Lengkap','Data Belum Lengkap') | Otomatis via accessor: cek `tipe_pemberkasan` (+ `tanggal_terima_bank`, `bank` jika non-CASH) |

**Mandatory fields (StatusDataObserver):** `tipe_pemberkasan` (+ `tanggal_terima_bank`, `bank` jika bukan CASH)

#### Tahap 4: Proses Bank
Pengajuan KPR ke bank dan memperoleh SP3K.

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_kavling | VARCHAR(50) (FK → kavling) | ID kavling |
| id_berkas | VARCHAR(50) | ID berkas (FK → pemberkasan) |
| no_sp3k | VARCHAR(100) | Nomor SP3K |
| jenis_respon | VARCHAR(20) | Approved, Approved Tenor, Approved Turun Plafond, Reject, Revisi, CASH |
| approved_plafond | DECIMAL(15,2) | Plafon disetujui bank |
| approved_tenor | VARCHAR(20) | Tenor disetujui |
| lead_time_hari | INT | Lead time aktual (hari) |
| status | ENUM('ontime','terlambat') | Status lead time |
| kategori_revisi | VARCHAR(100) | Kategori revisi jika ada |
| detail_revisi | TEXT | Detail revisi |
| keterangan | TEXT | Catatan |
| status_data | ENUM('Data Lengkap','Data Belum Lengkap') | Otomatis via accessor: cek `no_sp3k`, `jenis_respon`, `approved_plafond` |

**Mandatory fields (StatusDataObserver):** `no_sp3k`, `jenis_respon`, `approved_plafond`

**Guard:** Jika `jenis_respon = Reject` atau `Revisi`, pipeline berhenti:
- `PipelineFlowService::getNextStageClass()` return `null`
- Tombol "Lanjut ke PPJB Dev" tidak muncul di UI

#### Tahap 5: PPJB Developer
Perjanjian Jual Beli antara developer & konsumen (notariil).

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_kavling | VARCHAR(50) (FK → kavling) | ID kavling |
| no_sp3k | VARCHAR(100) | No. SP3K (FK → proses_bank) |
| id_ppjb_dev | VARCHAR(100) | Nomor dokumen PPJB notariil |
| tanggal_sp3k | DATE | Tanggal SP3K terbit |
| tanggal_ttd_ppjb | DATE | Tanggal tanda tangan PPJB |
| lead_time_hari | INT | Lead time aktual (hari) |
| status | ENUM('ontime','terlambat') | Status lead time |
| keterangan | TEXT | Catatan |
| status_data | ENUM('Data Lengkap','Data Belum Lengkap') | Otomatis via accessor: cek `tanggal_sp3k`, `tanggal_ttd_ppjb` |

**Mandatory fields (StatusDataObserver):** `tanggal_sp3k`, `tanggal_ttd_ppjb`

#### Tahap 6: Akad
Akad KPR di bank / notaris.

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_kavling | VARCHAR(50) (FK → kavling) | ID kavling |
| id_ppjb_dev | VARCHAR(100) | ID PPJB (FK → ppjb_dev) |
| no_ppjb_akad | VARCHAR(50) | Nomor dokumen akad |
| tanggal_akad | DATE | Tanggal akad |
| kualitas_akad | VARCHAR(50) | Akad Sempurna, Akad DP Belum Lunas, Akad Bangunan Belum Jadi, Akad KLT Belum Lunas |
| lead_time_hari | INT | Lead time aktual (hari) |
| status | ENUM('ontime','terlambat') | Status lead time |
| keterangan_terlambat | TEXT | Alasan keterlambatan |
| keterangan | TEXT | Catatan |
| status_data | ENUM('Data Lengkap','Data Belum Lengkap') | Otomatis via accessor: cek `tanggal_akad` |

**Mandatory fields (StatusDataObserver):** `tanggal_akad`

#### Tahap 7: BAST (Berita Acara Serah Terima)
Serah terima kunci & unit rumah.

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_kavling | VARCHAR(50) (FK → kavling) | ID kavling |
| no_ppjb_akad | VARCHAR(50) | No. akad (FK → akad) |
| no_bast | VARCHAR(50) | Nomor BAST |
| tanggal_bast | DATE | Tanggal BAST |
| lead_time_hari | INT | Lead time aktual (hari) |
| status | ENUM('ontime','terlambat') | Status lead time |
| keterangan | TEXT | Alasan keterlambatan |
| status_data | ENUM('Data Lengkap','Data Belum Lengkap') | Otomatis via accessor: cek `tanggal_bast` |

**Mandatory fields (StatusDataObserver):** `tanggal_bast`

### 4.3 Format Input & Display Rupiah

- Semua field harga/plafond/dp menggunakan mask `$money($input, ".", ",")` via Alpine.js (`@alpinejs/mask`)
- Format Indonesia: titik sebagai pemisah ribuan, koma sebagai desimal
- Display di tabel menggunakan `->money('IDR', decimalPlaces: 0, locale: 'id')`
- Data integer bersih (`166000000`) yang tersimpan di DB, bukan string dengan separator
- `dehydrateStateUsing` strip non-digit sebelum simpan

### 4.4 Status Kavling

| Status | Badge Color |
|--------|------------|
| Tersedia | Success (hijau) |
| Dipesan | Warning (kuning) |
| Terjual | Danger (merah) |

### 4.5 Tracking Pengeluaran

#### 4.5.1 Kategori Pengeluaran

| Kategori | Visibility | Deskripsi |
|----------|-----------|-----------|
| **Meta Ads** | Pusat | Biaya iklan Facebook/Instagram, per campaign |
| **Biaya Lead Online** | Pusat | Biaya per lead dari marketplace, Google Ads, dll |
| **Biaya Event Offline** | Pusat & Cabang | Biaya acara, konsumsi, sewa tempat, dll |
| **Operasional Cabang** | Pusat & Cabang | ATK, transport, pulsa, dll |
| **Pengeluaran Umum** | Pusat Only | Sewa kantor, gaji, listrik, dll |

---

## 5. Master Data (Database Schema)

### 5.1 Tabel Cabang

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK, Auto Increment) | ID unik cabang |
| nama | VARCHAR(255) | Nama cabang |
| urutan | INT | Urutan tampilan |

**Data:** Malang (1), Madiun (2), Solo (3), Magelang (4), Purworejo (5), Purwokerto (6), Jepara (7), Pekalongan (8), Sumedang (9), Bandung (10)

### 5.2 Tabel Proyek

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK, Auto Increment) | ID unik proyek |
| nama_proyek | VARCHAR(255) | Nama proyek (dengan prefix Marison) |
| cabang_id | BIGINT (FK → cabangs) | Cabang tempat proyek |

**Data contoh:** Marison Pati, Marison Regency Jepara 2, Marison Regency Kuwasen, Marison Jabung Malang, Marison Sragen, dll.

### 5.3 Tabel Bank

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK, Auto Increment) | ID unik bank |
| bank | VARCHAR(100) | Nama bank |
| kc_unit | VARCHAR(100) | Kantor Cabang / Unit |

### 5.4 Tabel Kavling (Unit Rumah)

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_kavling | VARCHAR(50) (PK) | ID unik kavling (proyek-kode) |
| proyek_id | BIGINT (FK → proyeks) | Proyek perumahan |
| kode_kavling | VARCHAR(20) | Kode unit |
| luas_bangunan_m2 | DECIMAL(5,2) | Luas bangunan (m²) |
| luas_tanah_m2 | DECIMAL(5,2) | Luas tanah (m²) |
| progres_bangun | VARCHAR(20) | Progress pembangunan (100%, 90%, 75%, BELUM SPK) |
| harga | DECIMAL(15,2) | Harga jual |
| status_kavling | ENUM('Tersedia','Dipesan','Terjual') | Status kavling |
| cabang_id | BIGINT (FK → cabangs) | Cabang |

**Format ID:** `Marison Pati-A04`, `Marison Regency Jepara 2-AA01`, `Marison Regency Kuwasen-B12`

### 5.5 Tabel Konsumen

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_konsumen | BIGINT (PK, Auto Increment) | ID unik konsumen |
| id_kavling | VARCHAR(50) (FK → kavling) | Kavling yang dibeli |
| no_ktp | VARCHAR(20) | Nomor KTP |
| nama_konsumen | VARCHAR(150) | Nama lengkap |
| tanggal_lahir | DATE | Tanggal lahir |
| pekerjaan | VARCHAR(100) | Jenis pekerjaan |
| detail_pekerjaan | VARCHAR(100) | Detail pekerjaan |
| umur | VARCHAR(10) | Usia (display) |
| alamat | TEXT | Alamat lengkap |
| kelurahan | VARCHAR(100) | Kelurahan/Desa |
| kecamatan | VARCHAR(100) | Kecamatan |
| kabupaten_kota | VARCHAR(100) | Kabupaten/Kota |
| no_hp | VARCHAR(20) | No. HP konsumen |
| nama_kondar | VARCHAR(100) | Nama kontak darurat |
| no_hp_kondar | VARCHAR(20) | No. HP kontak darurat |
| status_cash | ENUM('YA','TIDAK') | Pembayaran cash atau KPR |
| status_data | ENUM('Data Lengkap','Data Belum Lengkap') | Otomatis via accessor: cek `nama_konsumen`, `no_ktp`, `no_hp`, `pekerjaan`, `tanggal_lahir`, `alamat`, `kelurahan`, `kecamatan`, `kabupaten_kota` |
| keterangan | TEXT | Catatan tambahan |

**Display:** `id_konsumen` dapat di-hide di table view.

### 5.6 Tabel Sales (Marketing)

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK, Auto Increment) | ID unik sales |
| nik_sales | VARCHAR(20) | NIK sales internal |
| nama_sales | VARCHAR(100) | Nama sales |
| nik_koordinator | VARCHAR(20) | NIK koordinator (self-reference) |
| nama_koordinator | VARCHAR(100) | Nama koordinator |
| status | ENUM('Aktif','OUT','OJT') | Status keaktifan |
| cabang | VARCHAR(100) | Cabang tempat sales |

### 5.7 Tabel Promo

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id_promo | VARCHAR(20) (PK) | ID promo unik |
| nama_promo | VARCHAR(200) | Nama promo |
| tanggal_mulai | DATE | Tanggal mulai promo |
| tanggal_selesai | DATE | Tanggal selesai promo |
| keterangan | TEXT | Catatan & syarat promo |
| status | ENUM('Aktif','Nonaktif') | Status promo |

### 5.8 Tabel Lead Time (Aturan Pipeline)

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK, Auto Increment) | ID unik |
| tahap_awal | VARCHAR(50) | Tahap pipeline asal |
| tahap_tujuan | VARCHAR(50) | Tahap pipeline tujuan |
| proses | VARCHAR(100) | Nama proses transisi |
| target_hari_kerja | INT | Batas maksimal hari kerja |

**Data Aturan Lead Time:**

| Dari | Ke | Proses | Target Hari Kerja |
|------|----|--------|:---:|
| BI Checking | PSJB | bi_checking → psjb | 1 |
| PSJB | Pemberkasan | psjb → pemberkasan | 3 |
| Pemberkasan | Proses Bank | pemberkasan → proses bank | 10 |
| Proses Bank | PPJB Developer | proses bank → ppjb_dev | 10 |
| PPJB Developer | Akad | ppjb_dev → akad | 3 |
| Akad | BAST | akad → bast | 1 |

### 5.9 Tabel Pipeline Log

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK) | ID unik |
| kavling_id | VARCHAR(50) | Kavling terkait |
| from_stage | VARCHAR(50) | Tahap asal |
| to_stage | VARCHAR(50) | Tahap tujuan |
| triggered_by | BIGINT (FK → users) | User yang memicu transisi |
| created_at | TIMESTAMP | Waktu transisi |

### 5.10 Tabel Users & Auth

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK) | ID unik user |
| name | VARCHAR(255) | Nama lengkap |
| email | VARCHAR(255) (unique) | Email login |
| password | VARCHAR(255) | Password (hashed) |
| cabang_id | BIGINT (FK → cabangs) | Cabang user |
| role | VARCHAR(50) | super-admin / admin-cabang / marketing / finance |
| remember_token | VARCHAR(100) | Token session |

### 5.11 Tabel Roles & Permissions (Spatie)

| Tabel | Fungsi |
|-------|--------|
| roles | Daftar role (Super Admin, Admin Cabang, Marketing, Finance) |
| permissions | Daftar permission per modul |
| model_has_roles | Pivot user → role |
| model_has_permissions | Pivot user → permission (optional) |
| role_has_permissions | Pivot role → permission |

### 5.12 View di Filament

| Page | Route | Fitur |
|------|-------|-------|
| Dashboard | `/admin` | Pipeline funnel, grafik, lead terbaru |
| Kavling | `/admin/kavlings` | CRUD + import CSV kavling |
| Konsumen | `/admin/konsumens` | CRUD + ID konsumen hidden |
| Bi Checking | `/admin/bi-checkings` | CRUD + hasil SLIK |
| PSJB | `/admin/psjbs` | CRUD + currency mask |
| Pemberkasan | `/admin/pemberkasans` | CRUD + plafond mask |
| Proses Bank | `/admin/proses-banks` | CRUD + guard reject/revisi |
| PPJB Dev | `/admin/ppjb-devs` | CRUD |
| Akad | `/admin/akads` | CRUD |
| BAST | `/admin/basts` | CRUD |
| Pengeluaran | `/admin/expenses` | CRUD + kategori |
| Proyek | `/admin/settings/proyeks` | Settings → CRUD proyek |
| Cabang | `/admin/settings/cabangs` | Settings → CRUD cabang |
| Bank | `/admin/settings/banks` | Settings → CRUD bank |
| Sales | `/admin/settings/sales` | Settings → CRUD sales |
| Promo | `/admin/settings/promos` | Settings → CRUD promo |
| Lead Time | `/admin/settings/lead-times` | Settings → CRUD lead time |

---

## 6. Relasi Antar Tabel

```
cabangs ──1:N── proyeks
proyeks ──1:N── kavlings (via proyek_id)
cabangs ──1:N── kavlings (via cabang_id)

kavlings ──1:1── bi_checking (via id_kavling)
kavlings ──1:1── psjb (via id_kavling)
kavlings ──1:1── pemberkasan (via id_kavling)
kavlings ──1:1── proses_bank (via id_kavling)
kavlings ──1:1── ppjb_dev (via id_kavling)
kavlings ──1:1── akad (via id_kavling)
kavlings ──1:1── bast (via id_kavling)

kavlings ──1:N── konsumens (via id_kavling)

users ──N:1── cabangs (via cabang_id)
users ──M:N── roles (via model_has_roles)
roles ──M:N── permissions (via role_has_permissions)
```

---

## 7. Service Layer

### 7.1 PipelineFlowService

`app/Services/PipelineFlowService.php`

Menangani logika transisi antar tahap pipeline:

| Method | Fungsi |
|--------|--------|
| `getNextStageClass()` | Menentukan class model tahap berikutnya |
| `getNextStageLabel()` | Label tombol "Lanjut ke..." |
| `getNextStageData()` | Data default untuk record tahap berikutnya |
| `getNextStageRelation()` | Nama relasi Eloquent ke tahap berikutnya |
| `nextStageExists()` | Cek apakah record tahap berikutnya sudah ada |
| `findOrCreateNextStage()` | Ambil existing atau buat baru record tahap berikutnya |
| `finalizeStage()` | Hitung lead time & update status |
| `getNextStageEditUrl()` | Generate URL edit untuk tahap berikutnya |

**Flow transisi:**
```
Konsumen → (cash? YA → Psjb, TIDAK → BiChecking)
BiChecking → Psjb
Psjb → Pemberkasan
Pemberkasan → (cash path? YA → PpjbDev, TIDAK → ProsesBank)
ProsesBank → (jenis_respon Reject/Revisi? → null, else → PpjbDev)
PpjbDev → Akad
Akad → Bast
Bast → null (end)
```

### 7.2 LeadTimeService

`app/Services/LeadTimeService.php`

Menghitung lead time aktual (hari kerja) antara `created_at` record saat ini dan timestamp yang diberikan, lalu membandingkan dengan target dari tabel `lead_times`.

### 7.3 StatusDataObserver

`app/Observers/StatusDataObserver.php`

Registered di `AppServiceProvider::boot()` untuk model: Konsumen, BiChecking, Psjb, Pemberkasan, ProsesBank, PpjbDev, Akad, Bast.

Hook `saving()`: set `status_data` berdasarkan kelengkapan mandatory fields masing-masing model.

### 7.4 Status Data Accessor (Computed)

Setiap pipeline model memiliki `getStatusDataAttribute()` accessor yang mengkomputasi `status_data` secara dinamis. Ini memastikan nilai selalu akurat meskipun kolom DB belum diupdate.

---

## 8. Spesifikasi Teknis

| Aspek | Spesifikasi |
|-------|------------|
| **Platform** | Web-based (Responsive) |
| **Frontend** | Laravel Blade + Filament 3 + Alpine.js + Tailwind CSS |
| **Backend** | PHP 8.2+ / Laravel 11 |
| **Database** | MySQL 8 / MariaDB |
| **Auth** | Laravel Breeze (session-based) |
| **RBAC** | Spatie Laravel Permission |
| **CSV** | league/csv |
| **Build** | Vite + NPM |
| **UI Components** | Heroicons, Filament built-in |
| **Deployment** | VPS / Shared hosting |

---

## 9. Prioritas Fitur (MVP vs V2)

### MVP (Fase 1) — Implemented
- [x] CRUD Master Data: Cabang, Proyek, Bank, Sales, Promo, Lead Time
- [x] CRUD Kavling (+ status badge: Tersedia/Dipesan/Terjual)
- [x] CRUD Konsumen (+ hide ID konsumen)
- [x] Pipeline KPR: BiChecking → PSJB → Pemberkasan → ProsesBank → PpjbDev → Akad → Bast
- [x] Dual path: Cash vs KPR
- [x] Currency mask (Alpine.js `$money`) untuk semua field rupiah
- [x] Guard Proses Bank: stop pipeline jika Reject/Revisi
- [x] Status data computed (accessor) — selalu akurat
- [x] Status data auto-set via observer saat save
- [x] Lead time calculation & status (ontime/terlambat)
- [x] Pipeline flow service
- [x] Role & permission (Spatie)
- [x] Multi-cabang scoping
- [x] Export data via ExportService (DB langsung, tidak terpengaruh formatting)
- [x] Seeder data dari CSV (8 file: kavling, konsumen, 7 pipeline)
- [x] Auto-mapping id_kavling di seeder (handle format dengan/senza Marison)

### Fase 2
- [ ] Notifikasi otomatis
- [ ] Google Sheets Integration
- [ ] Import pipeline massal per tahap
- [ ] Integrasi API Meta Ads
- [ ] Modul target & realisasi sales
- [ ] Komisi sales

### Fase 3
- [ ] Integrasi WhatsApp
- [ ] Aplikasi mobile (Android)
- [ ] AI Scoring Lead

---

## 10. Asumsi & Dependensi

- Data master cabang, proyek, sales, bank, promo, lead time sudah tersedia.
- Nomor telepon konsumen bersifat unik.
- Biaya Meta Ads diinput manual.
- Pipeline tidak bisa revert otomatis (mundur manual via admin).
- Proyek non-Marison (misal Marisland Pakelan) dapat ditambahkan terpisah tanpa prefix Marison.

---

## 11. Alur Input Pipeline (Business Flow)

```
[Konsumen Lengkap]
    ↓
├─── [Cash] ─────────────────────────────────
│         ↓
│   [PPJB Developer]
│         ↓
│      [Akad]
│         ↓
│      [BAST] → Selesai
│
└─── [KPR / FLPP] ───────────────────────────
          ↓
  [BI Checking] ← Gagal → Berhenti
          ↓ Lolos
       [PSJB]
          ↓
    [Pemberkasan]
          ↓
    [Proses Bank] ← Reject/Revisi → Berhenti
          ↓ Approved
    [PPJB Developer]
          ↓
        [Akad]
          ↓
       [BAST] → Selesai
```

---

## 12. Glossary

| Istilah | Arti |
|---------|------|
| BI Checking / SLIK | Sistem Layanan Informasi Keuangan OJK |
| PSJB | Perjanjian Jual Beli Pendahuluan |
| PPJB | Perjanjian Jual Beli |
| BAST | Berita Acara Serah Terima |
| SP3K | Surat Persetujuan Prinsip Pemberian Kredit |
| Akad | Penandatanganan kredit KPR |
| CPL | Cost Per Lead |
| CFD | Car Free Day |
| KPR | Kredit Pemilikan Rumah |
| FLPP | Fasilitas Likuiditas Pembiayaan Perumahan |
| UTJ | Uang Tanda Jadi |
| KLT | Kekurangan Luas Tanah |
| Lead | Calon konsumen potensial |
| Pipeline | Tahapan proses KPR |
| Funnel | Visualisasi pipeline berbentuk corong |
| Lead Time | Batas maksimal waktu (hari kerja) per transisi tahap |
