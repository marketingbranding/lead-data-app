<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisiProsesBank extends Model
{
    protected $table = 'revisi_proses_banks';

    protected $fillable = [
        'id_proses_bank',
        'kategori',
        'detail',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function prosesBank()
    {
        return $this->belongsTo(ProsesBank::class, 'id_proses_bank');
    }
}
