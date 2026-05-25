<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class LeadTimeSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('../../references/masterdata/Database Admin Master - MEI 2026 - lead_time.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv->getRecords() as $record) {
            $proses = trim($record['proses']);
            $pos = mb_strpos($proses, '-');
            $tahapAwal = $pos !== false ? trim(mb_substr($proses, 0, $pos)) : $proses;

            $records[] = [
                'tahap_awal' => $tahapAwal,
                'tahap_tujuan' => $record['tabel'] ?: null,
                'proses' => $proses ?: null,
                'target_hari_kerja' => $record['target waktu hari kerja'] !== '' ? (int) $record['target waktu hari kerja'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($records) >= 100) {
                DB::table('lead_times')->insert($records);
                $records = [];
            }
        }
        if (!empty($records)) {
            DB::table('lead_times')->insert($records);
        }
    }
}
