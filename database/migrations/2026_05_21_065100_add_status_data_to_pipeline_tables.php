<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['bi_checking', 'psjb', 'pemberkasan', 'proses_bank', 'ppjb_dev', 'akad', 'bast'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->enum('status_data', ['Data Lengkap', 'Data Belum Lengkap'])
                    ->default('Data Belum Lengkap');
            });
        }
    }

    public function down(): void
    {
        $tables = ['bi_checking', 'psjb', 'pemberkasan', 'proses_bank', 'ppjb_dev', 'akad', 'bast'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('status_data');
            });
        }
    }
};
