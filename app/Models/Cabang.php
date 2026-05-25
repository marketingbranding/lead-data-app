<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabangs';

    protected $fillable = [
        'nama',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function proyeks()
    {
        return $this->hasMany(Proyek::class, 'cabang_id');
    }
}
