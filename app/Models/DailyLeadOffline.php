<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLeadOffline extends Model
{
    protected $table = 'daily_lead_offlines';

    protected $fillable = [
        'campaign_id',
        'tanggal_laporan',
        'lead_didapat',
        'kunjungan_lokasi',
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
