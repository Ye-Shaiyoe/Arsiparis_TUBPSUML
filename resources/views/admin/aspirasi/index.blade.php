@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-primary);">
                <i class="bi bi-chat-right-heart-fill text-primary me-2"></i> Manajemen Aspirasi Pegawai
            </h4>
            <p class="text-muted small mb-0">Daftar saran, keluhan, dan pertanyaan dari seluruh pegawai.</p>
        </div>
        <div>
            <form action="{{ url()->current() }}" method="GET" id="filterForm">
                <select name="tahun" class="form-select shadow-sm" onchange="this.form.submit()" style="width: 150px; border-radius: 10px; font-weight: 600; border-color: #e5e7eb; font-size: 13px;">
                    <option value="">Semua Tahun</option>
                    @php $startYear = 2024; $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-custom border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3" style="font-size: 13px; width: 200px;">Pengirim</th>
                                    <th class="py-3" style="font-size: 13px;">Aspirasi</th>
                                    <th class="py-3" style="font-size: 13px; width: 120px;">Kategori</th>
                                    <th class="py-3" style="font-size: 13px; width: 120px;">Status</th>
                                    <th class="pe-4 py-3 text-end" style="font-size: 13px; width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($aspirasis as $item)
                                    <tr id="row-{{ $item->uuid }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold" style="width:32px; height:32px; font-size:12px;">
                                                    {{ strtoupper(substr($item->user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size: 13px;">{{ $item->user->name }}</div>
                                                    <div class="text-muted" style="font-size: 11px;">NIP: {{ $item->user->nip }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary mb-1" style="font-size: 13px;">{{ $item->judul }}</div>
                                            <div class="text-secondary small text-truncate" style="max-width: 400px;">{{ $item->isi }}</div>
                                            <div class="text-muted mt-1" style="font-size: 10px;">Dikirim: {{ $item->created_at->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->kategori === 'saran' ? 'bg-primary' : ($item->kategori === 'keluhan' ? 'bg-danger' : 'bg-info') }} bg-opacity-10 text-{{ $item->kategori === 'saran' ? 'primary' : ($item->kategori === 'keluhan' ? 'danger' : 'info') }}" style="font-size: 10px; border: 1px solid currentColor;">
                                                {{ ucfirst($item->kategori) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->status === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size: 10px; border: 1px solid currentColor;">Menunggu</span>
                                            @elseif($item->status === 'dibaca')
                                                <span class="badge bg-info bg-opacity-10 text-info" style="font-size: 10px; border: 1px solid currentColor;">Dibaca</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 10px; border: 1px solid currentColor;">Dibalas</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <button type="button" class="btn btn-sm btn-light border" 
                                                        data-uuid="{{ $item->uuid }}"
                                                        data-name="{{ $item->user->name }}"
                                                        data-judul="{{ $item->judul }}"
                                                        data-isi="{{ $item->isi }}"
                                                        data-balasan="{{ $item->balasan }}"
                                                        data-status="{{ $item->status }}"
                                                        onclick="showAspirasiModal(this)"
                                                        style="font-size: 11px; border-radius: 8px;">
                                                    <i class="bi bi-reply me-1"></i> {{ $item->status === 'dibalas' ? 'Lihat' : 'Balas' }}
                                                </button>
                                                <form action="{{ route('admin.aspirasi.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aspirasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border" style="font-size: 11px; border-radius: 8px;" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-chat-square-dots" style="font-size: 48px;"></i>
                                            <p class="mt-2 mb-0">Belum ada aspirasi yang masuk.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top">
                        {{ $aspirasis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL BALAS --}}
<div class="modal fade" id="aspirasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Detail Aspirasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">DARI PEGAWAI</label>
                    <div id="modalUser" class="fw-bold text-dark"></div>
                </div>
                <div class="mb-4 p-3 bg-light rounded-3">
                    <label class="form-label small fw-bold text-primary" id="modalJudul"></label>
                    <p class="mb-0 small text-secondary" id="modalIsi" style="line-height: 1.6;"></p>
                </div>

                <form id="replyForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label small fw-bold">BALASAN ADMIN</label>
                        <textarea name="balasan" id="modalBalasan" class="form-control" rows="5" placeholder="Tuliskan balasan untuk pegawai..." required style="border-radius: 12px;"></textarea>
                    </div>
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold" id="btnSubmit" style="border-radius: 10px;">Kirim Balasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showAspirasiModal(el) {
    const uuid = el.getAttribute('data-uuid');
    const name = el.getAttribute('data-name');
    const judul = el.getAttribute('data-judul');
    const isi = el.getAttribute('data-isi');
    const balasan = el.getAttribute('data-balasan');
    const status = el.getAttribute('data-status');

    document.getElementById('modalUser').innerText = name;
    document.getElementById('modalJudul').innerText = judul;
    document.getElementById('modalIsi').innerText = isi;
    document.getElementById('modalBalasan').value = (balasan === 'null' || !balasan) ? '' : balasan;
    
    const form = document.getElementById('replyForm');
    form.action = "{{ url('Admin/Aspirasi') }}/" + uuid;

    if (status === 'dibalas') {
        document.getElementById('modalBalasan').readOnly = true;
        document.getElementById('btnSubmit').style.display = 'none';
        document.getElementById('modalTitle').innerText = 'Aspirasi (Sudah Dibalas)';
    } else {
        document.getElementById('modalBalasan').readOnly = false;
        document.getElementById('btnSubmit').style.display = 'inline-block';
        document.getElementById('modalTitle').innerText = 'Balas Aspirasi';

        // Mark as read
        fetch("{{ url('Admin/Aspirasi') }}/" + uuid + "/read", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
    }

    new bootstrap.Modal(document.getElementById('aspirasiModal')).show();
}
</script>
@endsection
