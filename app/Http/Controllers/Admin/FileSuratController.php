<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileSuratController extends Controller
{
    /**
     * Tampilkan daftar surat yang masih memiliki file fisik.
     */
    public function index(Request $request)
    {
        $query = Surat::with('user')
            ->where(function($q) {
                $q->whereNotNull('file_word')
                  ->orWhereNotNull('file_lampiran');
            })
            ->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $surats = $query->paginate(15)->withQueryString();

        return view('admin.settings.file.index', compact('surats'));

    }

    /**
     * Hapus file fisik surat tanpa menghapus data tracking.
     */
    public function destroy(Surat $surat)
    {
        // Validasi: Hanya surat yang sudah selesai atau ditolak yang boleh dihapus file fisiknya
        if (!in_array($surat->status, ['selesai', 'ditolak'])) {
            return back()->with('error', 'File fisik tidak dapat dihapus karena surat masih dalam tahap pemrosesan.');
        }

        // Hapus file dari disk
        if ($surat->file_word) {
            Storage::disk('private')->delete($surat->file_word);
        }

        if ($surat->file_lampiran) {
            Storage::disk('private')->delete($surat->file_lampiran);
        }

        // Update database: set path file jadi null dan catat waktu penghapusan
        $surat->update([
            'file_word'         => null,
            'file_lampiran'     => null,
            'file_dihapus_pada' => now(),
        ]);

        return back()->with('success', "File fisik untuk surat \"{$surat->judul}\" berhasil dihapus. Data tracking tetap tersimpan.");
    }

    /**
     * Hapus file fisik secara massal (hanya untuk yang sudah selesai/ditolak)
     */
    public function massDelete(Request $request)
    {
        $surats = Surat::whereIn('status', ['selesai', 'ditolak'])
            ->where(function($q) {
                $q->whereNotNull('file_word')
                  ->orWhereNotNull('file_lampiran');
            })
            ->get();

        $count = 0;
        foreach ($surats as $surat) {
            if ($surat->file_word) Storage::disk('private')->delete($surat->file_word);
            if ($surat->file_lampiran) Storage::disk('private')->delete($surat->file_lampiran);

            $surat->update([
                'file_word'         => null,
                'file_lampiran'     => null,
                'file_dihapus_pada' => now(),
            ]);
            $count++;
        }

        return back()->with('success', "Berhasil menghapus file fisik dari {$count} surat.");
    }
}
