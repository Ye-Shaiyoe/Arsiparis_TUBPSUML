<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
