<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pemberkasan')->where('tipe_pemberkasan', 'CASH')->update(['tipe_pemberkasan' => null]);
        DB::statement("ALTER TABLE pemberkasan MODIFY COLUMN tipe_pemberkasan ENUM('registrasi', 'banding', 'pip', 'revisi') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pemberkasan MODIFY COLUMN tipe_pemberkasan ENUM('registrasi', 'CASH') NULL");
    }
};
