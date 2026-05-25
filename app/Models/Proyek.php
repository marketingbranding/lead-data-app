<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    protected $table = 'proyeks';

    protected $fillable = [
        'nama_proyek',
        'cabang_id',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function kavlings()
    {
        return $this->hasMany(Kavling::class, 'proyek_id');
    }
}
