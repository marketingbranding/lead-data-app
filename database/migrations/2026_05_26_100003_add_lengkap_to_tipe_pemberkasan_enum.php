<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pemberkasan MODIFY COLUMN tipe_pemberkasan ENUM('registrasi', 'banding', 'pip', 'revisi', 'lengkap') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pemberkasan MODIFY COLUMN tipe_pemberkasan ENUM('registrasi', 'banding', 'pip', 'revisi') NULL");
    }
};
