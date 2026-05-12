@extends('layouts.admin')

@section('title', 'Bantuan IT Support')

@section('content')
<style>
    .bug-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .bug-card:hover {
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .bug-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .bug-badge.bug { background: #fee2e2; color: #dc2626; }
    .bug-badge.error { background: #fee2e2; color: #b91c1c; }
    .bug-badge.fitur { background: #dbeafe; color: #2563eb; }
    .bug-badge.lainnya { background: #f3f4f6; color: #6b7280; }
    .bug-badge.pending { background: #fef3c7; color: #d97706; }
    .bug-badge.dibaca { background: #dbeafe; color: #2563eb; }
    .bug-badge.dibalas { background: #d1fae5; color: #059669; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-primary);">
                <i class="bi bi-bug-fill text-danger me-2"></i>Laporan Bug & IT Support
            </h4>
            <p class="text-muted small mb-0">Kirim laporan bug, error, atau permintaan fitur ke tim IT Support.</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Form Laporan --}}
        <div class="col-12 col-lg-4">
            <div class="bug-card p-4">
                <h5 class="fw-bold mb-3" style="color: var(--text-primary);">
                    <i class="bi bi-plus-circle me-2"></i>Buat Laporan Baru
                </h5>
                <form action="{{ route('admin.bug-report.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Subjek</label>
                        <input type="text" name="subjek" class="form-control" placeholder="Contoh: Error saat upload file" required style="border-radius: 10px; font-size: 13px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Kategori</label>
                        <select name="kategori" class="form-select" required style="border-radius: 10px; font-size: 13px;">
                            <option value="bug">🐛 Bug</option>
                            <option value="error">⚠️ Error</option>
                            <option value="fitur">💡 Request Fitur</option>
                            <option value="lainnya">📝 Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Detail Laporan</label>
                        <textarea name="isi" class="form-control" rows="5" placeholder="Jelaskan detail bug/error yang ditemukan..." required style="border-radius: 10px; font-size: 13px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 10px;">
                        <i class="bi bi-send me-2"></i>Kirim Laporan
                    </button>
                </form>
            </div>
        </div>

        {{-- Daftar Laporan --}}
        <div class="col-12 col-lg-8">
            <div class="bug-card">
                <div class="d-flex align-items-center justify-content-between p-4 border-bottom" style="border-color: var(--border-color) !important;">
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary);">Riwayat Laporan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3" style="font-size: 12px;">Laporan</th>
                                <th class="py-3" style="font-size: 12px; width: 100px;">Kategori</th>
                                <th class="py-3" style="font-size: 12px; width: 100px;">Status</th>
                                <th class="pe-4 py-3 text-end" style="font-size: 12px; width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-primary mb-1" style="font-size: 13px;">{{ $item->subjek }}</div>
                                        <div class="text-muted small text-truncate" style="max-width: 350px;">{{ $item->isi }}</div>
                                        <div class="text-muted mt-1" style="font-size: 10px;">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td>
                                        <span class="bug-badge {{ $item->kategori }}">{{ $item->kategori }}</span>
                                    </td>
                                    <td>
                                        <span class="bug-badge {{ $item->status }}">{{ $item->status === 'pending' ? 'Menunggu' : ($item->status === 'dibaca' ? 'Dibaca' : 'Dibalas') }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <form action="{{ route('admin.bug-report.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border" style="font-size: 11px; border-radius: 6px;" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-bug" style="font-size: 48px;"></i>
                                        <p class="mt-2 mb-0">Belum ada laporan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif
@endsection