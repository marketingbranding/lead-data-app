<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('../../references/masterdata/Database Admin Master - MEI 2026 - data_sales.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        $usedNik = [];
        $ojtCounter = 1;
        $outCounter = 1;

        foreach ($csv->getRecords() as $record) {
            $nikSales = trim($record['nik_sales']);
            if ($nikSales === '' || $nikSales === 'nik_sales') continue;

            $originalNik = $nikSales;

            if (isset($usedNik[$nikSales])) {
                if ($nikSales === 'OJT') {
                    $nikSales = 'OJT-' . ($ojtCounter++);
                } elseif ($nikSales === 'OUT') {
                    $nikSales = 'OUT-' . ($outCounter++);
                } else {
                    continue;
                }
            }

            $usedNik[$originalNik] = true;
            $usedNik[$nikSales] = true;

            $records[] = [
                'nik_sales' => $nikSales,
                'nama_sales' => $record['nama_sales'] ?: null,
                'nik_koordinator' => $this->parseNullable($record['nik_koordinator']),
                'nama_koordinator' => $this->parseNullable($record['nama_koordinator']),
                'cabang' => null,
                'status' => $nikSales === 'OUT' || str_starts_with($nikSales, 'OUT-') ? 'OUT' : ($nikSales === 'OJT' || str_starts_with($nikSales, 'OJT-') ? 'OJT' : 'Aktif'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('sales')->insert($records);
    }

    private function parseNullable($value): ?string
    {
        if ($value === null || $value === '' || in_array(trim($value), ['Data Belum Lengkap', '---', 'Kosong', 'KOSONG', 'SKEMA LAMA'])) return null;
        return trim($value);
    }
}
