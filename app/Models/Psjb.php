<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Psjb extends Model
{
    protected $table = 'psjb';

    protected $fillable = [
        'id_kavling',
        'id_kons',
        'id_psjb',
        'tanggal_psjb',
        'nama_koordinator',
        'nama_sales',
        'harga_unit',
        'tanggal_utj',
        'utj',
        'tanggal_dp_klt',
        'dp',
        'klt',
        'detail_klt',
        'cara_pembayaran',
        'id_promo',
        'lead_time_hari',
        'status',
        'keterangan',
        'status_data',
    ];

    protected $casts = [
        'tanggal_psjb' => 'date',
        'tanggal_utj' => 'date',
        'tanggal_dp_klt' => 'date',
        'harga_unit' => 'decimal:2',
        'utj' => 'decimal:2',
        'dp' => 'decimal:2',
    ];

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'id_promo', 'id_promo');
    }

    public function pemberkasan()
    {
        return $this->hasOne(Pemberkasan::class, 'id_psjb', 'id_psjb');
    }

    public function getStatusDataAttribute(): string
    {
        return !blank($this->tanggal_psjb) && !blank($this->nama_koordinator) && !blank($this->nama_sales) && !blank($this->harga_unit) && !blank($this->cara_pembayaran)
            ? 'Data Lengkap'
            : 'Data Belum Lengkap';
    }

    public function getJenisPipelineAttribute(): string
    {
        return $this->kavling?->isCashPath() ? 'CASH' : 'KPR';
    }
}
