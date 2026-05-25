<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kavling extends Model
{
    protected $table = 'kavlings';
    protected $primaryKey = 'id_kavling';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kavling',
        'proyek_id',
        'cabang_id',
        'kode_kavling',
        'luas_bangunan_m2',
        'luas_tanah_m2',
        'progres_bangun',
        'harga',
        'status_kavling',
    ];

    protected $casts = [
        'luas_bangunan_m2' => 'decimal:2',
        'luas_tanah_m2' => 'decimal:2',
        'harga' => 'decimal:2',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    public function konsumens()
    {
        return $this->hasMany(Konsumen::class, 'id_kavling', 'id_kavling');
    }

    public function biChecking()
    {
        return $this->hasOne(BiChecking::class, 'id_kavling', 'id_kavling');
    }

    public function psjb()
    {
        return $this->hasOne(Psjb::class, 'id_kavling', 'id_kavling');
    }

    public function pemberkasan()
    {
        return $this->hasOne(Pemberkasan::class, 'id_kavling', 'id_kavling');
    }

    public function prosesBank()
    {
        return $this->hasOne(ProsesBank::class, 'id_kavling', 'id_kavling');
    }

    public function ppjbDev()
    {
        return $this->hasOne(PpjbDev::class, 'id_kavling', 'id_kavling');
    }

    public function akad()
    {
        return $this->hasOne(Akad::class, 'id_kavling', 'id_kavling');
    }

    public function bast()
    {
        return $this->hasOne(Bast::class, 'id_kavling', 'id_kavling');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'id_kavling', 'id_kavling');
    }

    public function pipelineLogs()
    {
        return $this->hasMany(PipelineLog::class, 'id_kavling', 'id_kavling');
    }

    public function isCashPath(): bool
    {
        return $this->konsumens()->where('status_cash', 'YA')->exists();
    }

    public function getStatusKavlingAttribute(): string
    {
        if ($this->relationLoaded('bast') ? $this->bast : $this->bast()->exists()) {
            return 'Terjual';
        }
        if ($this->relationLoaded('konsumens') ? $this->konsumens->isNotEmpty() : $this->konsumens()->exists()) {
            return 'Dipesan';
        }
        return 'Tersedia';
    }
}
