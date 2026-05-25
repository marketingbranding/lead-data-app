<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_times', function (Blueprint $table) {
            $table->id('id_lead_time');
            $table->string('tahap_awal', 50);
            $table->string('tahap_tujuan', 50);
            $table->string('proses', 100);
            $table->integer('target_hari_kerja');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_times');
    }
};
