<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psjb', function (Blueprint $table) {
            $table->id();
            $table->string('id_kavling', 50);
            $table->string('id_kons', 50)->nullable();
            $table->string('id_psjb', 50)->nullable();
            $table->date('tanggal_psjb')->nullable();
            $table->string('nama_koordinator', 100)->nullable();
            $table->string('nama_sales', 100)->nullable();
            $table->decimal('harga_unit', 15, 2)->nullable();
            $table->date('tanggal_utj')->nullable();
            $table->decimal('utj', 15, 2)->nullable();
            $table->date('tanggal_dp_klt')->nullable();
            $table->decimal('dp', 15, 2)->nullable();
            $table->string('klt', 50)->nullable();
            $table->text('detail_klt')->nullable();
            $table->enum('cara_pembayaran', ['FLPP', 'Cash', 'Cash Bertahap'])->nullable();
            $table->string('id_promo', 20)->nullable();
            $table->integer('lead_time_hari')->nullable();
            $table->enum('status', ['ontime', 'terlambat'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kavling')->references('id_kavling')->on('kavlings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_promo')->references('id_promo')->on('promos')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psjb');
    }
};
