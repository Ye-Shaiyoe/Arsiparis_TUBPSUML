@extends('layouts.user')
@section('title', $title)

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 animate-in">
    <div>
        <h5 class="fw-bold mb-0" style="color:#1e3a5f;">
            {!! request('status') === 'draft' ? '📝' : '📬' !!} {{ $title }}
        </h5>
        <small class="text-muted">{{ request('status') === 'draft' ? 'Daftar surat yang masih dalam bentuk draf' : 'Semua surat yang pernah kamu ajukan' }}</small>
    </div>
    <a href="{{ route('user.surat.create') }}" class="btn btn-primary d-flex align-items-center gap-2"
       style="background:#1e3a5f;border-color:#1e3a5f;border-radius:9px;font-size:13px;font-weight:600;">
        <i class="bi bi-plus-circle-fill"></i> Ajukan Surat Baru
    </a>
</div>

{{-- FILTER --}}
<div class="card card-custom mb-3 animate-in" style="animation-delay: 0.1s;">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('user.surat.index') }}"
              class="d-flex gap-3 align-items-end flex-wrap">
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">STATUS</label>
                <select name="status" class="form-select form-select-sm" style="font-size:13px;border-radius:7px;width:130px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                    <option value="">Semua</option>
                    <option value="proses"  {{ request('status')==='proses'  ? 'selected':'' }}>Proses</option>
                    <option value="revisi"  {{ request('status')==='revisi'  ? 'selected':'' }}>Revisi</option>
                    <option value="selesai" {{ request('status')==='selesai' ? 'selected':'' }}>Selesai</option>
                    <option value="ditolak" {{ request('status')==='ditolak' ? 'selected':'' }}>Ditolak</option>
                    <option value="draft"   {{ request('status')==='draft'   ? 'selected':'' }}>Draf</option>
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">JENIS</label>
                <select name="jenis" class="form-select form-select-sm" style="font-size:13px;border-radius:7px;width:160px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                    <option value="">Semua Jenis</option>
                    @foreach(\App\Models\Surat::JENIS_LABEL as $val => $label)
                        <option value="{{ $val }}" {{ request('jenis')===$val ? 'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">TAHUN</label>
                <select name="tahun" class="form-select form-select-sm" style="font-size:13px;border-radius:7px;width:100px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                    <option value="">Semua</option>
                    @php $startYear = 2024; $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">BULAN</label>
                <select name="bulan" class="form-select form-select-sm" style="font-size:13px;border-radius:7px;width:120px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                    <option value="">Semua</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">CARI JUDUL</label>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 7px 0 0 7px; border-color: #e5e7eb;">
                        <i class="bi bi-search" style="color: #6b7280;"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" class="form-control border-start-0" 
                           placeholder="Ketik judul surat..." 
                           value="{{ request('search') }}"
                           autocomplete="off"
                           style="font-size:13px;border-radius:0 7px 7px 0;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary" style="background:#1e3a5f;border-color:#1e3a5f;border-radius:7px;font-size:12px;">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('user.surat.index') }}" class="btn btn-sm btn-light" style="border-radius:7px;font-size:12px;background:#f9fafb;color:#111827;border-color:#e5e7eb;">Reset</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let searchTimer;

        if (searchInput) {
            // Restore focus and cursor position if searched
            if (searchInput.value.length > 0) {
                searchInput.focus();
                searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    this.closest('form').submit();
                }, 600); // Debounce 600ms
            });
        }
    });
</script>

{{-- LIST SURAT --}}
@if($surats->isEmpty())
    <div class="card card-custom animate-in" style="animation-delay: 0.2s;">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-envelope-open" style="font-size:42px;display:block;margin-bottom:12px;color:#cbd5e1;"></i>
            <div style="font-size:14px;font-weight:500;">Belum ada surat yang ditemukan</div>
            <div style="font-size:12px;margin-top:4px;">
                <a href="{{ route('user.surat.create') }}" class="text-primary text-decoration-none fw-semibold">Ajukan sekarang →</a>
            </div>
        </div>
    </div>
@else
    <div class="d-flex flex-column gap-3">
    @foreach($surats as $index => $surat)
        <div class="card card-custom animate-in" style="animation-delay: {{ 0.2 + ($index * 0.05) }}s;">
            <div class="card-body px-4 py-3">
                <div class="d-flex align-items-start gap-3">

                    {{-- Status indicator --}}
                    <div style="
                        width:42px;height:42px;border-radius:10px;flex-shrink:0;
                        display:flex;align-items:center;justify-content:center;font-size:18px;
                        background:{{ $surat->status==='selesai' ? '#dcfce7' : ($surat->status==='ditolak' ? '#fee2e2' : ($surat->status==='draft' ? '#f1f5f9' : ($surat->status==='revisi' ? '#fef3c7' : '#dbeafe'))) }}">
                        {{ $surat->status==='selesai' ? '✅' : ($surat->status==='ditolak' ? '❌' : ($surat->status==='draft' ? '📝' : ($surat->status==='revisi' ? '✍️' : '⏳'))) }}
                    </div>

                    <div class="flex-1 min-w-0" style="flex:1;">
                        <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                            <div>
                                <div class="fw-semibold" style="font-size:14px;color:#111827;">
                                    {{ $surat->judul }}
                                </div>
                                <div class="d-flex gap-2 mt-1 flex-wrap align-items-center">
                                    <span class="badge rounded-pill" style="font-size:10px;background:#ede9fe;color:#6d28d9;">
                                        {{ $surat->jenis_label }}
                                    </span>
                                    <span class="badge rounded-pill badge-{{ $surat->sifat }}" style="font-size:10px;">
                                        {{ ucfirst($surat->sifat) }}
                                    </span>
                                    <span class="text-muted" style="font-size:11px;">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $surat->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                @if($surat->status==='selesai')
                                    <span class="badge rounded-pill" style="background:#dcfce7;color:#15803d;font-size:11px;padding:4px 10px;">✓ Selesai</span>
                                @elseif($surat->status==='ditolak')
                                    <span class="badge rounded-pill" style="background:#fee2e2;color:#b91c1c;font-size:11px;padding:4px 10px;">✗ Ditolak</span>
                                @elseif($surat->status==='revisi')
                                    <span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:11px;padding:4px 10px;">📝 Revisi</span>
                                @elseif($surat->status==='draft')
                                    <span class="badge rounded-pill" style="background:#f1f5f9;color:#64748b;font-size:11px;padding:4px 10px;">📄 Draf</span>
                                @elseif($surat->sla_status==='terlambat')
                                    <span class="badge rounded-pill" style="background:#fee2e2;color:#b91c1c;font-size:11px;padding:4px 10px;">⚠ SLA Terlambat</span>
                                @else
                                    <span class="badge rounded-pill" style="background:#dbeafe;color:#1d4ed8;font-size:11px;padding:4px 10px;">⏱ Proses</span>
                                @endif
                                @if($surat->status==='draft')
                                    <a href="{{ route('user.surat.edit', $surat) }}"
                                       class="btn btn-sm" style="font-size:12px;border:1px solid #e5e7eb;border-radius:7px;color:#2563eb;font-weight:500;background:#ffffff;">
                                        Edit Draf <i class="bi bi-pencil-square ms-1"></i>
                                    </a>
                                @else
                                    <a href="{{ route('user.surat.show', $surat) }}"
                                       class="btn btn-sm" style="font-size:12px;border:1px solid #e5e7eb;border-radius:7px;color:#1e3a5f;font-weight:500;background:#ffffff;">
                                        Detail <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                @endif
                                {{-- Tombol Hapus --}}
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        style="font-size:12px;border-radius:7px;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $surat->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Mini progress --}}
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <div class="progress flex-1" style="height:4px;border-radius:99px;background:#e5e7eb;">
                                <div class="progress-bar" style="width:{{ $surat->proses_persen }}%;background:#1e3a5f;border-radius:99px;"></div>
                            </div>
                            <span style="font-size:10px;color:#6b7280;white-space:nowrap;">
                                Tahap {{ $surat->tahap_sekarang }}/10 · {{ $surat->nama_tahap }}
                            </span>
                        </div>

                        {{-- Nomor surat jika ada --}}
                        @if($surat->nomor_surat)
                            <div style="font-size:11px;color:#6b7280;margin-top:4px;">
                                <i class="bi bi-hash me-1"></i><strong>No. Surat:</strong> {{ $surat->nomor_surat }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>

    <div class="mt-3">{{ $surats->links() }}</div>
@endif

@endsection

{{-- Modal Hapus Surat — dirender di luar <main> via @stack('modals') --}}
@push('modals')
@foreach($surats as $surat)
<div class="modal fade" id="deleteModal{{ $surat->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $surat->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('user.surat.requestDelete', $surat) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header" style="background:#fee2e2;border-bottom:1px solid #fca5a5;">
                    <h5 class="modal-title" id="deleteModalLabel{{ $surat->id }}" style="color:#b91c1c;">
                        <i class="bi bi-trash"></i> Hapus Surat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:#111827;">
                        Apakah Anda yakin ingin menghapus surat:
                    </p>
                    <div class="alert alert-light" style="border-left:4px solid #1e3a5f;font-size:13px;background:#f9fafb;color:#111827;border-color:#e5e7eb;">
                        <strong>{{ $surat->judul }}</strong><br>
                        <span class="text-muted">{{ $surat->jenis_label }} · {{ $surat->created_at->format('d M Y') }}</span>
                    </div>
                    
                    @php
                        $bisaLangsungHapus = in_array($surat->status, ['draft', 'ditolak', 'selesai']) || $surat->sla_status === 'terlambat';
                        $existingRequest = \App\Models\SuratDeleteRequest::where('surat_id', $surat->id)->where('status', 'pending')->first();
                    @endphp

                    @if($bisaLangsungHapus)
                        <div class="alert alert-warning" style="font-size:13px;">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Surat ini akan <strong>langsung dihapus</strong> tanpa perlu persetujuan admin.
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#111827;">
                                Alasan Penghapusan <span class="text-muted">(Opsional)</span>
                            </label>
                            <textarea name="alasan" class="form-control" rows="2" 
                                      placeholder="Jelaskan alasan penghapusan surat..." 
                                      style="font-size:13px;background:#ffffff;color:#111827;border-color:#e5e7eb;"></textarea>
                        </div>
                    @elseif($existingRequest)
                        <div class="alert alert-info" style="font-size:13px;">
                            <i class="bi bi-clock-history"></i> 
                            Permintaan hapus sedang menunggu persetujuan admin.
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:#111827;">
                                Alasan Penghapusan <span class="text-danger">*</span>
                            </label>
                            <textarea name="alasan" class="form-control" rows="3" 
                                      placeholder="Jelaskan alasan penghapusan surat..." 
                                      required 
                                      style="font-size:13px;background:#ffffff;color:#111827;border-color:#e5e7eb;"></textarea>
                            <small class="text-muted">Permintaan akan dikirim ke admin untuk disetujui.</small>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:13px;">
                        Batal
                    </button>
                    @if(!$bisaLangsungHapus && !$existingRequest)
                        <button type="submit" class="btn btn-danger" style="font-size:13px;">
                            <i class="bi bi-send"></i> Kirim Permintaan
                        </button>
                    @else
                        <button type="submit" class="btn btn-danger" style="font-size:13px;">
                            <i class="bi bi-trash"></i> Ya, Hapus Sekarang
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endpush