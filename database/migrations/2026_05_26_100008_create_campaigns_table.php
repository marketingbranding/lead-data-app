<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_id', 30)->unique();
            $table->unsignedBigInteger('cabang_id');
            $table->unsignedBigInteger('proyek_id');
            $table->enum('kategori_promosi', ['Online', 'Offline']);
            $table->string('sumber_promosi', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->decimal('budget', 15, 2)->default(0);
            $table->enum('status', ['Draft', 'Berlangsung', 'Jeda', 'Selesai'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('cabang_id')->references('id')->on('cabangs')->cascadeOnUpdate();
            $table->foreign('proyek_id')->references('id')->on('proyeks')->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
