<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisi_proses_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_proses_bank');
            $table->enum('kategori', ['Rekening Koran', 'Slip Gaji']);
            $table->text('detail')->nullable();
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->timestamps();

            $table->foreign('id_proses_bank')->references('id')->on('proses_bank')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi_proses_banks');
    }
};
