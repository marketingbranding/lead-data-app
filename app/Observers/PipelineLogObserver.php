<?php

namespace App\Observers;

use App\Models\Akad;
use App\Models\Bast;
use App\Models\BiChecking;
use App\Models\Pemberkasan;
use App\Models\PipelineLog;
use App\Models\PpjbDev;
use App\Models\ProsesBank;
use App\Models\Psjb;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PipelineLogObserver
{
    protected const STAGE_NAMES = [
        BiChecking::class => 'Bi Checking',
        Psjb::class => 'PSJB',
        Pemberkasan::class => 'Pemberkasan',
        ProsesBank::class => 'Proses Bank',
        PpjbDev::class => 'PPJB Dev',
        Akad::class => 'Akad',
        Bast::class => 'BAST',
    ];

    protected const STAGE_TO_LEAD_TIME = [
        'PSJB' => 'psjb',
        'Pemberkasan' => 'pemberkasan',
        'Proses Bank' => 'proses bank',
        'PPJB Dev' => 'ppjb_dev',
        'Akad' => 'akad',
        'BAST' => 'bast',
    ];

    public function created(Model $model): void
    {
        $stageName = static::STAGE_NAMES[get_class($model)] ?? null;

        if ($stageName === null) {
            return;
        }

        $kavlingId = $model->id_kavling;
        $now = $model->created_at;

        $previousLog = PipelineLog::where('id_kavling', $kavlingId)
            ->whereNull('tanggal_keluar')
            ->latest('tanggal_masuk')
            ->first();

        $asal = null;

        if ($previousLog) {
            $asal = $previousLog->tahap_tujuan;
            $previousLog->tanggal_keluar = $now;

            $leadTime = (int) Carbon::parse($previousLog->tanggal_masuk)
                ->diffInWeekdays(Carbon::parse($now));

            $previousLog->lead_time_hari = $leadTime;

            $targetKey = static::STAGE_TO_LEAD_TIME[$previousLog->tahap_tujuan] ?? null;
            if ($targetKey) {
                $target = DB::table('lead_times')
                    ->where('tahap_tujuan', $targetKey)
                    ->value('target_hari_kerja');

                $previousLog->status = $target !== null
                    ? ($leadTime > $target ? 'terlambat' : 'ontime')
                    : null;
            }

            $previousLog->save();
        }

        PipelineLog::create([
            'id_kavling' => $kavlingId,
            'tahap_asal' => $asal,
            'tahap_tujuan' => $stageName,
            'tanggal_masuk' => $now,
        ]);
    }
}
