<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisiPemberkasan extends Model
{
    protected $table = 'revisi_pemberkasans';

    protected $fillable = [
        'id_pemberkasan',
        'kategori',
        'detail',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function pemberkasan()
    {
        return $this->belongsTo(Pemberkasan::class, 'id_pemberkasan');
    }
}
