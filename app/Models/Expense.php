<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'id_expense';

    protected $fillable = [
        'id_kavling',
        'nama_pengeluaran',
        'kategori',
        'jumlah',
        'tanggal',
        'keterangan',
        'bukti',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function kavling()
    {
        return $this->belongsTo(Kavling::class, 'id_kavling', 'id_kavling');
    }
}
