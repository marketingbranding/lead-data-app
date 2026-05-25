<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kavlings', function (Blueprint $table) {
            $table->foreignId('proyek_id')->nullable()->after('id_kavling')->constrained('proyeks')->nullOnDelete();
            $table->dropColumn('proyek');
        });
    }

    public function down(): void
    {
        Schema::table('kavlings', function (Blueprint $table) {
            $table->string('proyek')->nullable()->after('id_kavling');
            $table->dropForeign(['proyek_id']);
            $table->dropColumn('proyek_id');
        });
    }
};
