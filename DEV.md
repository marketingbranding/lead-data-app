# Development Plan — OASIS

## Tech Stack
- **Backend:** Laravel 13.7.0 (PHP 8.3.31)
- **Admin Panel:** FilamentPHP v5.6.4
- **Database:** MySQL / MariaDB (via XAMPP)
- **Auth:** Spatie Permission v7.4
- **Export/Import:** OpenSpout v4.32
- **Storage:** Local (upload dokumen pipeline)
- **Deployment:** User handle sendiri

## Environment
- PHP 8.3.31 (ZTS Visual C++ 2019), Composer 2.9, Windows
- Working dir: `D:\WEBSITE\MarisAPP\LeadData`
- Project dir: `D:\WEBSITE\MarisAPP\LeadData\lead-data-app`
- Admin URL: `http://localhost/lead-data-app/public/admin`

## Bahasa
Kode, komentar, dokumentasi, UI dalam **Bahasa Indonesia**.

---

## Development Steps (Urutan Eksekusi)

### ✅ Tahap 0: Setup Proyek
- [x] `composer create-project laravel/laravel lead-data` — Laravel 13.7.0 terinstall
- [x] Konfigurasi `.env` (MySQL: `lead_data` database)
- [x] Install FilamentPHP v5.6.4 panel
- [x] Install `spatie/laravel-permission` v7.4
- [x] Install `openspout/openspout` v4.32 (PHP 8.3 compatible)
- [x] Setup user admin: `admin@leaddata.com` / `admin`
- [-] Skip `spatie/laravel-medialibrary` (Filament built-in file upload mencukupi)

### ✅ Tahap 1: Database & Migrations (15 migrations)
- [x] `banks` — `id_bank`, `bank`, `kc_unit`
- [x] `kavlings` — `id_kavling` (PK string), `proyek`, `kode_kavling`, `luas_bangunan_m2`, `luas_tanah_m2`, `progres_bangun`, `status_kavling`, `harga`
- [x] `konsumens` — `id_konsumen`, `id_kavling` (FK), `no_ktp`, `nama_konsumen`, `tanggal_lahir`, `pekerjaan`, `detail_pekerjaan`, `umur`, `alamat`, `kelurahan`, `kecamatan`, `kabupaten_kota`, `no_hp`, `nama_kondar`, `no_hp_kondar`, `status_cash`, `status_data`, `keterangan`
- [x] `sales` — `nik_sales` (PK string), `nama_sales`, `nik_koordinator` (self-ref FK), `nama_koordinator`, `cabang`, `status`
- [x] `promos` — `id_promo` (PK string), `nama_promo`, `tanggal_mulai`, `tanggal_selesai`, `keterangan`
- [x] `lead_times` — `id_lead_time`, `tahap_awal`, `tahap_tujuan`, `proses`, `target_hari_kerja`
- [x] `bi_checking` — pipeline stage 1
- [x] `psjb` — pipeline stage 2 (field lengkap: harga, utj, dp, cara_pembayaran, dll)
- [x] `pemberkasan` — pipeline stage 3
- [x] `proses_bank` — pipeline stage 4
- [x] `ppjb_dev` — pipeline stage 5
- [x] `akad` — pipeline stage 6 (dengan `kualitas_akad`, `keterangan_terlambat`)
- [x] `bast` — pipeline stage 7
- [x] `expenses` — tracking pengeluaran (`id_expense`, `id_kavling` FK, `nama_pengeluaran`, `kategori`, `jumlah`, `tanggal`, `bukti`)
- [x] `pipeline_logs` — history pergerakan pipeline

### ✅ Tahap 2: Model & Relasi (15 models)
- [x] `Bank`, `Kavling` (string PK), `Konsumen`, `Sales` (string PK, self-ref), `Promo` (string PK)
- [x] `LeadTime`, `BiChecking`, `Psjb`, `Pemberkasan`, `ProsesBank`, `PpjbDev`, `Akad`, `Bast`
- [x] `Expense`, `PipelineLog`
- [x] All relationships: Kavling hasOne each pipeline stage, Konsumen belongsTo Kavling, etc.

### ✅ Tahap 3: Seeder (13 seeders, semua dari CSV)
- [x] `BankSeeder` — 12 records
- [x] `KavlingSeeder` — 542 records
- [x] `SalesSeeder` — 25 records (OJT/OUT deduplicated)
- [x] `PromoSeeder` — 5 records
- [x] `KonsumenSeeder` — 249 records
- [x] `LeadTimeSeeder` — 6 aturan
- [x] `BiCheckingSeeder` — 241 records
- [x] `PsjbSeeder` — 2,708 records (filtered by valid kavling_id)
- [x] `PemberkasanSeeder` — 151 records
- [x] `ProsesBankSeeder` — 123 records
- [x] `PpjbDevSeeder` — 118 records
- [x] `AkadSeeder` — 116 records
- [x] `BastSeeder` — 114 records

### ✅ Tahap 4: Filament Resources (16 resources)
- [x] **Master Data (6):** Bank, Kavling, Sales, Promo, Konsumen, LeadTime
- [x] **Pipeline KPR (7):** BiChecking, Psjb, Pemberkasan, ProsesBank, PpjbDev, Akad, Bast
- [x] **Keuangan (1):** Expense
- [x] **Log (1):** PipelineLog

### Tahap 5: Fitur Khusus
- [ ] **Dual Path Pipeline** — Observer: jika `konsumen.status_cash = YA`, skip bi_checking/psjb/pemberkasan/proses_bank
- [ ] **Lead Time Otomatis** — Hitung selisih hari kerja, bandingkan dengan `lead_times`, set `ontime`/`terlambat`
- [ ] **Dashboard Widgets** — Total lead, pipeline funnel, pengeluaran bulan ini, lead terlambat

### Tahap 6: Export & Import (via Filament)
- [ ] Export actions di setiap Resource (Excel + CSV)
- [ ] Import untuk Master Data + Lead
- [ ] Download template Excel
- [ ] Validasi import

### Tahap 7: Role & Permission
- [ ] Setup Spatie Permission + roles
- [ ] Scope cabang

---

## Aturan Penting

### Cash vs KPR
- **KPR (status_cash = TIDAK):** BI Checking → PSJB → Pemberkasan → Proses Bank → PPJB Dev → Akad → BAST
- **Cash (status_cash = YA):** Langsung PPJB Dev → Akad → BAST
- Tabel bi_checking, psjb, pemberkasan, proses_bank untuk Cash diisi nilai default:
  - `hasil_slik` = null
  - `tipe_pemberkasan` = 'CASH'
  - `jenis_respon` = 'CASH'
  - `lead_time_hari` = 0
  - `status` = 'ontime'
- Penentuan Cash/KPR dari `konsumen.status_cash`, **bukan** dari `hasil_slik = NO BIC`

### NO BIC — BUKAN hasil SLIK
Nilai `NO BIC` di file `1 - bi_checking.csv` adalah artifact Excel karena tidak bisa memisahkan jalur Cash dan KPR. Di aplikasi, ini **bukan** hasil SLIK. Konsumen dengan `status_cash = YA` langsung dianggap Cash.

### Lead Time
Aturan dari `lead_time.csv`:
| Transisi | Target Hari |
|----------|:-----------:|
| bi_checking → psjb | 1 |
| psjb → pemberkasan | 3 |
| pemberkasan → proses_bank | 10 |
| proses_bank → ppjb_dev | 10 |
| ppjb_dev → akad | 3 |
| akad → bast | 1 |

### Sumber Data (References)
| Folder/File | Isi |
|-------------|-----|
| `references/masterdata/` | Bank, Kavling, Konsumen, Sales, Promo, LeadTime |
| `references/pipelinekpr/` | 7 CSV untuk tiap tahap pipeline |

---

## Perintah Berguna

```bash
# Setup project baru
composer create-project laravel/laravel lead-data
cd lead-data

# Install Filament
composer require filament/filament:"^3.2" -W
php artisan filament:install --panels

# Install packages
composer require spatie/laravel-permission
composer require filament/spatie-laravel-permission-plugin
composer require spatie/laravel-medialibrary
composer require openspout/openspout
composer require filament/actions

# Create admin user
php artisan make:filament-user

# Make migration
php artisan make:migration create_banks_table
php artisan make:model Bank -m
php artisan make:resource BankResource --generate

# Run
php artisan migrate
php artisan db:seed
php artisan serve
```

## Data Seeder Pattern (CSV Import)
```php
// Contoh seeder dari CSV
$csv = array_map('str_getcsv', file(database_path('../../references/masterdata/file.csv')));
$header = array_shift($csv);
foreach ($csv as $row) {
    $data = array_combine($header, $row);
    Bank::create([...]);
}
```
