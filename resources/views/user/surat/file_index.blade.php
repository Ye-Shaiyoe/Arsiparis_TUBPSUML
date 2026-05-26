@extends('layouts.user')
@section('title', $title)

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 animate-in">
    <div>
        <h5 class="fw-bold mb-0" style="color:#1e3a5f;">
            📁 {{ $title }}
        </h5>
        <small class="text-muted">Hapus file fisik untuk <strong> Keamanan sistem </strong>setelah proses selesai.</small>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" style="border-radius:8px; border:1px solid #e5e7eb;">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

{{-- ALERT INFO --}}
<div class="alert alert-info border-0 shadow-sm mb-4 animate-in" style="border-radius:16px; background:#eff6ff; color:#1e40af; border-left: 5px solid #3b82f6 !important;">
    <div class="d-flex align-items-center gap-3 p-1">
        <div style="font-size:24px;">💡</div>
        <div style="font-size:13px;">
            Hanya surat dengan status <strong>Selesai</strong> yang muncul di sini. Menghapus file fisik <strong>tidak akan menghapus</strong> data tracking atau riwayat surat Anda.
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="card card-custom mb-3 animate-in" style="animation-delay: 0.1s;">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('user.surat.file_index') }}" data-turbo="false"
              class="d-flex gap-3 align-items-end flex-wrap">
            <div style="flex: 1; min-width: 250px;">
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">CARI JUDUL</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 7px 0 0 7px; border-color: #e5e7eb;">
                        <i class="bi bi-search" style="color: #6b7280;"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Ketik judul surat..." 
                           value="{{ request('search') }}"
                           style="font-size:13px;border-radius:0 7px 7px 0;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary" style="background:#1e3a5f;border-color:#1e3a5f;border-radius:7px;font-size:12px;">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('user.surat.file_index') }}" class="btn btn-sm btn-light" style="border-radius:7px;font-size:12px;background:#f9fafb;color:#111827;border-color:#e5e7eb;">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- LIST SURAT --}}
<div class="card card-custom animate-in" style="animation-delay: 0.2s;">
    <div class="card-body p-0">
        @if($surats->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-folder2-open" style="font-size:42px;display:block;margin-bottom:12px;color:#cbd5e1;"></i>
                <div style="font-size:14px;font-weight:500;">Tidak ada file fisik yang perlu dibersihkan</div>
                <div style="font-size:12px;margin-top:4px;">Semua file fisik sudah dibersihkan atau belum ada surat selesai.</div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:13px;">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 border-0" style="width: 50px;">#</th>
                            <th class="border-0">Informasi Surat</th>
                            <th class="border-0">File Tersedia</th>
                            <th class="border-0 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surats as $index => $surat)
                        <tr>
                            <td class="ps-4 align-middle text-muted">{{ $loop->iteration + ($surats->currentPage()-1)*$surats->perPage() }}</td>
                            <td class="align-middle">
                                <div class="fw-bold text-dark">{{ $surat->judul }}</div>
                                <div class="text-muted small">
                                    {{ $surat->jenis_label }} &bull; Selesai pada {{ $surat->updated_at->format('d M Y') }}
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-2">
                                    @if($surat->file_word)
                                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:10px; font-weight:600;">
                                            <i class="bi bi-file-earmark-word me-1"></i>Word
                                        </span>
                                    @endif
                                    @if($surat->file_lampiran)
                                        <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:10px; font-weight:600;">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>Lampiran
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="align-middle text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('user.surat.show', $surat) }}" class="btn btn-sm btn-light" style="border-radius:8px; font-size:11px;" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" 
                                            style="border-radius:8px; font-size:11px; font-weight:600;"
                                            data-bs-toggle="modal" data-bs-target="#purgeModal{{ $surat->id }}">
                                        <i class="bi bi-trash"></i> Hapus File
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="mt-3">
    {{ $surats->links() }}
</div>

{{-- MODALS --}}
@push('modals')
@foreach($surats as $surat)
<div class="modal fade" id="purgeModal{{ $surat->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border-radius:20px; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:60px; height:60px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:24px;"></i>
                </div>
                <h6 class="fw-bold text-dark">Hapus File Fisik?</h6>
                <p class="text-muted small mb-4">
                    Apakah Anda yakin ingin menghapus file Word dan Lampiran untuk surat <strong>"{{ $surat->judul }}"</strong>?<br>
                    <span class="text-danger fw-bold">Tindakan ini tidak bisa dibatalkan.</span>
                </p>
                
                <form action="{{ route('user.surat.purgeFiles', $surat) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 py-2" data-bs-dismiss="modal" style="border-radius:10px; font-size:13px; font-weight:600;">Batal</button>
                        <button type="submit" class="btn btn-danger w-100 py-2" style="border-radius:10px; font-size:13px; font-weight:600;">Ya, Hapus File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endpush

@endsection
