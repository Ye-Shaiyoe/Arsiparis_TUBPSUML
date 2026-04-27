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
        Schema::table('surats', function (Blueprint $table) {
            $table->enum('status', ['proses', 'selesai', 'ditolak', 'revisi', 'draft'])->default('proses')->change();
        });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->enum('status', ['proses', 'selesai', 'ditolak', 'draft'])->default('proses')->change();
        });
    }
};
