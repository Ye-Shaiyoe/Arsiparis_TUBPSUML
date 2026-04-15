<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\SuratTahapan;
use App\Models\SuratDeleteRequest;
use App\Models\User;
use App\Notifications\SuratStatusNotification;
use App\Notifications\SuratDiprosesNotification;
use App\Notifications\FileRevisiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with('user')->latest();

        $admin = Auth::user();

        // Filter berdasarkan role admin
        // admin_aspirasi: tahap 2 + 5-10
        // admin_kasubbag_tu: tahap 3
        // admin_kepala_balai: tahap 4
        if ($admin->role === 'admin_aspirasi') {
            $query->where(function($q) {
                $q->where('tahap_sekarang', 2)
                  ->orWhere('tahap_sekarang', '>=', 5);
            });
        } elseif ($admin->role === 'admin_kasubbag_tu') {
            $query->where('tahap_sekarang', 3);
        } elseif ($admin->role === 'admin_kepala_balai') {
            $query->where('tahap_sekarang', 4);
        }
        // admin lama (role='admin') tetap bisa lihat semua

        if ($request->filled('jenis'))  $query->where('jenis', $request->jenis);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('tahap'))  $query->where('tahap_sekarang', $request->tahap);
        if ($request->filled('search')) $query->where('judul', 'like', '%'.$request->search.'%');

        // Tampilkan surat dengan status 'revisi' di paling atas (prioritas)
        $surats = $query->orderByRaw("CASE WHEN status = 'revisi' THEN 0 ELSE 1 END")
                        ->paginate(15)->withQueryString();

        return view('admin.surat.index', compact('surats'));
    }

    public function show(Surat $surat)
    {
        $surat->load(['user', 'tahapans.diprosesByUser']);
        return view('admin.surat.show', compact('surat'));
    }

    public function setujui(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan'     => 'nullable|string|max:500',
            'nomor_surat' => 'nullable|string|max:100',
        ]);

        // Validasi: apakah admin punya hak approve tahap ini?
        $admin = Auth::user();
        if (!$admin->canApproveTahap($surat->tahap_sekarang)) {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk approve tahap ini.');
        }

        // Jika status revisi, set jadi 'proses' lagi
        if ($surat->status === 'revisi') {
            $surat->update(['status' => 'proses']);
        }

        // Tandai tahap sekarang selesai
        SuratTahapan::where('surat_id', $surat->id)
            ->where('tahap', $surat->tahap_sekarang)
            ->update([
                'status'        => 'selesai',
                'diproses_oleh' => Auth::id(),
                'catatan'       => $request->catatan,
                'selesai_pada'  => now(),
            ]);

        $tahapBerikutnya = $surat->tahap_sekarang + 1;

        if ($tahapBerikutnya > 10) {
            // Surat selesai - setujui_pada dan file_expires_at (3 hari)
            $surat->update([
                'status' => 'selesai', 
                'tahap_sekarang' => 10,
                'disetujui_pada' => now(),
                'file_expires_at' => now()->addDays(3),
            ]);

            // Notif ke pengusul: SELESAI
            $surat->user->notify(new SuratStatusNotification(
                surat  : $surat,
                type   : 'success',
                title  : '✅ Surat selesai diproses!',
                message: "Surat \"{$surat->judul}\" telah selesai semua tahapan.",
                url    : route('user.surat.show', $surat->id),
            ));
        } else {
            $updateData = ['tahap_sekarang' => $tahapBerikutnya];

            if ($surat->tahap_sekarang === 5 && $request->filled('nomor_surat')) {
                $updateData['nomor_surat']   = $request->nomor_surat;
                $updateData['tanggal_surat'] = now()->toDateString();
            }

            $surat->update($updateData);
            $surat->refresh();

            SuratTahapan::where('surat_id', $surat->id)
                ->where('tahap', $tahapBerikutnya)
                ->update(['status' => 'proses']);

            // Notif ke pengusul: maju tahap
            $surat->user->notify(new SuratStatusNotification(
                surat  : $surat,
                type   : 'info',
                title  : "📨 Surat maju ke tahap {$tahapBerikutnya}",
                message: "\"{$surat->judul}\" sudah diverifikasi — sekarang: {$surat->nama_tahap}.",
                url    : route('user.surat.show', $surat->id),
            ));

            // Notif ke admin lain: surat diproses
            $this->notifAdminLain($surat, Auth::user(), 'disetujui');
        }

        return redirect()->route('admin.surat.show', $surat)
                         ->with('success', 'Surat berhasil disetujui dan maju ke tahap berikutnya.');
    }

    public function tolak(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        // Jika sudah status revisi, set kembali ke ditolak
        $statusSebelumnya = $surat->status;
        
        SuratTahapan::where('surat_id', $surat->id)
            ->where('tahap', $surat->tahap_sekarang)
            ->update([
                'status'        => 'ditolak',
                'diproses_oleh' => Auth::id(),
                'catatan'       => $request->catatan,
                'selesai_pada'  => now(),
            ]);

        $surat->update(['status' => 'ditolak']);

        // Notif ke pengusul: DITOLAK
        $surat->user->notify(new SuratStatusNotification(
            surat  : $surat,
            type   : 'danger',
            title  : $statusSebelumnya === 'revisi' ? '❌ File revisi ditolak' : '❌ Surat ditolak',
            message: "Surat \"{$surat->judul}\" " . ($statusSebelumnya === 'revisi' ? 'file revisinya tetap' : 'ditolak') . ". Alasan: {$request->catatan}",
            url    :route('user.surat.show', $surat->id),
        ));

        // Notif ke admin lain
        $this->notifAdminLain($surat, Auth::user(), $statusSebelumnya === 'revisi' ? 'revisi ditolak' : 'ditolak');

        return redirect()->route('admin.surat.index')
                         ->with('success', $statusSebelumnya === 'revisi' ? 'File revisi ditolak. User bisa upload ulang.' : 'Surat telah ditolak.');
    }

    // Kirim notif ke semua admin kecuali yang sedang login
    private function notifAdminLain(Surat $surat, $currentUser, string $aksi): void
    {
        User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])
            ->where('id', '!=', $currentUser->id)
            ->get()
            ->each(function ($admin) use ($surat, $currentUser, $aksi) {
                $admin->notify(new SuratDiprosesNotification(
                    surat         : $surat,
                    diprosesByUser: $currentUser,
                    aksi          : $aksi,
                ));
            });
    }

    public function preview(Surat $surat, string $tipe)
    {
        // Cek apakah file sudah dihapus (expired)
        if ($surat->file_dihapus_pada) {
            abort(404, 'File sudah tidak tersedia (kadaluarsa)');
        }

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath || !\Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $fullPath = storage_path('app/public/' . $filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Untuk PDF, tampilkan inline di browser
        if ($extension === 'pdf') {
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
            ]);
        }

        // Untuk gambar, langsung tampilkan
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'bmp' => 'image/bmp',
            ];
            return response()->file($fullPath, [
                'Content-Type' => $mimeTypes[$extension] ?? 'image/jpeg',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
            ]);
        }

        // Untuk Word (.docx/.doc), convert ke HTML dan tampilkan di modal
        if (in_array($extension, ['docx', 'doc'])) {
            $converter = new \App\Services\DocxToHtmlConverter($fullPath);
            $htmlContent = $converter->convert();

            return response()->view('admin.surat.preview-word', [
                'surat' => $surat,
                'htmlContent' => $htmlContent,
                'tipe' => $tipe,
                'fileName' => basename($filePath),
            ]);
        }

        // Fallback: download
        return response()->download($fullPath);
    }

    public function download(Surat $surat, string $tipe)
    {
        // Cek apakah file sudah dihapus (expired)
        if ($surat->file_dihapus_pada) {
            abort(404, 'File sudah tidak tersedia (kadaluarsa)');
        }

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath || !\Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Ambil nama file asli
        $originalName = $tipe === 'word' ? $surat->judul : 'lampiran';
        $downloadName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . '.' . $extension;

        // MIME types
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'bmp'  => 'image/bmp',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls'  => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'ppt'  => 'application/vnd.ms-powerpoint',
        ];

        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

        while (ob_get_level()) {
            ob_end_clean();
        }

        return \Storage::disk('public')->download($filePath, $downloadName, [
            'Content-Type' => $mimeType,
        ]);
    }
    public function previewContent(Surat $surat, string $tipe)
    {
        if ($surat->file_dihapus_pada) {
            return response()->json(['error' => 'File sudah kadaluarsa'], 404);
        }

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath || !\Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        $fullPath = storage_path('app/public/' . $filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (in_array($extension, ['docx', 'doc'])) {
            $converter = new \App\Services\DocxToHtmlConverter($fullPath);
            $htmlContent = $converter->convert();

            return response()->json([
                'type' => 'html',
                'content' => $htmlContent,
            ]);
        }

        // PDF: return URL untuk iframe
        if ($extension === 'pdf') {
            return response()->json([
                'type' => 'pdf',
                'url' => route('admin.surat.preview', [$surat, $tipe]),
            ]);
        }

        // Image: return URL
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
            return response()->json([
                'type' => 'image',
                'url' => route('admin.surat.preview', [$surat, $tipe]),
            ]);
        }

        return response()->json(['error' => 'Format tidak didukung'], 400);
    }

    /**
     * Approve permintaan hapus surat
     */
    public function approveDelete(Request $request, SuratDeleteRequest $deleteRequest)
    {
        // Pastikan request masih pending
        if (!$deleteRequest->isPending()) {
            return back()->with('error', 'Permintaan hapus sudah diproses sebelumnya.');
        }

        $request->validate([
            'admin_catatan' => 'nullable|string|max:500',
        ]);

        // Update status request
        $deleteRequest->update([
            'admin_id'          => Auth::id(),
            'status'            => 'disetujui',
            'admin_catatan'     => $request->admin_catatan,
            'admin_approved_at' => now(),
        ]);

        // Hapus surat
        $surat = $deleteRequest->surat;
        $this->hapusSurat($surat);

        // Notifikasi ke user bahwa surat dihapus
        $surat->user->notify(new SuratStatusNotification(
            surat: $surat,
            type: 'success',
            title: '✅ Permintaan hapus disetujui',
            message: "Surat \"{$surat->judul}\" telah dihapus setelah disetujui admin." . ($request->admin_catatan ? " Catatan: {$request->admin_catatan}" : ''),
            url: route('user.surat.index'),
        ));

        return back()->with('success', 'Permintaan hapus disetujui. Surat berhasil dihapus.');
    }

    public function rejectDelete(Request $request, SuratDeleteRequest $deleteRequest)
    {
        // Pastikan request masih pending
        if (!$deleteRequest->isPending()) {
            return back()->with('error', 'Permintaan hapus sudah diproses sebelumnya.');
        }

        $request->validate([
            'admin_catatan' => 'required|string|max:500',
        ]);

        $deleteRequest->update([
            'admin_id'          => Auth::id(),
            'status'            => 'ditolak',
            'admin_catatan'     => $request->admin_catatan,
            'admin_approved_at' => now(),
        ]);

        $surat = $deleteRequest->surat;
        $surat->user->notify(new SuratStatusNotification(
            surat: $surat,
            type: 'warning',
            title: '❌ Permintaan hapus ditolak',
            message: "Permintaan hapus surat \"{$surat->judul}\" ditolak. Alasan: {$request->admin_catatan}",
            url: route('user.surat.show', $surat->id),
        ));

        return back()->with('info', 'Permintaan hapus ditolak.');
    }

    private function hapusSurat(Surat $surat)
    {
        // Hapus file word
        if ($surat->file_word) {
            $wordPath = storage_path('app/public/' . $surat->file_word);
            if (file_exists($wordPath)) {
                @unlink($wordPath);
            }
        }

        // Hapus file lampiran
        if ($surat->file_lampiran) {
            $lampiranPath = storage_path('app/public/' . $surat->file_lampiran);
            if (file_exists($lampiranPath)) {
                @unlink($lampiranPath);
            }
        }

        $surat->delete();
    }
}