<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan riwayat aksi user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $logs = ActivityLog::where('user_id', $user->id)
            ->with('user')
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan action
        if ($request->filled('action')) {
            $logs->where('action', $request->action);
        }
        
        // Filter berdasarkan model_type
        if ($request->filled('model_type')) {
            $logs->where('model_type', $request->model_type);
        }
        
        // Filter berdasarkan rentang tanggal
        if ($request->filled('date_from')) {
            $logs->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $logs->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $logs->where(function ($query) use ($search) {
                $query->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $logs = $logs->paginate(20);
        
        // Get unique actions untuk filter
        $actions = ActivityLog::where('user_id', $user->id)
            ->distinct()
            ->pluck('action');
        
        // Get unique model_types untuk filter
        $modelTypes = ActivityLog::where('user_id', $user->id)
            ->distinct()
            ->pluck('model_type');
        
        return view('user.activity-log.index', compact('logs', 'actions', 'modelTypes'));
    }

    /**
     * Tampilkan detail aksi tertentu
     */
    public function show(ActivityLog $log)
    {
        // Validasi: user hanya bisa lihat log mereka sendiri
        if ($log->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('user.activity-log.show', compact('log'));
    }

    /**
     * Export activity logs ke CSV
     */
    /**
     * Hapus semua activity log milik user yang login
     */
    public function destroyAll(Request $request)
    {
        $user = Auth::user();

        ActivityLog::where('user_id', $user->id)->delete();

        return redirect()->route('user.activity-log.index')
            ->with('success', 'Semua riwayat aktivitas berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        
        $logs = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');
        
        // Apply filters sama seperti di index
        if ($request->filled('action')) {
            $logs->where('action', $request->action);
        }
        if ($request->filled('model_type')) {
            $logs->where('model_type', $request->model_type);
        }
        if ($request->filled('date_from')) {
            $logs->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $logs->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $logs->get();
        
        // Create CSV
        $filename = 'activity-log-' . date('Y-m-d-His') . '.csv';
        $handle = fopen('php://memory', 'r+');
        
        // Header
        fputcsv($handle, ['Tanggal', 'Aksi', 'Model', 'Deskripsi', 'IP Address']);
        
        // Data
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->created_at->format('Y-m-d H:i:s'),
                $log->action,
                $log->model_type . '#' . $log->model_id,
                $log->description,
                $log->ip_address,
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
