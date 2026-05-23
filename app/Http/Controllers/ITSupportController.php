<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\ITSupportBroadcastNotification;
use App\Notifications\AspirasiDibalasNotification;

class ITSupportController extends Controller
{
    public function dashboard()
    {
        $aspirasis = \App\Models\Aspirasi::where('tujuan', 'it_support')
            ->latest()
            ->limit(5)
            ->get();

        $notifications = auth()->user()->notifications()->latest()->limit(5)->get();

        // Fetch all complete letters with their data including both files
        $completeSurats = \App\Models\Surat::where('status', 'selesai')
            ->with('user')
            ->orderBy('disetujui_pada', 'desc')
            ->paginate(15);

        return view('it_support.dashboard', [
            'title' => 'IT Support Dashboard',
            'aspirasis' => $aspirasis,
            'notifications' => $notifications,
            'completeSurats' => $completeSurats
        ]);
    }

    public function updateAspirasi(Request $request, \App\Models\Aspirasi $aspirasi)
    {
        $request->validate([
            'balasan' => 'required|string',
        ]);

        $aspirasi->update([
            'balasan' => $request->balasan,
            'status' => 'dibalas',
            'dibalas_at' => now(),
        ]);

        // Optional: Notify User that IT has replied
        $aspirasi->user->notify(new AspirasiDibalasNotification($aspirasi));

        return redirect()->back()->with('success', 'Aspirasi berhasil dibalas!');
    }

    public function createNotification()
    {
        return view('it_support.notification_create', [
            'title' => 'Broadcast Notification'
        ]);
    }

    public function storeNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'target' => 'required|in:all,admin,user'
        ]);

        $query = \App\Models\User::query();

        if ($request->target === 'admin') {
            $query->whereIn('role', ['admin', 'admin_kasubbag_tu', 'admin_kepala_balai', 'it_support']);
        } elseif ($request->target === 'user') {
            $query->where('role', 'user');
        }

        $users = $query->get();

        foreach ($users as $user) {
            $user->notify(new ITSupportBroadcastNotification(
                $request->title,
                $request->message,
                $request->type
            ));
        }

        return redirect()->route('itsupport.dashboard')->with('success', 'Notifikasi berhasil dikirim ke ' . $users->count() . ' pengguna.');
    }

    public function becomeITSupport(\Illuminate\Http\Request $request)
    {
        $code = $request->input('code');
        $envCode = config('app.it_support_code');

        if ($envCode && $code === trim($envCode)) {
            $user = auth()->user();
            $user->role = 'it_support';
            $user->save();
            return redirect()->route('itsupport.dashboard')->with('success', 'You are now IT Support!');
        }

        return redirect('/')->with('error', 'Invalid code or configuration error.');
    }
}
