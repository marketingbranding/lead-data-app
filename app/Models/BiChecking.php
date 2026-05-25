<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiChecking extends Model
{
    protected $table = 'bi_checking';

    protected $fillable = [
        'id_kavling',
        'no_ktp',
        'id_kons',
        'tanggal_slik',
        'hasil_slik',
        'keterangan',
        'status_data',
    ];

    protected $casts = [
        'tanggal_slik' => 'date',
    ];

    public function getStatusDataAttribute(): string
    {
        return !blank($this->no_ktp) && !blank($this->tanggal_slik) && !blank($this->hasil_slik)
            ? 'Data Lengkap'
            : 'Data Belum Lengkap';
    }

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }
}
