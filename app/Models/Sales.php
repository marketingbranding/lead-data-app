<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'nik_sales';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik_sales',
        'nama_sales',
        'nik_koordinator',
        'nama_koordinator',
        'cabang',
        'status',
    ];

    public function koordinator()
    {
        return $this->belongsTo(Sales::class, 'nik_koordinator', 'nik_sales');
    }

    public function anggota()
    {
        return $this->hasMany(Sales::class, 'nik_koordinator', 'nik_sales');
    }
}
