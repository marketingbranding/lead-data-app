<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dana_talangans', function (Blueprint $table) {
            $table->foreignId('proyek_id')->nullable()->after('cabang_id')->constrained('proyeks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dana_talangans', function (Blueprint $table) {
            $table->dropForeign(['proyek_id']);
            $table->dropColumn('proyek_id');
        });
    }
};
