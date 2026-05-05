<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        /** @var FilesystemAdapter $privateDisk */
        $privateDisk = Storage::disk('private');
        $templates = collect($privateDisk->files('templates'))
            ->map(fn(string $path) => [
                'nama' => basename($path),
                'url'  => route('user.template.download', ['nama' => basename($path)]),
                'size' => round($privateDisk->size($path) / 1024, 1) . ' KB',
                'ext'  => pathinfo($path, PATHINFO_EXTENSION),
            ])
            ->values();

        return view('user.template.index', [
            'title'     => 'Template Surat',
            'templates' => $templates,
        ]);
    }
}
