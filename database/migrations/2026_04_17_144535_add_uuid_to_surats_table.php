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
            $table->uuid('uuid')->after('id')->nullable()->unique();
        });

        // Generate UUIDs for existing records
        $surats = \DB::table('surats')->whereNull('uuid')->get();
        foreach ($surats as $surat) {
            \DB::table('surats')
                ->where('id', $surat->id)
                ->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        }
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
