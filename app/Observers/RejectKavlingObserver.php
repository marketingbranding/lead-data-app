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
use Illuminate\Support\Facades\DB;

class RejectKavlingObserver
{
    protected const STAGE_TO_LEAD_TIME = [
        'Bi Checking' => null,
        'PSJB' => 'psjb',
        'Pemberkasan' => 'pemberkasan',
        'Proses Bank' => 'proses bank',
        'PPJB Dev' => 'ppjb_dev',
        'Akad' => 'akad',
        'BAST' => 'bast',
    ];

    public function saved(ProsesBank $prosesBank): void
    {
        if ($prosesBank->jenis_respon !== 'Reject') {
            return;
        }

        $kavling = $prosesBank->kavling;
        if (!$kavling) {
            return;
        }

        $konsumen = $kavling->konsumens()->where('status_konsumen', 'aktif')->first();
        if ($konsumen) {
            $konsumen->status_konsumen = 'batal';
            $konsumen->saveQuietly();
        }

        $lastLog = PipelineLog::where('id_kavling', $kavling->id_kavling)
            ->whereNull('tanggal_keluar')
            ->latest('tanggal_masuk')
            ->first();

        if ($lastLog) {
            $now = now();
            $lastLog->tanggal_keluar = $now;
            $lastLog->lead_time_hari = (int) Carbon::parse($lastLog->tanggal_masuk)->diffInWeekdays($now);

            $targetKey = static::STAGE_TO_LEAD_TIME[$lastLog->tahap_tujuan] ?? null;
            if ($targetKey) {
                $target = DB::table('lead_times')->where('tahap_tujuan', $targetKey)->value('target_hari_kerja');
                $lastLog->status = $target !== null
                    ? ($lastLog->lead_time_hari > $target ? 'terlambat' : 'ontime')
                    : null;
            }

            $lastLog->save();
        }

        foreach ([BiChecking::class, Psjb::class, Pemberkasan::class, ProsesBank::class, PpjbDev::class, Akad::class, Bast::class] as $modelClass) {
            $modelClass::where('id_kavling', $kavling->id_kavling)->delete();
        }
    }
}
