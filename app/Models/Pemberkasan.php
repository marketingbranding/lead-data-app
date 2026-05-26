<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pemberkasan extends Model
{
    use SoftDeletes;
    protected $table = 'pemberkasan';

    protected $fillable = [
        'id_kavling',
        'id_psjb',
        'id_berkas',
        'tanggal_terima_bank',
        'bank',
        'kc_unit',
        'request_plafond',
        'request_tenor',
        'tipe_pemberkasan',
        'lead_time_hari',
        'status',
        'keterangan',
        'status_data',
    ];

    protected $casts = [
        'tanggal_terima_bank' => 'date',
        'request_plafond' => 'decimal:2',
    ];

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }

    public function psjb()
    {
        return $this->belongsTo(Psjb::class, 'id_psjb', 'id_psjb');
    }

    public function prosesBank()
    {
        return $this->hasOne(ProsesBank::class, 'id_berkas', 'id_berkas');
    }

    public function revisiPemberkasans()
    {
        return $this->hasMany(RevisiPemberkasan::class, 'id_pemberkasan');
    }

    public function getStatusDataAttribute(): string
    {
        $mandatory = ['tipe_pemberkasan'];
        foreach ($mandatory as $field) {
            if (blank($this->{$field})) return 'Data Belum Lengkap';
        }
        return 'Data Lengkap';
    }

    public function getStatusAttribute(): ?string
    {
        $target = DB::table('lead_times')->where('tahap_tujuan', 'pemberkasan')->value('target_hari_kerja');

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
