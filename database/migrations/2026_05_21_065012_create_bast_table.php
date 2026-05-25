<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bast', function (Blueprint $table) {
            $table->id();
            $table->string('id_kavling', 50);
            $table->string('no_ppjb_akad', 50)->nullable();
            $table->string('no_bast', 50)->nullable();
            $table->date('tanggal_bast')->nullable();
            $table->integer('lead_time_hari')->nullable();
            $table->enum('status', ['ontime', 'terlambat'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kavling')->references('id_kavling')->on('kavlings')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bast');
    }
};
