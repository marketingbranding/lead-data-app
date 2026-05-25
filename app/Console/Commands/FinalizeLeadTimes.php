<?php

namespace App\Console\Commands;

use App\Models\Akad;
use App\Models\Bast;
use App\Models\Pemberkasan;
use App\Models\PpjbDev;
use App\Models\ProsesBank;
use App\Models\Psjb;
use App\Services\LeadTimeService;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('pipeline:finalize-lead-times {--dry-run : Lihat record yang akan diupdate tanpa eksekusi}')]
#[Description('Finalize lead time (lead_time_hari & status) untuk pipeline records yang belum punya status')]
class FinalizeLeadTimes extends Command
{
    protected const MODELS = [
        Psjb::class       => 'PSJB',
        Pemberkasan::class => 'Pemberkasan',
        ProsesBank::class  => 'Proses Bank',
        PpjbDev::class     => 'PPJB Dev',
        Akad::class        => 'Akad',
        Bast::class        => 'BAST',
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $totalUpdated = 0;

        foreach (static::MODELS as $modelClass => $label) {
            $this->line("Processing {$label}...");

            $records = $modelClass::whereNull('status')->get();
            $updated = 0;

            foreach ($records as $record) {
                $end = $this->getNextStageCreatedAt($record);
                if (!$end) {
                    continue;
                }

                app(LeadTimeService::class)->calculate($record, Carbon::parse($end));

                if ($record->lead_time_hari === null && $record->status === null) {
                    continue;
                }

                if ($dryRun) {
                    $this->line("  [DRY-RUN] #{$record->getKey()}: {$record->lead_time_hari} hari → {$record->status}");
                } else {
                    $record->saveQuietly();
                }

                $updated++;
            }

            $totalUpdated += $updated;
            $this->line("  {$label}: {$updated} updated");
        }

        if ($dryRun) {
            $this->info("Dry run selesai. {$totalUpdated} record akan diupdate.");
        } else {
            $this->info("Selesai. {$totalUpdated} record diupdate.");
        }

        return Command::SUCCESS;
    }

    private function getNextStageCreatedAt(mixed $record): ?Carbon
    {
        $kavling = $record->kavling;
        if (!$kavling) {
            return null;
        }

        $end = match (get_class($record)) {
            Psjb::class => $kavling->biChecking?->created_at ?? $kavling->pemberkasan?->created_at,
            Pemberkasan::class => $kavling->prosesBank?->created_at ?? $kavling->ppjbDev?->created_at,
            ProsesBank::class => $kavling->ppjbDev?->created_at,
            PpjbDev::class => $kavling->akad?->created_at,
            Akad::class => $kavling->bast?->created_at,
            Bast::class => $record->updated_at,
            default => null,
        };

        return $end ? Carbon::parse($end) : null;
    }
}
