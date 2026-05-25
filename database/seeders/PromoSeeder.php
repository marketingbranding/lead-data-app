<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('../../references/masterdata/Database Admin Master - MEI 2026 - promo.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv->getRecords() as $record) {
            $records[] = [
                'id_promo' => $record['id_promo'],
                'nama_promo' => $this->parseNullable($record['nama_promo']),
                'tanggal_mulai' => $this->parseDate($record['tanggal_mulai']),
                'tanggal_selesai' => $this->parseDate($record['tanggal_selesai']),
                'keterangan' => $this->parseNullable($record['keterangan']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($records) >= 100) {
                DB::table('promos')->insert($records);
                $records = [];
            }
        }
        if (!empty($records)) {
            DB::table('promos')->insert($records);
        }
    }

    private function parseDate($value): ?string
    {
        if ($value === null || $value === '' || in_array(trim($value), ['Data Belum Lengkap', '---', 'Kosong', 'KOSONG'])) return null;
        try {
            return Carbon::createFromFormat('m/d/Y', trim($value))->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('n/j/Y', trim($value))->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    private function parseNullable($value): ?string
    {
        if ($value === null || $value === '' || in_array(trim($value), ['Data Belum Lengkap', '---', 'Kosong', 'KOSONG'])) return null;
        return trim($value);
    }
}
