<?php

namespace App\Console\Commands;

use App\Models\Akad;
use App\Models\Bast;
use App\Models\BiChecking;
use App\Models\Konsumen;
use App\Models\Pemberkasan;
use App\Models\PpjbDev;
use App\Models\ProsesBank;
use App\Models\Psjb;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

#[Signature('pipeline:update-status-data {--dry-run : Lihat record yang akan diupdate tanpa eksekusi}')]
#[Description('Update status_data semua record pipeline berdasarkan kelengkapan mandatory fields')]
class UpdateStatusData extends Command
{
    protected const MANDATORY_FIELDS = [
        Konsumen::class => ['nama_konsumen', 'no_ktp', 'no_hp', 'pekerjaan', 'tanggal_lahir', 'alamat', 'kelurahan', 'kecamatan', 'kabupaten_kota'],
        BiChecking::class => ['no_ktp', 'tanggal_slik', 'hasil_slik'],
        Psjb::class => ['tanggal_psjb', 'nama_koordinator', 'nama_sales', 'harga_unit', 'cara_pembayaran'],
        Pemberkasan::class => ['tipe_pemberkasan'],
        ProsesBank::class => ['no_sp3k', 'jenis_respon', 'approved_plafond'],
        PpjbDev::class => ['tanggal_sp3k', 'tanggal_ttd_ppjb'],
        Akad::class => ['tanggal_akad'],
        Bast::class => ['tanggal_bast'],
    ];

    protected const LABELS = [
        Konsumen::class => 'Konsumen',
        BiChecking::class => 'Bi Checking',
        Psjb::class => 'PSJB',
        Pemberkasan::class => 'Pemberkasan',
        ProsesBank::class => 'Proses Bank',
        PpjbDev::class => 'PPJB Dev',
        Akad::class => 'Akad',
        Bast::class => 'BAST',
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $totalUpdated = 0;
        $totalSkipped = 0;

        foreach (static::MANDATORY_FIELDS as $modelClass => $fields) {
            $label = static::LABELS[$modelClass];
            $this->line("Processing {$label}...");

            $query = $modelClass::query();
            $count = $query->count();
            $updated = 0;
            $skipped = 0;

            $query->chunk(100, function ($records) use ($modelClass, $fields, $dryRun, &$updated, &$skipped, $label) {
                foreach ($records as $record) {
                    $result = $this->checkMandatory($record, $modelClass, $fields);
                    $newStatus = $result ? 'Data Lengkap' : 'Data Belum Lengkap';

                    if ($record->status_data === $newStatus) {
                        $skipped++;
                        continue;
                    }

                    if ($dryRun) {
                        $this->line("  [DRY-RUN] {$record->getKey()} : {$record->status_data} → {$newStatus}");
                    }

                    if (!$dryRun) {
                        $record->status_data = $newStatus;
                        $record->saveQuietly();
                    }

                    $updated++;
                }
            });

            $totalUpdated += $updated;
            $totalSkipped += $skipped;

            $this->line("  {$label}: {$updated} updated, {$skipped} already correct");
        }

        if ($dryRun) {
            $this->info("Dry run selesai. {$totalUpdated} record akan diupdate.");
        } else {
            $this->info("Selesai. {$totalUpdated} record diupdate, {$totalSkipped} sudah benar.");
        }

        return Command::SUCCESS;
    }

    protected function checkMandatory(Model $record, string $modelClass, array $fields): bool
    {
        foreach ($fields as $field) {
            if (blank($record->{$field})) {
                return false;
            }
        }

        return true;
    }
}
