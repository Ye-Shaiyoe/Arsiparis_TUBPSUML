<x-mail::message>
# Akun Anda Telah Dibuat

Halo, **{{ $user->name }}**!

Akun Anda di **Sistem Informasi Persuratan BPSUML** telah dibuat oleh **{{ $createdByName }}**.

Berikut informasi login Anda:

<x-mail::panel>
**Email:** {{ $user->email }}
@if($user->nip)
**NIP:** {{ $user->nip }}
@endif
**Password:** `{{ $plainPassword }}`
**Role:** {{ $user->getRoleLabel() }}
</x-mail::panel>

<x-mail::button :url="$loginUrl" color="primary">
Masuk Sekarang
</x-mail::button>

> **Penting:** Segera ganti password Anda setelah login pertama melalui halaman **Profil → Ubah Password**.

Jika Anda tidak merasa mendaftar atau ada pertanyaan, hubungi administrator sistem.

Salam,
**Tim BPSUML**
</x-mail::message>
