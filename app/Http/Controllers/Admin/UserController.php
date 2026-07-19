<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewAccountMail;
use App\Models\User;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of all users with surat statistics
     */
    public function index(Request $request)
    {
        $query = User::withCount([
            'surats as total_surats',
            'surats as surats_selesai' => function ($q) { $q->where('status', 'selesai'); },
            'surats as surats_proses' => function ($q) { $q->where('status', 'proses'); },
            'surats as surats_ditolak' => function ($q) { $q->where('status', 'ditolak'); },
        ]);

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        if (in_array($sort, ['name', 'email', 'total_surats', 'created_at'])) {
            $query->orderBy($sort, $direction);
        }

        $users = $query->paginate(15)->withQueryString();

        // Statistik keseluruhan
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_users_registered' => User::where('role', 'user')->count(),
            'total_surats' => Surat::count(),
            'total_surats_selesai' => Surat::where('status', 'selesai')->count(),
            'total_surats_proses' => Surat::where('status', 'proses')->count(),
            'total_surats_ditolak' => Surat::where('status', 'ditolak')->count(),
        ];

        return view('admin.Settings.user.index', compact('users', 'stats'));
    }

    /**
     * Display detailed view of a specific user
     */
    public function show(User $user)
    {
        $user->load(['surats' => function ($query) {
            $query->with('tahapans')->latest();
        }]);

        // User statistics
        $stats = [
            'total_surats' => $user->surats->count(),
            'surats_selesai' => $user->surats->where('status', 'selesai')->count(),
            'surats_proses' => $user->surats->where('status', 'proses')->count(),
            'surats_ditolak' => $user->surats->where('status', 'ditolak')->count(),
            'avg_processing_days' => $this->calculateAverageProcessingDays($user),
        ];

        return view('admin.Settings.user.show', compact('user', 'stats'));
    }

    /**
     * Calculate average processing days for user's surats
     */
    private function calculateAverageProcessingDays(User $user): float
    {
        $completedSurats = $user->surats->where('status', 'selesai');
        
        if ($completedSurats->isEmpty()) {
            return 0;
        }

        $totalDays = $completedSurats->sum(function ($surat) {
            return $surat->updated_at->diffInDays($surat->created_at);
        });

        return round($totalDays / $completedSurats->count(), 2);
    }

    /**
     * Update role user (hanya untuk user dengan role 'user').
     */
    public function updateRole(Request $request, User $user)
    {
        // Hanya boleh ubah role user biasa, bukan sesama admin
        if ($user->role !== 'user') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Hanya role pengguna biasa (User) yang dapat diubah dari halaman ini.');
        }

        // Tidak boleh ubah role diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        $request->validate([
            'role' => ['required', 'string', 'in:user,admin_aspirasi,admin_kasubbag_tu,admin_kepala_balai'],
        ]);

        $oldRole = $user->role;
        $user->update([
            'role'          => $request->role,
            // role_selected = true karena role sudah dipilihkan admin,
            // user tidak perlu ke halaman Role-Selection lagi
            'role_selected' => true,
        ]);

        \Illuminate\Support\Facades\Log::info('Role user diubah oleh admin', [
            'target_user_id' => $user->id,
            'old_role'       => $oldRole,
            'new_role'       => $request->role,
            'by_admin_id'    => auth()->id(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Role '{$user->name}' berhasil diubah dari {$oldRole} menjadi {$request->role}.");
    }

    /**
     * Buat akun pengguna baru dari panel admin.
     * Password di-generate otomatis dan dikirim ke email target.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'nip'   => ['nullable', 'string', 'regex:/^\d{18}$/', 'unique:' . User::class],
            'role'  => ['required', 'string', 'in:user,admin_aspirasi,admin_kasubbag_tu,admin_kepala_balai'],
        ], [
            'nip.regex'  => 'NIP harus tepat 18 digit angka.',
            'nip.unique' => 'NIP sudah digunakan oleh pengguna lain.',
            'role.in'    => 'Role tidak valid.',
        ]);

        // Generate password acak 12 karakter (huruf + angka + simbol)
        $plainPassword = Str::password(12, letters: true, numbers: true, symbols: true, spaces: false);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'nip'      => $request->filled('nip') ? $request->nip : null,
            'nip_hash' => $request->filled('nip') ? User::hashNip($request->nip) : null,
            'password' => Hash::make($plainPassword),
            'role'     => $request->role,
        ]);

        // Kirim email info login ke akun baru
        try {
            Mail::to($user->email)->send(new NewAccountMail(
                user:          $user,
                plainPassword: $plainPassword,
                createdByName: auth()->user()->name,
            ));
            $mailStatus = 'berhasil dikirim ke ' . $user->email;
        } catch (\Throwable $e) {
            // Jangan gagalkan pembuatan akun hanya karena email gagal
            \Illuminate\Support\Facades\Log::error('Gagal kirim email akun baru: ' . $e->getMessage(), [
                'user_id' => $user->id,
            ]);
            $mailStatus = 'gagal dikirim (cek konfigurasi SMTP)';
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun '{$user->name}' berhasil dibuat. Email info login {$mailStatus}.");
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', "User '{$user->name}' berhasil dihapus.");
    }
}
