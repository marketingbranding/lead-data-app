<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeadTimeService
{
    protected array $tahapMapping = [
        'psjb'         => 'psjb',
        'pemberkasan'  => 'pemberkasan',
        'proses_bank'  => 'proses bank',
        'ppjb_dev'     => 'ppjb_dev',
        'akad'         => 'akad',
        'bast'         => 'bast',
    ];

    public function calculate(Model $model, ?Carbon $endDate = null): void
    {
        $table = $model->getTable();

        if (!isset($this->tahapMapping[$table])) {
            return;
        }

        if (!$model->created_at) {
            return;
        }

        $end = $endDate ?? $model->updated_at ?? now();
        $leadTimeHari = (int) Carbon::parse($model->created_at)->diffInWeekdays(Carbon::parse($end));

        $tahapTujuan = $this->tahapMapping[$table];
        $target = DB::table('lead_times')
            ->where('tahap_tujuan', $tahapTujuan)
            ->value('target_hari_kerja');

        if ($target === null) {
            return;
        }

        $status = $leadTimeHari > $target ? 'terlambat' : 'ontime';

        $model->lead_time_hari = $leadTimeHari;
        $model->status = $status;
    }
}
