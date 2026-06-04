<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE konsumens MODIFY COLUMN status_konsumen ENUM('aktif', 'batal', 'mundur') NOT NULL DEFAULT 'aktif'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE konsumens MODIFY COLUMN status_konsumen ENUM('aktif', 'batal') NOT NULL DEFAULT 'aktif'");
    }
};
