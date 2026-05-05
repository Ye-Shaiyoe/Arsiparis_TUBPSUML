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
use App\Services\HtmlSanitizer;

class SuratController extends Controller
{
    public function index(Request $request, $title = 'Antrian Surat')
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
        if ($request->filled('status')) {
            if ($request->status === 'proses') {
                $query->whereIn('status', ['proses', 'revisi', 'revisi_admin']);
            } else {
                $query->where('status', $request->status);
            }
        }
        if ($request->filled('tahap'))  $query->where('tahap_sekarang', $request->tahap);
        if ($request->filled('search')) $query->where('judul', 'like', '%'.$request->search.'%');
        if ($request->filled('bulan'))  $query->whereMonth('created_at', (int) $request->bulan);
        if ($request->filled('tahun'))  $query->whereYear('created_at', (int) $request->tahun);

        // Tampilkan surat dengan status 'revisi' atau 'revisi_admin' di paling atas (prioritas)
        $surats = $query->orderByRaw("CASE WHEN status = 'revisi' OR status = 'revisi_admin' THEN 0 ELSE 1 END")
                        ->paginate(15)->withQueryString();

        return view('admin.surat.index', compact('surats', 'title'));
    }

    public function masuk(Request $request)
    {
        $request->merge(['status' => 'proses']);
        return $this->index($request, 'Surat Masuk');
    }

    public function proses(Request $request)
    {
        $request->merge(['status' => 'proses']);
        return $this->index($request, 'Surat Sedang Diproses');
    }

    public function selesai(Request $request)
    {
        $request->merge(['status' => 'selesai']);
        return $this->index($request, 'Surat Selesai');
    }

    public function revisi(Request $request)
    {
        $request->merge(['status' => 'revisi']);
        return $this->index($request, 'Surat Perlu Revisi');
    }

    public function show($surat)
    {
        // Cari berdasarkan UUID dulu (standar baru)
        $suratModel = Surat::where('uuid', $surat)->first();

        // Fallback: Jika tidak ketemu dan inputnya angka, coba cari berdasarkan ID (untuk link lama)
        if (!$suratModel && is_numeric($surat)) {
            $suratModel = Surat::find($surat);
            if ($suratModel) {
                // Redirect otomatis ke URL versi UUID biar rapi
                return redirect()->route('admin.surat.show', $suratModel);
            }
        }

        if (!$suratModel) {
            abort(404, 'Surat tidak ditemukan.');
        }

        $suratModel->load(['user', 'tahapans.diprosesByUser']);
        return view('admin.surat.show', ['surat' => $suratModel]);
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

        // Jika status revisi (dari user atau admin), set jadi 'proses' lagi
        if (in_array($surat->status, ['revisi', 'revisi_admin'])) {
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
                url    : route('user.surat.show', $surat),
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
                url    : route('user.surat.show', $surat),
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
            'catatan'     => 'required|string|max:500',
            'jenis_tolak' => 'nullable|string|in:ke_user,ke_admin_aspirasi',
        ]);

        $statusSebelumnya = $surat->status;
        $jenisTolak       = $request->input('jenis_tolak', 'ke_user');

        if ($jenisTolak === 'ke_admin_aspirasi') {
            // Logika: Kembalikan ke Admin Tahap 2
            
            // 1. Tandai tahap saat ini sebagai 'ditolak'
            SuratTahapan::where('surat_id', $surat->id)
                ->where('tahap', $surat->tahap_sekarang)
                ->update([
                    'status'        => 'ditolak',
                    'diproses_oleh' => Auth::id(),
                    'catatan'       => $request->catatan,
                    'selesai_pada'  => now(),
                ]);

            // 2. Reset Tahap 2 (Admin Aspirasi) agar jadi 'proses' lagi
            SuratTahapan::where('surat_id', $surat->id)
                ->where('tahap', 2)
                ->update([
                    'status'        => 'proses',
                    'diproses_oleh' => null,
                    'selesai_pada'  => null,
                    // Catatan lama biarkan saja buat histori? Atau hapus? 
                    // Kita timpa saja nanti saat diproses ulang
                ]);

            // 3. Update Surat
            $surat->update([
                'status'         => 'revisi_admin',
                'tahap_sekarang' => 2,
            ]);

            // Notif ke pengusul (User): Surat sedang direvisi internal
            $surat->user->notify(new SuratStatusNotification(
                surat  : $surat,
                type   : 'warning',
                title  : '🔄 Surat sedang direvisi (Internal)',
                message: "Surat \"{$surat->judul}\" sedang dikembalikan ke bagian Aspirasi untuk perbaikan internal. Catatan: {$request->catatan}",
                url    : route('user.surat.show', $surat),
            ));

            // Notif ke admin lain (terutama Admin Aspirasi)
            $this->notifAdminLain($surat, Auth::user(), 'revisi_admin');

            return redirect()->route('admin.surat.index')
                             ->with('success', 'Surat berhasil dikembalikan ke Admin Aspirasi (Tahap 2).');

        } else {
            // Logika Lama: Tolak ke User
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
                url    : route('user.surat.show', $surat),
            ));

            // Notif ke admin lain
            $this->notifAdminLain($surat, Auth::user(), $statusSebelumnya === 'revisi' ? 'revisi ditolak' : 'ditolak');

            return redirect()->route('admin.surat.index')
                             ->with('success', $statusSebelumnya === 'revisi' ? 'File revisi ditolak. User bisa upload ulang.' : 'Surat telah ditolak.');
        }
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
        if ($surat->file_dihapus_pada) {
            abort(404, 'File sudah tidak tersedia (kadaluarsa)');
        }

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $fileContent = Storage::disk('private')->get($filePath);
        $fileName = basename($filePath);

        // Jika request minta raw (untuk docx-preview.js)
        if (request()->has('raw')) {
            if (ob_get_length()) ob_end_clean();
            $mimeType = $extension === 'docx' 
                ? 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                : 'application/msword';

            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        }

        // PDF
        if ($extension === 'pdf') {
            if (ob_get_length()) ob_end_clean();
            return response($fileContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        }

        // Gambar
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
            if (ob_get_length()) ob_end_clean();
            $mimeTypes = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif','webp'=>'image/webp','bmp'=>'image/bmp'];
            return response($fileContent, 200)
                ->header('Content-Type', $mimeTypes[$extension] ?? 'image/jpeg')
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        }

        // Jika .doc biasa, paksa download karena docx converter tidak support doc binary
        if ($extension === 'doc') {
            return $this->download($surat, $tipe);
        }

        // Word (.docx) - Tampilkan View Preview
        if ($extension === 'docx') {
            $converter = new \App\Services\DocxToHtmlConverter(Storage::disk('private')->path($filePath));
            $htmlRaw = $converter->convert();
            $htmlContent = HtmlSanitizer::clean($htmlRaw);

            return response()->view('admin.surat.preview-word', [
                'surat' => $surat,
                'htmlContent' => $htmlContent,
                'tipe' => $tipe,
                'fileName' => $fileName,
            ]);
        }

        // Fallback: download
        return Storage::disk('private')->download($filePath);
    }

    public function download(Surat $surat, string $tipe)
    {
        // Cek apakah file sudah dihapus (expired)
        if ($surat->file_dihapus_pada) {
            abort(404, 'File sudah tidak tersedia (kadaluarsa)');
        }

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
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

        return Storage::disk('private')->download($filePath, $downloadName, [
            'Content-Type' => $mimeType,
        ]);
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
            url: route('user.surat.show', $surat),
        ));

        return back()->with('info', 'Permintaan hapus ditolak.');
    }

    private function hapusSurat(Surat $surat)
    {
        // Hapus file word dari private storage
        if ($surat->file_word) {
            Storage::disk('private')->delete($surat->file_word);
        }

        // Hapus file lampiran dari private storage
        if ($surat->file_lampiran) {
            Storage::disk('private')->delete($surat->file_lampiran);
        }

        $surat->delete();
    }

    public function uploadFileAdmin(Request $request, Surat $surat)
    {
        $request->validate([
            'file_word'     => 'required|file|mimes:docx,doc|max:5120',
            'file_lampiran' => 'nullable|file|mimes:pdf,docx,doc,jpg,jpeg,png|max:10240',
        ]);

        $updated = false;

        // Handle File Word
        if ($request->hasFile('file_word')) {
            $pathWord = $request->file('file_word')->store('surat/word', 'private');
            if ($surat->file_word) {
                Storage::disk('private')->delete($surat->file_word);
            }
            $surat->file_word = $pathWord;
            $updated = true;
        }

        // Handle File Lampiran
        if ($request->hasFile('file_lampiran')) {
            $pathLampiran = $request->file('file_lampiran')->store('surat/lampiran', 'private');
            if ($surat->file_lampiran) {
                Storage::disk('private')->delete($surat->file_lampiran);
            }
            $surat->file_lampiran = $pathLampiran;
            $updated = true;
        }

        if ($updated) {
            $surat->save();

            // Berikan notifikasi ke user jika file diubah admin (biasanya tahap 2)
            if ($surat->user) {
                $surat->user->notify(new SuratStatusNotification(
                    surat  : $surat,
                    type   : 'info',
                    title  : '📝 File Surat Diperbarui Admin',
                    message: "Admin Aspirasi telah melakukan penyesuaian/perbaikan format pada file surat \"{$surat->judul}\".",
                    url    : route('user.surat.show', $surat)
                ));
            }

            return back()->with('success', 'File surat berhasil diperbarui.');
        }

        return back()->with('error', 'Tidak ada file yang diunggah.');
    }
}