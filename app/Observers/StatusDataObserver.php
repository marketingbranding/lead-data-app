<?php

namespace App\Observers;

use App\Models\Akad;
use App\Models\Bast;
use App\Models\BiChecking;
use App\Models\Konsumen;
use App\Models\Pemberkasan;
use App\Models\PpjbDev;
use App\Models\ProsesBank;
use App\Models\Psjb;
use Illuminate\Database\Eloquent\Model;

class StatusDataObserver
{
    protected const MANDATORY_FIELDS = [
        Konsumen::class => ['nama_konsumen', 'no_ktp', 'no_hp', 'pekerjaan', 'tanggal_lahir', 'alamat', 'kelurahan', 'kecamatan', 'kabupaten_kota'],
        BiChecking::class => ['no_ktp', 'tanggal_slik', 'hasil_slik'],
        Psjb::class => ['tanggal_psjb', 'nama_koordinator', 'nama_sales', 'harga_unit', 'cara_pembayaran'],
        Pemberkasan::class => ['tipe_pemberkasan'],
        ProsesBank::class => ['no_sp3k', 'jenis_respon', 'approved_plafond'],
        PpjbDev::class => ['tanggal_sp3k', 'tanggal_ttd_ppjb'],
        Akad::class => ['tanggal_akad'],
        Bast::class => ['tanggal_bast'],
    ];

    public function saving(Model $model): void
    {
        $fields = static::MANDATORY_FIELDS[get_class($model)] ?? null;

        if ($fields === null) {
            return;
        }

        $lengkap = true;

        foreach ($fields as $field) {
            if (blank($model->{$field})) {
                $lengkap = false;
                break;
            }
        }

        $model->status_data = $lengkap ? 'Data Lengkap' : 'Data Belum Lengkap';
    }
}
