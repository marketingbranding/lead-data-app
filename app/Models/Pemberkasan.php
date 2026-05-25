<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemberkasan extends Model
{
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

    public function getStatusDataAttribute(): string
    {
        $mandatory = ['tipe_pemberkasan'];
        if ($this->tipe_pemberkasan !== 'CASH') {
            $mandatory[] = 'tanggal_terima_bank';
            $mandatory[] = 'bank';
        }
        foreach ($mandatory as $field) {
            if (blank($this->{$field})) return 'Data Belum Lengkap';
        }
        return 'Data Lengkap';
    }

    public function getJenisPipelineAttribute(): string
    {
        return $this->kavling?->isCashPath() ? 'CASH' : 'KPR';
    }
}
