<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akad extends Model
{
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

    public function getJenisPipelineAttribute(): string
    {
        return $this->kavling?->isCashPath() ? 'CASH' : 'KPR';
    }
}
