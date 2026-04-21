<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Make file_word nullable (since we purge files by setting to null)
            $table->string('file_word')->nullable()->change();

            // Update enum for jenis to include surat_undangan and surat_lainnya
            $table->enum('jenis', ['nota_dinas', 'surat_dinas', 'surat_keputusan', 'surat_pernyataan', 'surat_keterangan', 'surat_undangan', 'surat_lainnya'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Revert file_word to not nullable
            $table->string('file_word')->nullable(false)->change();

            // Revert enum for jenis to original values
            $table->enum('jenis', ['nota_dinas', 'surat_dinas', 'surat_keputusan', 'surat_pernyataan', 'surat_keterangan'])->change();
        });
    }
};