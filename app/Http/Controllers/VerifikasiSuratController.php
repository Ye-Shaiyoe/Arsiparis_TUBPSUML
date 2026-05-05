<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class VerifikasiSuratController extends Controller
{
    /**
     * Tampilkan halaman verifikasi publik untuk surat
     */
    public function index($uuid)
    {
        // Cari surat berdasarkan UUID
        $surat = Surat::where('uuid', $uuid)->with('user')->first();

        if (!$surat) {
            return view('surat.verifikasi-error');
        }

        return view('surat.verifikasi', compact('surat'));
    }
}
