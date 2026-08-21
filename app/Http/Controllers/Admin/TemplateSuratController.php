<?php
// =============================================
// app/Http/Controllers/Admin/TemplateSuratController.php
// =============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateSuratController extends Controller
{
    // Folder tempat simpan template
    const FOLDER = 'templates';

    public function index()
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('private');
        
        // Ambil semua file di folder templates
        $files = collect($disk->files(self::FOLDER))
            ->map(function ($path) use ($disk) {
                return [
                    'path'     => $path,
                    'nama'     => basename($path),
                    'ukuran'   => $this->formatBytes($disk->size($path)),
                    'ext'      => strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                    'diupload' => \Carbon\Carbon::createFromTimestamp(
                                    $disk->lastModified($path)
                                  )->format('d M Y'),
                    'url'      => route('admin.template.download', ['nama' => basename($path)]),
                ];
            })
            ->sortBy('nama')
            ->values();

        return view('admin.template.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_template' => 'required|file|mimes:docx,doc,pdf|max:10240',
            'nama_file'     => 'required|string|max:100',
        ]);

        $ext = strtolower($request->file('file_template')->getClientOriginalExtension());
        $namaFile = \Str::slug($request->nama_file) . '.' . $ext;
        $request->file('file_template')->storeAs(self::FOLDER, $namaFile, 'private');

        return redirect()->route('admin.template.index')
                         ->with('success', "Template '{$namaFile}' berhasil diupload.");
    }

    public function destroy(Request $request)
    {
        $request->validate(['path' => 'required|string']);
        Storage::disk('private')->delete($request->path);

        return redirect()->route('admin.template.index')
                         ->with('success', 'Template berhasil dihapus.');
    }

    public function download($nama)
    {
        $safeName = basename($nama);
        $path = self::FOLDER . '/' . $safeName;
        if (!Storage::disk('private')->exists($path)) {
            return redirect()->back()->with('error', 'File template tidak ditemukan.');
        }

        $extension = strtolower(pathinfo($safeName, PATHINFO_EXTENSION));
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

        while (ob_get_level()) {
            ob_end_clean();
        }

        return Storage::disk('private')->download($path, $safeName, [
            'Content-Type' => $mimeType,
        ]);
    }

    private function formatBytes($bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        return round($bytes / 1024, 1) . ' KB';
    }
}