<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class KavlingSeeder extends Seeder
{
    public function run(): void
    {
        $proyekMap = DB::table('proyeks')->pluck('id', 'nama_proyek');

        $csv = Reader::createFromPath(database_path('../../references/masterdata/Database Admin Master - MEI 2026 - data_kav.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv->getRecords() as $record) {
            $proyekName = trim($record['proyek'] ?? '');
            $proyekId = $proyekMap[$proyekName] ?? null;

            $records[] = [
                'id_kavling' => $record['id_kavling'],
                'proyek_id' => $proyekId,
                'kode_kavling' => $record['kode_kavling'] ?: null,
                'luas_bangunan_m2' => $record['luas_bangunan_m2'] !== '' ? (float) $record['luas_bangunan_m2'] : null,
                'luas_tanah_m2' => $record['luas_tanah_m2'] !== '' ? (float) $record['luas_tanah_m2'] : null,
                'progres_bangun' => $record['progres_bangun'] ?: null,
                'harga' => null,
                'status_kavling' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($records) >= 100) {
                DB::table('kavlings')->insert($records);
                $records = [];
            }
        }
        if (!empty($records)) {
            DB::table('kavlings')->insert($records);
        }
    }
}
