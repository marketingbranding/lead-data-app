<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bi_checking', function (Blueprint $table) {
            $table->id();
            $table->string('id_kavling', 50);
            $table->string('no_ktp', 20)->nullable();
            $table->string('id_kons', 50)->nullable();
            $table->date('tanggal_slik')->nullable();
            $table->string('hasil_slik', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kavling')->references('id_kavling')->on('kavlings')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bi_checking');
    }
};
