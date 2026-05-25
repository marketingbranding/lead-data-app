<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promos';
    protected $primaryKey = 'id_promo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_promo',
        'nama_promo',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function psjb()
    {
        return $this->hasMany(Psjb::class, 'id_promo', 'id_promo');
    }
}
