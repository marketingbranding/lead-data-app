<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id('id_expense');
            $table->string('id_kavling', 50)->nullable();
            $table->string('nama_pengeluaran', 200);
            $table->string('kategori', 100)->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('bukti', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_kavling')->references('id_kavling')->on('kavlings')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
