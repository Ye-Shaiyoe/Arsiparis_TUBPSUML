<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItSupportTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya admin/IT yang dapat membuat tiket
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai', 'it_support']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'subjek'   => 'required|string|max:255',
            'kategori' => 'required|in:bug,error,fitur,lainnya',
            'detail'   => 'required|string',
        ];
    }

    /**
     * Custom messages (optional).
     */
    public function messages(): array
    {
        return [
            'subjek.required'   => 'Subjek tidak boleh kosong.',
            'kategori.required' => 'Pilih kategori tiket.',
            'detail.required'   => 'Detail tiket wajib diisi.',
        ];
    }
}
