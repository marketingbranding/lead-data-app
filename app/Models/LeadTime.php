<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadTime extends Model
{
    protected $table = 'lead_times';
    protected $primaryKey = 'id_lead_time';

    protected $fillable = [
        'tahap_awal',
        'tahap_tujuan',
        'proses',
        'target_hari_kerja',
    ];
}
