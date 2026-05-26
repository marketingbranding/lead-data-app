<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsumens', function (Blueprint $table) {
            $table->enum('status_konsumen', ['aktif', 'batal'])->default('aktif')->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('konsumens', function (Blueprint $table) {
            $table->dropColumn('status_konsumen');
        });
    }
};
