<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpjbDev extends Model
{
    protected $table = 'ppjb_dev';

    protected $fillable = [
        'id_kavling',
        'no_sp3k',
        'id_ppjb_dev',
        'tanggal_sp3k',
        'tanggal_ttd_ppjb',
        'lead_time_hari',
        'status',
        'keterangan',
        'status_data',
    ];

    protected $casts = [
        'tanggal_sp3k' => 'date',
        'tanggal_ttd_ppjb' => 'date',
    ];

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }

    public function prosesBank()
    {
        return $this->belongsTo(ProsesBank::class, 'no_sp3k', 'no_sp3k');
    }

    public function akad()
    {
        return $this->hasOne(Akad::class, 'id_ppjb_dev', 'id_ppjb_dev');
    }

    public function getStatusDataAttribute(): string
    {
        return !blank($this->tanggal_sp3k) && !blank($this->tanggal_ttd_ppjb)
            ? 'Data Lengkap'
            : 'Data Belum Lengkap';
    }

    public function getJenisPipelineAttribute(): string
    {
        return $this->kavling?->isCashPath() ? 'CASH' : 'KPR';
    }
}
