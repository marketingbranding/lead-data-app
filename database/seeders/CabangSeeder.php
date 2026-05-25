<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $cabangs = [
            ['nama' => 'Malang', 'urutan' => 1],
            ['nama' => 'Madiun', 'urutan' => 2],
            ['nama' => 'Solo', 'urutan' => 3],
            ['nama' => 'Magelang', 'urutan' => 4],
            ['nama' => 'Purworejo', 'urutan' => 5],
            ['nama' => 'Purwokerto', 'urutan' => 6],
            ['nama' => 'Jepara', 'urutan' => 7],
            ['nama' => 'Pekalongan', 'urutan' => 8],
            ['nama' => 'Sumedang', 'urutan' => 9],
        ];

        foreach ($cabangs as $cabang) {
            Cabang::updateOrCreate(
                ['nama' => $cabang['nama']],
                $cabang
            );
        }
    }
}
