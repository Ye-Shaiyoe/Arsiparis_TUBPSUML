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
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable()->unique();
        });

        // Generate UUIDs for existing records
        $users = \DB::table('users')->whereNull('uuid')->get();
        foreach ($users as $user) {
            \DB::table('users')
                ->where('id', $user->id)
                ->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
