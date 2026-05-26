<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanaTalangan extends Model
{
    protected $table = 'dana_talangans';

    protected $fillable = [
        'cabang_id',
        'proyek_id',
        'kavling_id',
        'konsumen_id',
        'bank_id',
        'tgl_akad',
        'tgl_bbg_due',
        'tgl_pengajuan_dana_talangan',
        'tgl_pengembalian_dana_talangan',
        'penyelesaian',
    ];

    protected $casts = [
        'tgl_akad' => 'date',
        'tgl_bbg_due' => 'date',
        'tgl_pengajuan_dana_talangan' => 'date',
        'tgl_pengembalian_dana_talangan' => 'date',
    ];

    public function getStatusBbgAttribute(): string
    {
        return $this->tgl_bbg_due && now()->greaterThan($this->tgl_bbg_due)
            ? 'Expired'
            : 'Active';
    }

    public function getBbgRemainingDaysAttribute(): ?int
    {
        if (!$this->tgl_bbg_due) {
            return null;
        }

        $now = now()->startOfDay();
        if ($now->greaterThan($this->tgl_bbg_due)) {
            return null;
        }

        return (int) $now->diffInDays($this->tgl_bbg_due);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'kavling_id', 'id_kavling');
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'konsumen_id', 'id_konsumen');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
