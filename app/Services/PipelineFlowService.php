<?php

namespace App\Services;

use App\Models\Akad;
use App\Models\Bast;
use App\Models\BiChecking;
use App\Models\Konsumen;
use App\Models\Pemberkasan;
use App\Models\PpjbDev;
use App\Models\ProsesBank;
use App\Models\Psjb;
use App\Services\LeadTimeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PipelineFlowService
{
    public function getNextStageClass(Model $record): ?string
    {
        return match (get_class($record)) {
            Konsumen::class => $record->status_cash === 'YA' ? Psjb::class : BiChecking::class,
            BiChecking::class => Psjb::class,
            Psjb::class => Pemberkasan::class,
            Pemberkasan::class => $record->kavling?->isCashPath() ? PpjbDev::class : ProsesBank::class,
            ProsesBank::class => in_array($record->jenis_respon, ['Reject', 'Revisi']) ? null : PpjbDev::class,
            PpjbDev::class => Akad::class,
            Akad::class => Bast::class,
            default => null,
        };
    }

    public function getNextStageLabel(Model $record): ?string
    {
        return match (get_class($record)) {
            Konsumen::class => $record->status_cash === 'YA' ? 'Lanjut ke PSJB' : 'Lanjut ke Bi Checking',
            BiChecking::class => 'Lanjut ke PSJB',
            Psjb::class => 'Lanjut ke Pemberkasan',
            Pemberkasan::class => $record->kavling?->isCashPath() ? 'Lanjut ke PPJB Dev' : 'Lanjut ke Proses Bank',
            ProsesBank::class => in_array($record->jenis_respon, ['Reject', 'Revisi']) ? null : 'Lanjut ke PPJB Dev',
            PpjbDev::class => 'Lanjut ke Akad',
            Akad::class => 'Lanjut ke BAST',
            default => null,
        };
    }

    public function getNextStageData(Model $record): array
    {
        $nextClass = $this->getNextStageClass($record);

        return match (get_class($record)) {
            Konsumen::class => $nextClass === BiChecking::class
                ? ['id_kavling' => $record->id_kavling, 'no_ktp' => $record->no_ktp, 'id_kons' => $record->id_konsumen]
                : ['id_kavling' => $record->id_kavling, 'id_kons' => $record->id_konsumen],
            BiChecking::class => ['id_kavling' => $record->id_kavling, 'id_kons' => $record->id_kons, 'id_psjb' => 'PSJB-' . substr($record->id_kavling, 0, 15) . '-' . now()->format('ymdHis')],
            Psjb::class => ['id_kavling' => $record->id_kavling, 'id_psjb' => $record->id_psjb, 'id_berkas' => 'BRK-' . substr($record->id_kavling, 0, 15) . '-' . now()->format('ymdHis')],
            Pemberkasan::class => $nextClass === ProsesBank::class
                ? ['id_kavling' => $record->id_kavling, 'id_berkas' => $record->id_berkas]
                : ['id_kavling' => $record->id_kavling],
            ProsesBank::class => ['id_kavling' => $record->id_kavling, 'no_sp3k' => $record->no_sp3k],
            PpjbDev::class => ['id_kavling' => $record->id_kavling, 'id_ppjb_dev' => $record->id_ppjb_dev, 'no_ppjb_akad' => 'AKAD-' . substr($record->id_kavling, 0, 15) . '-' . now()->format('ymdHis')],
            Akad::class => ['id_kavling' => $record->id_kavling, 'no_ppjb_akad' => $record->no_ppjb_akad, 'no_bast' => 'BAST-' . substr($record->id_kavling, 0, 15) . '-' . now()->format('ymdHis')],
            default => [],
        };
    }

    public function getNextStageRelation(Model $record): ?string
    {
        return match ($this->getNextStageClass($record)) {
            BiChecking::class => 'biChecking',
            Psjb::class => 'psjb',
            Pemberkasan::class => 'pemberkasan',
            ProsesBank::class => 'prosesBank',
            PpjbDev::class => 'ppjbDev',
            Akad::class => 'akad',
            Bast::class => 'bast',
            default => null,
        };
    }

    public function nextStageExists(Model $record): bool
    {
        $nextClass = $this->getNextStageClass($record);
        if (!$nextClass) {
            return true;
        }

        $kavling = $record->kavling;
        if (!$kavling) {
            return false;
        }

        $relation = $this->getNextStageRelation($record);
        return $kavling->$relation !== null;
    }

    public function findOrCreateNextStage(Model $record): ?Model
    {
        $nextClass = $this->getNextStageClass($record);
        if (!$nextClass) {
            return null;
        }

        $kavling = $record->kavling;
        if (!$kavling) {
            return null;
        }

        $relation = $this->getNextStageRelation($record);

        $existing = $kavling->$relation;
        if ($existing) {
            return $existing;
        }

        $data = $this->getNextStageData($record);
        $next = new $nextClass();
        $next->fill($data);
        $next->save();

        return $next;
    }

    public function finalizeStage(Model $record): void
    {
        $nextClass = $this->getNextStageClass($record);
        if (!$nextClass) {
            return;
        }

        app(LeadTimeService::class)->calculate($record, Carbon::now());
        $record->saveQuietly();
    }

    public function getNextStageEditUrl(Model $record): ?string
    {
        $this->finalizeStage($record);

        $next = $this->findOrCreateNextStage($record);
        if (!$next) {
            return null;
        }

        $resourceClass = match (get_class($next)) {
            BiChecking::class => \App\Filament\Resources\BiCheckings\BiCheckingResource::class,
            Psjb::class => \App\Filament\Resources\Psjbs\PsjbResource::class,
            Pemberkasan::class => \App\Filament\Resources\Pemberkasans\PemberkasanResource::class,
            ProsesBank::class => \App\Filament\Resources\ProsesBanks\ProsesBankResource::class,
            PpjbDev::class => \App\Filament\Resources\PpjbDevs\PpjbDevResource::class,
            Akad::class => \App\Filament\Resources\Akads\AkadResource::class,
            Bast::class => \App\Filament\Resources\Basts\BastResource::class,
            default => null,
        };

        return $resourceClass ? $resourceClass::getUrl('edit', ['record' => $next]) : null;
    }
}
