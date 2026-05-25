<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pipeline_logs', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('id_kavling', 50);
            $table->string('tahap_asal', 50)->nullable();
            $table->string('tahap_tujuan', 50);
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->integer('lead_time_hari')->nullable();
            $table->enum('status', ['ontime', 'terlambat'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kavling')->references('id_kavling')->on('kavlings')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipeline_logs');
    }
};
