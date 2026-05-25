<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kavlings', function (Blueprint $table) {
            $table->string('id_kavling', 50)->primary();
            $table->string('proyek', 100);
            $table->string('kode_kavling', 20);
            $table->decimal('luas_bangunan_m2', 5, 2)->nullable();
            $table->decimal('luas_tanah_m2', 5, 2)->nullable();
            $table->string('progres_bangun', 20)->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->enum('status_kavling', ['Tersedia', 'Dipesan', 'Terjual'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kavlings');
    }
};
