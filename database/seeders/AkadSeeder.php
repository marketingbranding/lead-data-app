<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class AkadSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('../../references/pipelinekpr/6 - akad.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv->getRecords() as $record) {
            $records[] = [
                'id_kavling' => $this->mapIdKavling($this->parseNullable($record['id_kavling'])),
                'id_ppjb_dev' => $this->parseNullable($record['id_ppjb_dev']),
                'no_ppjb_akad' => $this->parseNullable($record['no_ppjb_akad']),
                'tanggal_akad' => $this->parseDate($record['tanggal_akad']),
                'kualitas_akad' => $this->parseNullable($record['kualitas_akad']),
                'lead_time_hari' => $this->parseNullableInt($record['lead_time_hari']),
                'status' => $this->parseNullable($record['status']),
                'keterangan_terlambat' => $this->parseNullable($record['keterangan_terlambat']),
                'keterangan' => $this->parseNullable($record['keterangan']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($records) >= 100) {
                DB::table('akad')->insert($records);
                $records = [];
            }
        }
        if (!empty($records)) {
            DB::table('akad')->insert($records);
        }
    }

    private function mapIdKavling(?string $idKavling): ?string
    {
        if ($idKavling === null) return null;

        $patterns = [
            'Pati-' => 'Marison Pati-',
            'Kuwasen-' => 'Marison Regency Kuwasen-',
            'Mlonggo 2-' => 'Marison Regency Jepara 2-',
        ];

        foreach ($patterns as $old => $new) {
            if (str_starts_with($idKavling, $old)) {
                return $new . substr($idKavling, strlen($old));
            }
        }

        return $idKavling;
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

    private function parseNullableInt($value): ?int
    {
        if ($value === null || $value === '' || in_array(trim($value), ['Data Belum Lengkap', '---', 'Kosong', 'KOSONG'])) return null;
        if (is_numeric(trim($value))) return (int) trim($value);
        return null;
    }
}
