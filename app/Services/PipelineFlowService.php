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
    public function getNextStageForKonsumen(Konsumen $record): ?string
    {
        $kavling = $record->kavling;
        if (!$kavling) {
            return $record->status_cash === 'YA' ? PpjbDev::class : BiChecking::class;
        }

        $chain = $record->status_cash === 'YA'
            ? [PpjbDev::class, Akad::class, Bast::class]
            : [BiChecking::class, Psjb::class, Pemberkasan::class, ProsesBank::class, PpjbDev::class, Akad::class, Bast::class];

        $relationMap = [
            BiChecking::class => 'biChecking',
            Psjb::class => 'psjb',
            Pemberkasan::class => 'pemberkasan',
            ProsesBank::class => 'prosesBank',
            PpjbDev::class => 'ppjbDev',
            Akad::class => 'akad',
            Bast::class => 'bast',
        ];

        foreach ($chain as $stageClass) {
            $relation = $relationMap[$stageClass];
            $existing = $kavling->$relation;

            if (!$existing) {
                return $stageClass;
            }

            if ($stageClass === ProsesBank::class) {
                if (in_array($existing->jenis_respon, ['Reject', 'Revisi'])) {
                    return null;
                }
            }

            if ($existing->status_data !== 'Data Lengkap') {
                return $stageClass;
            }
        }

        return null;
    }

    public function getNextStageClass(Model $record): ?string
    {
        return match (get_class($record)) {
            Konsumen::class => $this->getNextStageForKonsumen($record),
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
            Konsumen::class => match ($this->getNextStageClass($record)) {
                BiChecking::class => 'Lanjut ke Bi Checking',
                Psjb::class => 'Lanjut ke PSJB',
                Pemberkasan::class => 'Lanjut ke Pemberkasan',
                ProsesBank::class => 'Lanjut ke Proses Bank',
                PpjbDev::class => 'Lanjut ke PPJB Developer',
                Akad::class => 'Lanjut ke Akad',
                Bast::class => 'Lanjut ke BAST',
                default => null,
            },
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
            Konsumen::class => match ($nextClass) {
                BiChecking::class => [
                    'id_kavling' => $record->id_kavling,
                    'no_ktp' => $record->no_ktp,
                    'id_kons' => $record->id_konsumen,
                ],
                Psjb::class => [
                    'id_kavling' => $record->id_kavling,
                    'id_kons' => $record->id_konsumen,
                    'id_psjb' => 'PSJB-' . substr($record->id_kavling, 0, 15) . '-' . now()->format('ymdHis'),
                ],
                Pemberkasan::class => [
                    'id_kavling' => $record->id_kavling,
                    'id_psjb' => $record->kavling?->psjb?->id_psjb,
                    'id_berkas' => 'BRK-' . substr($record->id_kavling, 0, 15) . '-' . now()->format('ymdHis'),
                ],
                ProsesBank::class => [
                    'id_kavling' => $record->id_kavling,
                    'id_berkas' => $record->kavling?->pemberkasan?->id_berkas,
                ],
                PpjbDev::class => [
                    'id_kavling' => $record->id_kavling,
                    'no_sp3k' => $record->kavling?->prosesBank?->no_sp3k,
                ],
                Akad::class => [
                    'id_kavling' => $record->id_kavling,
                    'id_ppjb_dev' => $record->kavling?->ppjbDev?->id_ppjb_dev,
                    'no_ppjb_akad' => 'AKAD-' . substr($record->id_kavling, 0, 15) . '-' . now()->format('ymdHis'),
                ],
                Bast::class => [
                    'id_kavling' => $record->id_kavling,
                    'no_ppjb_akad' => $record->kavling?->akad?->no_ppjb_akad,
                    'no_bast' => 'BAST-' . substr($record->id_kavling, 0, 15) . '-' . now()->format('ymdHis'),
                ],
                default => ['id_kavling' => $record->id_kavling],
            },
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
