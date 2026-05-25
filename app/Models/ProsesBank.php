<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProsesBank extends Model
{
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
        'kategori_revisi',
        'detail_revisi',
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

    public function getStatusDataAttribute(): string
    {
        return !blank($this->no_sp3k) && !blank($this->jenis_respon) && !blank($this->approved_plafond)
            ? 'Data Lengkap'
            : 'Data Belum Lengkap';
    }

    public function getJenisPipelineAttribute(): string
    {
        return $this->kavling?->isCashPath() ? 'CASH' : 'KPR';
    }
}
