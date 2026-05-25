<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class PsjbSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('../../references/pipelinekpr/2 - PSJB.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv->getRecords() as $record) {
            $idKavling = $this->mapIdKavling($this->parseNullable($record['id_kavling']));
            if ($idKavling === null) continue;

            $records[] = [
                'id_kavling' => $idKavling,
                'id_kons' => $this->parseNullable($record['id_kons']),
                'id_psjb' => $this->parseNullable($record['id_psjb']),
                'tanggal_psjb' => $this->parseDate($record['tanggal_psjb']),
                'nama_koordinator' => $this->parseNullable($record['nama_koordinator']),
                'nama_sales' => $this->parseNullable($record['nama_sales']),
                'harga_unit' => $this->parseCurrency($record['harga_unit']),
                'tanggal_utj' => $this->parseDate($record['tanggal_utj']),
                'utj' => $this->parseCurrency($record['utj']),
                'tanggal_dp_klt' => $this->parseDate($record['tanggal_dp_klt']),
                'dp' => $this->parseCurrency($record['dp']),
                'klt' => $this->parseNullable($record['klt']),
                'detail_klt' => $this->parseNullable($record['detail_klt']),
                'cara_pembayaran' => $this->parseNullable($record['cara_pembayaran']),
                'id_promo' => $this->parseNullable($record['id_promo']),
                'lead_time_hari' => $this->parseNullableInt($record['lead_time_hari']),
                'status' => $this->parseNullable($record['status']),
                'keterangan' => $this->parseNullable($record['keterangan']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($records) >= 100) {
                DB::table('psjb')->insert($records);
                $records = [];
            }
        }
        if (!empty($records)) {
            DB::table('psjb')->insert($records);
        }
    }

    private function mapIdKavling(?string $idKavling): ?string
    {
        if ($idKavling === null) return null;

        // Coba exact match dulu
        if (DB::table('kavlings')->where('id_kavling', $idKavling)->exists()) {
            return $idKavling;
        }

        $patterns = [
            'Pati-' => 'Marison Pati-',
            'Kuwasen-' => 'Marison Regency Kuwasen-',
            'Mlonggo 2-' => 'Marison Regency Jepara 2-',
        ];

        foreach ($patterns as $old => $new) {
            if (str_starts_with($idKavling, $old)) {
                $transformed = $new . substr($idKavling, strlen($old));
                if (DB::table('kavlings')->where('id_kavling', $transformed)->exists()) {
                    return $transformed;
                }
            }
        }

        return null;
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
