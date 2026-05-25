<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class KonsumenSeeder extends Seeder
{
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('../../references/masterdata/Database Admin Master - MEI 2026 - data_konsumen.csv'));
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv->getRecords() as $record) {
            $nama = trim($record['nama_konsumen'] ?? '');
            if ($nama === '' || $nama === 'Data Belum Lengkap') continue;

            $records[] = [
                'id_kavling' => $this->mapIdKavling($this->parseNullable($record['id_kavling'])),
                'no_ktp' => $this->parseNullable($record['no_ktp']),
                'nama_konsumen' => $nama,
                'tanggal_lahir' => $this->parseDate($record['tanggal_lahir']),
                'pekerjaan' => $this->parseNullable($record['pekerjaan']),
                'detail_pekerjaan' => $this->parseNullable($record['detail_pekerjaan']),
                'umur' => $this->parseNullable($record['umur']),
                'alamat' => $this->parseNullable($record['alamat']),
                'kelurahan' => $this->parseNullable($record['kelurahan']),
                'kecamatan' => $this->parseNullable($record['kecamatan']),
                'kabupaten_kota' => $this->parseNullable($record['kabupaten/kota']),
                'no_hp' => $this->parseNullable($record['no_hp']),
                'nama_kondar' => $this->parseNullable($record['nama_kondar']),
                'no_hp_kondar' => $this->parseNullable($record['no_hp_kondar']),
                'status_cash' => $this->parseNullable($record['status_cash'], 'TIDAK'),
                'status_data' => $this->parseNullable($record['Status'], 'Data Belum Lengkap'),
                'keterangan' => $this->parseNullable($record['keterangan']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($records) >= 100) {
                DB::table('konsumens')->insert($records);
                $records = [];
            }
        }
        if (!empty($records)) {
            DB::table('konsumens')->insert($records);
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

    private function parseNullable($value, ?string $default = null): ?string
    {
        if ($value === null || $value === '' || in_array(trim($value), ['Data Belum Lengkap', '---', 'Kosong', 'KOSONG'])) return $default;
        return trim($value);
    }
}
