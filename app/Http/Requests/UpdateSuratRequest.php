<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSuratRequest extends FormRequest
{
    /**
     * Hanya pemilik surat (draft) yang bisa update.
     */
    public function authorize(): bool
    {
        $surat = $this->route('surat');
        return $surat
            && $this->user()
            && $surat->user_id === Auth::id()
            && $surat->status === 'draft';
    }

    /**
     * Apakah request ini adalah mode draft.
     */
    public function isDraft(): bool
    {
        return $this->input('action') === 'draft';
    }

    /**
     * Aturan validasi untuk update/submit draft surat.
     */
    public function rules(): array
    {
        $isDraft    = $this->isDraft();
        $surat      = $this->route('surat');
        $req        = $isDraft ? 'nullable' : 'required';

        // file_word: wajib hanya jika submit & belum ada file sebelumnya
        $fileWordReq = ($isDraft || ($surat && $surat->file_word)) ? 'nullable' : 'required';

        return [
            'judul'             => [$req, 'string', 'max:255'],
            'jenis'             => [$req, 'string', 'in:nota_dinas,surat_dinas,surat_keputusan,surat_pernyataan,surat_keterangan,surat_undangan,surat_lainnya'],
            'sifat'             => [$req, 'string', 'in:biasa,segera,rahasia'],
            'tujuan'            => [$req, 'string', 'max:500'],
            'catatan_pengusul'  => ['nullable', 'string', 'max:100'],

            'file_word' => [
                $fileWordReq,
                'file',
                'mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword',
                'mimes:docx,doc',
                'max:5120',
            ],
            'file_lampiran' => [
                'nullable',
                'file',
                'mimetypes:application/pdf,image/jpeg,image/png,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel',
                'mimes:pdf,jpg,jpeg,png,docx,doc,xlsx,xls',
                'max:10240',
            ],
        ];
    }

    /**
     * Pesan error ramah.
     */
    public function messages(): array
    {
        return [
            'judul.required'            => 'Judul surat wajib diisi.',
            'jenis.required'            => 'Jenis surat wajib dipilih.',
            'jenis.in'                  => 'Jenis surat yang dipilih tidak valid.',
            'sifat.required'            => 'Sifat surat wajib dipilih.',
            'sifat.in'                  => 'Sifat surat yang dipilih tidak valid.',
            'tujuan.required'           => 'Tujuan surat wajib diisi.',
            'tujuan.max'                => 'Tujuan surat maksimal 500 karakter.',
            'catatan_pengusul.max'      => 'Catatan maksimal 100 karakter.',
            'file_word.required'        => 'File surat Word (.docx) wajib diupload.',
            'file_word.mimes'           => 'File surat harus berformat Word (.docx / .doc).',
            'file_word.mimetypes'       => 'File surat harus berformat Word (.docx / .doc). File mencurigakan ditolak.',
            'file_word.max'             => 'Ukuran file surat maksimal 5 MB.',
            'file_lampiran.mimes'       => 'Lampiran harus berupa PDF, Gambar (JPG/PNG), Word (DOCX/DOC), atau Excel (XLSX/XLS).',
            'file_lampiran.mimetypes'   => 'Tipe konten lampiran tidak diizinkan. File mencurigakan ditolak.',
            'file_lampiran.max'         => 'Ukuran lampiran maksimal 10 MB.',
        ];
    }

    /**
     * Sanitasi input teks sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'judul'            => $this->judul            ? strip_tags(trim($this->judul))            : $this->judul,
            'tujuan'           => $this->tujuan           ? strip_tags(trim($this->tujuan))           : $this->tujuan,
            'catatan_pengusul' => $this->catatan_pengusul ? strip_tags(trim($this->catatan_pengusul)) : $this->catatan_pengusul,
        ]);
    }
}
