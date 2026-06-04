<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pipeline_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id_konsumen')->nullable()->after('id_kavling');
            $table->foreign('id_konsumen')
                  ->references('id_konsumen')->on('konsumens')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pipeline_logs', function (Blueprint $table) {
            $table->dropForeign(['id_konsumen']);
            $table->dropColumn('id_konsumen');
        });
    }
};
