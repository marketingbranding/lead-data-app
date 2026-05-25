<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsumens', function (Blueprint $table) {
            $table->id('id_konsumen');
            $table->string('id_kavling', 50);
            $table->string('no_ktp', 20)->nullable();
            $table->string('nama_konsumen', 150);
            $table->date('tanggal_lahir')->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('detail_pekerjaan', 100)->nullable();
            $table->string('umur', 10)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kabupaten_kota', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('nama_kondar', 100)->nullable();
            $table->string('no_hp_kondar', 20)->nullable();
            $table->enum('status_cash', ['YA', 'TIDAK'])->default('TIDAK');
            $table->enum('status_data', ['Data Lengkap', 'Data Belum Lengkap'])->default('Data Belum Lengkap');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kavling')->references('id_kavling')->on('kavlings')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsumens');
    }
};
