<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PipelineLog extends Model
{
    protected $table = 'pipeline_logs';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_kavling',
        'id_konsumen',
        'tahap_asal',
        'tahap_tujuan',
        'tanggal_masuk',
        'tanggal_keluar',
        'lead_time_hari',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen', 'id_konsumen');
    }
}
