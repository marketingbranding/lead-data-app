<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Akad extends Model
{
    use SoftDeletes;
    protected $table = 'akad';

    protected $fillable = [
        'id_kavling',
        'id_ppjb_dev',
        'no_ppjb_akad',
        'tanggal_akad',
        'kualitas_akad',
        'lead_time_hari',
        'status',
        'keterangan_terlambat',
        'keterangan',
        'status_data',
    ];

    protected $casts = [
        'tanggal_akad' => 'date',
    ];

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }

    public function ppjbDev()
    {
        return $this->belongsTo(PpjbDev::class, 'id_ppjb_dev', 'id_ppjb_dev');
    }

    public function bast()
    {
        return $this->hasOne(Bast::class, 'no_ppjb_akad', 'no_ppjb_akad');
    }

    public function getStatusDataAttribute(): string
    {
        return !blank($this->tanggal_akad) ? 'Data Lengkap' : 'Data Belum Lengkap';
    }

    public function getStatusAttribute(): ?string
    {
        $target = DB::table('lead_times')->where('tahap_tujuan', 'akad')->value('target_hari_kerja');

        if ($target === null) {
            return null;
        }

        $hari = $this->lead_time_hari ?? ($this->created_at ? (int) Carbon::parse($this->created_at)->diffInWeekdays(now()) : null);

        if ($hari === null) {
            return null;
        }

        return $hari > $target ? 'terlambat' : 'ontime';
    }

    public function getJenisPipelineAttribute(): string
    {
        return $this->kavling?->isCashPath() ? 'CASH' : 'KPR';
    }
}
