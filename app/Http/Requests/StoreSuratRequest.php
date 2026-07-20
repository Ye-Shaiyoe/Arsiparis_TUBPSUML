<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratRequest extends FormRequest
{
    /**
     * Hanya user yang sudah login yang bisa submit form ini.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Apakah request ini adalah mode draft (simpan draf).
     */
    public function isDraft(): bool
    {
        return $this->input('action') === 'draft';
    }

    /**
     * Aturan validasi untuk pengajuan surat baru.
     * - Mode submit  : semua field wajib diisi.
     * - Mode draft   : field teks boleh nullable, tapi tipe/ukuran file tetap divalidasi jika ada.
     */
    public function rules(): array
    {
        $isDraft = $this->isDraft();
        $req     = $isDraft ? 'nullable' : 'required';

        return [
            // Teks
            'judul'             => [$req, 'string', 'max:255'],
            'jenis'             => [$req, 'string', 'in:nota_dinas,surat_dinas,surat_keputusan,surat_pernyataan,surat_keterangan,surat_undangan,surat_lainnya'],
            'sifat'             => [$req, 'string', 'in:biasa,segera,rahasia'],
            'tujuan'            => [$req, 'string', 'max:500'],
            'catatan_pengusul'  => ['nullable', 'string', 'max:100'],

            // File — tipe & ukuran SELALU divalidasi jika file dikirim (nullable tidak skip mime check)
            'file_word'     => [
                $req,
                'file',
                'mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/pdf',
                'mimes:docx,doc,pdf',   // double-check ekstensi
                'max:5120',         // 5 MB
            ],
            'file_lampiran' => [
                'nullable',
                'file',
                'mimetypes:application/pdf,image/jpeg,image/png,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel',
                'mimes:pdf,jpg,jpeg,png,docx,doc,xlsx,xls',   // double-check ekstensi
                'max:10240',        // 10 MB
            ],
        ];
    }

    /**
     * Pesan error yang lebih ramah.
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
            'file_word.required'        => 'File surat Word (.docx) atau PDF (.pdf) wajib diupload.',
            'file_word.mimes'           => 'File surat harus berformat Word (.docx / .doc) atau PDF (.pdf).',
            'file_word.mimetypes'       => 'File surat harus berformat Word (.docx / .doc) atau PDF (.pdf). File mencurigakan ditolak.',
            'file_word.max'             => 'Ukuran file surat maksimal 5 MB.',
            'file_lampiran.mimes'       => 'Lampiran harus berupa PDF, Gambar (JPG/PNG), Word (DOCX/DOC), atau Excel (XLSX/XLS).',
            'file_lampiran.mimetypes'   => 'Tipe konten lampiran tidak diizinkan. File mencurigakan ditolak.',
            'file_lampiran.max'         => 'Ukuran lampiran maksimal 10 MB.',
        ];
    }

    /**
     * Sanitasi input teks sebelum validasi dijalankan.
     * Menghapus tag HTML dari semua field teks bebas.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'judul'            => $this->judul            ? strip_tags(trim($this->judul))           : $this->judul,
            'tujuan'           => $this->tujuan           ? strip_tags(trim($this->tujuan))          : $this->tujuan,
            'catatan_pengusul' => $this->catatan_pengusul ? strip_tags(trim($this->catatan_pengusul)): $this->catatan_pengusul,
        ]);
    }
}
