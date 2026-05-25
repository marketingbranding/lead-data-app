<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proses_bank', function (Blueprint $table) {
            $table->id();
            $table->string('id_kavling', 50);
            $table->string('id_berkas', 50)->nullable();
            $table->string('no_sp3k', 100)->nullable();
            $table->string('jenis_respon', 20)->nullable();
            $table->decimal('approved_plafond', 15, 2)->nullable();
            $table->string('approved_tenor', 20)->nullable();
            $table->integer('lead_time_hari')->nullable();
            $table->enum('status', ['ontime', 'terlambat'])->nullable();
            $table->string('kategori_revisi', 100)->nullable();
            $table->text('detail_revisi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kavling')->references('id_kavling')->on('kavlings')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proses_bank');
    }
};
