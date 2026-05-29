<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_jalans', function (Blueprint $table) {
            $table->id();
            $table->date('periode');
            $table->foreignId('cabang_id')->constrained('cabangs')->cascadeOnDelete();
            $table->foreignId('proyek_id')->constrained('proyeks')->cascadeOnDelete();
            $table->integer('total_konsumen_survey')->default(0);
            $table->integer('konsumen_insiden_jalan')->default(0);
            $table->integer('batal_beli_karena_jalan')->default(0);
            $table->text('catatan_lapangan')->nullable();
            $table->timestamps();

            $table->unique(['cabang_id', 'proyek_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_jalans');
    }
};
