<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    protected $table = 'konsumens';
    protected $primaryKey = 'id_konsumen';

    protected $fillable = [
        'id_kavling',
        'no_ktp',
        'nama_konsumen',
        'tanggal_lahir',
        'pekerjaan',
        'detail_pekerjaan',
        'umur',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'no_hp',
        'nama_kondar',
        'no_hp_kondar',
        'status_cash',
        'status_data',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function getStatusDataAttribute(): string
    {
        return !blank($this->nama_konsumen) && !blank($this->no_ktp) && !blank($this->no_hp) && !blank($this->pekerjaan) && !blank($this->tanggal_lahir) && !blank($this->alamat) && !blank($this->kelurahan) && !blank($this->kecamatan) && !blank($this->kabupaten_kota)
            ? 'Data Lengkap'
            : 'Data Belum Lengkap';
    }

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }
}
