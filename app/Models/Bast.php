<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bast extends Model
{
    protected $table = 'bast';

    protected $fillable = [
        'id_kavling',
        'no_ppjb_akad',
        'no_bast',
        'tanggal_bast',
        'lead_time_hari',
        'status',
        'keterangan',
        'status_data',
    ];

    protected $casts = [
        'tanggal_bast' => 'date',
    ];

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }

    public function akad()
    {
        return $this->belongsTo(Akad::class, 'no_ppjb_akad', 'no_ppjb_akad');
    }

    public function getStatusDataAttribute(): string
    {
        return !blank($this->tanggal_bast) ? 'Data Lengkap' : 'Data Belum Lengkap';
    }

    public function getJenisPipelineAttribute(): string
    {
        return $this->kavling?->isCashPath() ? 'CASH' : 'KPR';
    }
}
