<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsumens', function (Blueprint $table) {
            $table->string('tahap_terakhir', 100)->nullable()->after('status_konsumen');
        });
    }

    public function down(): void
    {
        Schema::table('konsumens', function (Blueprint $table) {
            $table->dropColumn('tahap_terakhir');
        });
    }
};
