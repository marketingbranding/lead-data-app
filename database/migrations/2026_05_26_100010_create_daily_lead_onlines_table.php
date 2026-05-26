<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_lead_onlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->date('tanggal_laporan');
            $table->integer('klik_tautan')->default(0);
            $table->integer('lead_masuk')->default(0);
            $table->integer('respon')->default(0);
            $table->integer('tahap_diskusi')->default(0);
            $table->integer('cek_lokasi')->default(0);
            $table->integer('closing_utj')->default(0);
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('campaigns')->cascadeOnDelete();
            $table->unique(['campaign_id', 'tanggal_laporan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_lead_onlines');
    }
};
