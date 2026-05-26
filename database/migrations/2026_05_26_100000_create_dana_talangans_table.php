<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dana_talangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabangs')->cascadeOnDelete();
            $table->string('kavling_id', 50);
            $table->foreignId('konsumen_id')->constrained('konsumens', 'id_konsumen')->cascadeOnDelete();
            $table->unsignedBigInteger('bank_id');
            $table->foreign('bank_id')->references('id_bank')->on('banks')->cascadeOnDelete();
            $table->date('tgl_akad');
            $table->date('tgl_bbg_due');
            $table->enum('status_bbg', ['Active', 'Expired'])->default('Active');
            $table->date('tgl_pengajuan_dana_talangan')->nullable();
            $table->date('tgl_pengembalian_dana_talangan')->nullable();
            $table->text('penyelesaian')->nullable();
            $table->timestamps();

            $table->foreign('kavling_id')->references('id_kavling')->on('kavlings')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dana_talangans');
    }
};
