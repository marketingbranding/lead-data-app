<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringJalan extends Model
{
    protected $fillable = [
        'periode',
        'cabang_id',
        'proyek_id',
        'total_konsumen_survey',
        'konsumen_insiden_jalan',
        'batal_beli_karena_jalan',
        'catatan_lapangan',
    ];

    protected $casts = [
        'periode' => 'date',
    ];

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }
}
