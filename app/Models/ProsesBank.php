<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProsesBank extends Model
{
    use SoftDeletes;
    protected $table = 'proses_bank';

    protected $fillable = [
        'id_kavling',
        'id_berkas',
        'no_sp3k',
        'jenis_respon',
        'approved_plafond',
        'approved_tenor',
        'lead_time_hari',
        'status',
        'keterangan',
        'status_data',
    ];

    protected $casts = [
        'approved_plafond' => 'decimal:2',
    ];

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }

    public function pemberkasan()
    {
        return $this->belongsTo(Pemberkasan::class, 'id_berkas', 'id_berkas');
    }

    public function ppjbDev()
    {
        return $this->hasOne(PpjbDev::class, 'no_sp3k', 'no_sp3k');
    }

    public function revisiProsesBanks()
    {
        return $this->hasMany(RevisiProsesBank::class, 'id_proses_bank');
    }

    public function getStatusDataAttribute(): string
    {
        return !blank($this->no_sp3k) && !blank($this->jenis_respon) && !blank($this->approved_plafond)
            ? 'Data Lengkap'
            : 'Data Belum Lengkap';
    }

    public function getStatusAttribute(): ?string
    {
        $target = DB::table('lead_times')->where('tahap_tujuan', 'proses bank')->value('target_hari_kerja');

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
