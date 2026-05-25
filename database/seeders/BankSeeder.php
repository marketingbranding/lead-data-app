<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('../../references/masterdata/Database Admin Master - MEI 2026 - data_bank.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv->getRecords() as $record) {
            $records[] = [
                'bank' => $this->parseNullable($record['bank']),
                'kc_unit' => $this->parseNullable($record['kc/unit']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($records) >= 100) {
                DB::table('banks')->insert($records);
                $records = [];
            }
        }
        if (!empty($records)) {
            DB::table('banks')->insert($records);
        }
    }

    private function parseNullable($value): ?string
    {
        if ($value === null || $value === '' || in_array(trim($value), ['Data Belum Lengkap', '---', 'Kosong', 'KOSONG'])) return null;
        return trim($value);
    }
}
