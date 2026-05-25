<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';
    protected $primaryKey = 'id_bank';

    protected $fillable = [
        'bank',
        'kc_unit',
    ];
}
