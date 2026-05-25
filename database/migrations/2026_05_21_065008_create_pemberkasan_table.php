<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemberkasan', function (Blueprint $table) {
            $table->id();
            $table->string('id_kavling', 50);
            $table->string('id_psjb', 50)->nullable();
            $table->string('id_berkas', 50)->nullable();
            $table->date('tanggal_terima_bank')->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('kc_unit', 100)->nullable();
            $table->decimal('request_plafond', 15, 2)->nullable();
            $table->string('request_tenor', 20)->nullable();
            $table->enum('tipe_pemberkasan', ['registrasi', 'CASH'])->nullable();
            $table->integer('lead_time_hari')->nullable();
            $table->enum('status', ['ontime', 'terlambat'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kavling')->references('id_kavling')->on('kavlings')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemberkasan');
    }
};
