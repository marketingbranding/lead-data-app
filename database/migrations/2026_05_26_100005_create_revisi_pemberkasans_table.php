<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisi_pemberkasans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemberkasan');
            $table->string('kategori', 100);
            $table->text('detail')->nullable();
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->timestamps();

            $table->foreign('id_pemberkasan')->references('id')->on('pemberkasan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi_pemberkasans');
    }
};
