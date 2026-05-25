<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ProsesBankSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('../../references/pipelinekpr/4 - proses_bank.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv->getRecords() as $record) {
            $records[] = [
                'id_kavling' => $this->mapIdKavling($this->parseNullable($record['id_kavling'])),
                'id_berkas' => $this->parseNullable($record['id_berkas']),
                'no_sp3k' => $this->parseNullable($record['no_sp3k']),
                'jenis_respon' => $this->parseNullable($record['jenis_respon']),
                'approved_plafond' => $this->parseCurrency($record['approved_plafond']),
                'approved_tenor' => $this->parseNullable($record['approved_tenor']),
                'lead_time_hari' => $this->parseNullableInt($record['lead_time_hari']),
                'status' => $this->parseNullable($record['status']),
                'kategori_revisi' => $this->parseNullable($record['kategori_revisi']),
                'detail_revisi' => $this->parseNullable($record['detail_revisi']),
                'keterangan' => $this->parseNullable($record['keterangan']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($records) >= 100) {
                DB::table('proses_bank')->insert($records);
                $records = [];
            }
        }
        if (!empty($records)) {
            DB::table('proses_bank')->insert($records);
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

    private function parseCurrency($value): ?float
    {
        if ($value === null || $value === '' || in_array(trim($value), ['---', 'Kosong', 'KOSONG', 'Data Belum Lengkap'])) return null;
        $cleaned = str_replace(['Rp', ',', ' '], '', trim($value));
        if ($cleaned === '' || !is_numeric($cleaned)) return null;
        return (float) $cleaned;
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
