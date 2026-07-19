<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambah kolom nip_hash untuk lookup NIP secara efisien.
     *
     * NIP asli tetap dienkripsi (reversible) di kolom `nip`.
     * nip_hash = HMAC-SHA256(nip, APP_KEY) — deterministik, bisa di-index,
     * tidak bisa di-reverse tanpa APP_KEY.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip_hash', 64)->nullable()->unique()->after('nip');
        });

        // Backfill: isi nip_hash untuk user yang sudah punya NIP
        // NIP sudah dienkripsi di DB, kita decode dulu lalu hash
        $users = DB::table('users')->whereNotNull('nip')->get(['id', 'nip']);

        foreach ($users as $user) {
            try {
                // Decrypt NIP (Laravel encrypted cast)
                $decrypted = \Illuminate\Support\Facades\Crypt::decryptString($user->nip);
                $hash = hash_hmac('sha256', $decrypted, config('app.key'));

                DB::table('users')->where('id', $user->id)->update(['nip_hash' => $hash]);
            } catch (\Throwable $e) {
                // Skip jika NIP tidak bisa di-decrypt (data lama/corrupt)
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nip_hash');
        });
    }
};
