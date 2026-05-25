<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->string('nik_sales', 20)->primary();
            $table->string('nama_sales', 100);
            $table->string('nik_koordinator', 20)->nullable();
            $table->string('nama_koordinator', 100)->nullable();
            $table->string('cabang', 100)->nullable();
            $table->enum('status', ['Aktif', 'OUT', 'OJT'])->default('Aktif');
            $table->timestamps();

            $table->foreign('nik_koordinator')->references('nik_sales')->on('sales')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
