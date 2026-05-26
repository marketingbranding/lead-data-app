<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bi_checking', fn (Blueprint $table) => $table->softDeletes());
        Schema::table('psjb', fn (Blueprint $table) => $table->softDeletes());
        Schema::table('pemberkasan', fn (Blueprint $table) => $table->softDeletes());
        Schema::table('proses_bank', fn (Blueprint $table) => $table->softDeletes());
        Schema::table('ppjb_dev', fn (Blueprint $table) => $table->softDeletes());
        Schema::table('akad', fn (Blueprint $table) => $table->softDeletes());
        Schema::table('bast', fn (Blueprint $table) => $table->softDeletes());
    }

    public function down(): void
    {
        Schema::table('bi_checking', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('psjb', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('pemberkasan', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('proses_bank', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('ppjb_dev', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('akad', fn (Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('bast', fn (Blueprint $table) => $table->dropSoftDeletes());
    }
};
