<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_lead_offlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->date('tanggal_laporan');
            $table->integer('lead_didapat')->default(0);
            $table->integer('kunjungan_lokasi')->nullable();
            $table->integer('closing_utj')->nullable();
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('campaigns')->cascadeOnDelete();
            $table->unique(['campaign_id', 'tanggal_laporan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_lead_offlines');
    }
};
