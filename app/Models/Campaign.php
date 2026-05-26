<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'campaign_id',
        'cabang_id',
        'proyek_id',
        'kategori_promosi',
        'sumber_promosi',
        'tanggal_mulai',
        'tanggal_selesai',
        'budget',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'budget' => 'decimal:2',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class);
    }

    public function dailyLeadOfflines()
    {
        return $this->hasMany(DailyLeadOffline::class);
    }

    public function dailyLeadOnlines()
    {
        return $this->hasMany(DailyLeadOnline::class);
    }

    public function scopeBerlangsung($q)
    {
        return $q->where('status', 'Berlangsung');
    }

    public function scopeByCabang($q, $cabangId)
    {
        return $q->where('cabang_id', $cabangId);
    }
}
