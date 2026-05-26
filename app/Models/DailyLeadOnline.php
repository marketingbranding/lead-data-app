<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLeadOnline extends Model
{
    protected $table = 'daily_lead_onlines';

    protected $fillable = [
        'campaign_id',
        'tanggal_laporan',
        'klik_tautan',
        'lead_masuk',
        'respon',
        'tahap_diskusi',
        'cek_lokasi',
        'closing_utj',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
