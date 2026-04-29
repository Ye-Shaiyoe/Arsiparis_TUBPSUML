@extends('layouts.admin')
@section('title', 'Detail Surat')

@section('content')

{{-- 1. TRACKING TAHAPAN HORIZONTAL (SCROLLABLE) --}}
<div class="card mb-3" style="border-radius: 12px; overflow: hidden; background: var(--bg-secondary); border-color: var(--border-color);">
    <div class="card-body p-3 stepper-wrapper" style="overflow-x: auto; white-space: nowrap; scrollbar-width: none; -ms-overflow-style: none;">
        <style>
            .stepper-wrapper::-webkit-scrollbar { display: none; }
            .step-item-h { display: inline-flex; flex-direction: column; align-items: center; min-width: 140px; position: relative; vertical-align: top; }
            .step-line { position: absolute; top: 15px; left: 50%; width: 100%; height: 2px; background: var(--border-color); z-index: 1; opacity: 0.5; }
            .step-dot-h { width: 32px; height: 32px; border-radius: 50%; background: var(--bg-secondary); border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: var(--text-secondary); position: relative; z-index: 2; margin-bottom: 8px; transition: all 0.3s; }
            .step-item-h.active .step-dot-h { border-color: #3b82f6; color: #3b82f6; background: rgba(59, 130, 246, 0.1); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
            .step-item-h.completed .step-dot-h { border-color: #10b981; background: #10b981; color: #fff; }
            .step-item-h.completed .step-line { background: #10b981; opacity: 1; }
            .step-label-h { font-size: 10px; font-weight: 600; color: var(--text-secondary); text-align: center; white-space: normal; line-height: 1.35; width: 120px; }
            .step-item-h.active .step-label-h { color: #60a5fa; font-weight: 700; }
            .step-item-h.completed .step-label-h { color: var(--text-primary); }
            .text-orange { color: #ff8c00 !important; }
            /* Dark mode label helper */
            html.dark-mode .step-label-h { color: #94a3b8; }
            html.dark-mode .step-item-h.active .step-label-h { color: #60a5fa; }
            html.dark-mode .step-item-h.completed .step-label-h { color: #e2e8f0; }
        </style>
        <div class="d-flex">
            @for($i = 1; $i <= 10; $i++)
                @php
                    $isCurrent = $surat->tahap_sekarang == $i;
                    $isPast = $surat->tahap_sekarang > $i;
                @endphp
                <div class="step-item-h {{ $isPast ? 'completed' : ($isCurrent ? 'active' : '') }}">
                    @if($i < 10) <div class="step-line"></div> @endif
                    <div class="step-dot-h">
                        @if($isPast) <i class="bi bi-check-lg"></i> @else {{ $i }} @endif
                    </div>
                    <div class="step-label-h">{{ \App\Models\Surat::NAMA_TAHAP[$i] }}</div>
                </div>
            @endfor
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 340px; gap:16px; align-items:start;">

    {{-- KOLOM KIRI --}}
    <div style="display:flex; flex-direction:column; gap:16px;">
        
        {{-- INFO UTAMA --}}
        <div class="card" style="background: var(--bg-secondary); border-color: var(--border-color);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
                <div>
                    <h2 style="font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:8px;">{{ $surat->judul }}</h2>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <span class="badge badge-purple">{{ $surat->jenis_label }}</span>
                        @if($surat->sifat === 'segera') <span class="badge badge-red">Segera</span> @endif
                        @if($surat->status === 'selesai') <span class="badge badge-green">Selesai</span> @endif
                        @if($surat->status === 'revisi') <span class="badge badge-amber">📝 File Revisi Baru</span> @endif
                        <span class="badge {{ $surat->revisi_count > 0 ? 'badge-light text-orange' : 'badge-info text-orange' }}"> Revisi ke: {{ $surat->revisi_count }} </span>                    </div>
                </div>
                <a href="{{ route('admin.surat.index') }}" class="btn btn-sm btn-outline-secondary">← Kembali</a>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; font-size:13px;">
                <div>
                    <div style="font-size:11px; color:var(--text-secondary); margin-bottom:4px; font-weight:700; letter-spacing:0.5px; opacity: 0.8;">PENGUSUL</div>
                    <div style="font-weight:600; color:var(--text-primary);">{{ $surat->user?->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:var(--text-secondary); margin-bottom:4px; font-weight:700; letter-spacing:0.5px; opacity: 0.8;">TUJUAN SURAT</div>
                    <div style="font-weight:500; color:var(--text-primary);">{{ $surat->tujuan }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:var(--text-secondary); margin-bottom:4px; font-weight:700; letter-spacing:0.5px; opacity: 0.8;">TANGGAL PENGAJUAN</div>
                    <div style="color:var(--text-primary);">{{ $surat->created_at?->format('d M Y, H:i') ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:var(--text-secondary); margin-bottom:4px; font-weight:700; letter-spacing:0.5px; opacity: 0.8;">NOMOR SURAT</div>
                    <div style="font-weight:700; color:var(--text-primary);">{{ $surat->nomor_surat ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px; color:var(--text-secondary); margin-bottom:4px; font-weight:700; letter-spacing:0.5px; opacity: 0.8;">ESTIMASI SELESAI (SLA)</div>
                    <div style="color: {{ $surat->sla_status === 'terlambat' ? '#ef4444' : '#10b981' }}; font-weight:700;">
                        {{ $surat->deadline_sla?->format('d M Y, H:i') ?? '—' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:11px; color:var(--text-secondary); margin-bottom:4px; font-weight:700; letter-spacing:0.5px; opacity: 0.8;">PROGRESS PEMROSESAN</div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div class="progress-bar" style="flex:1; height:6px; border-radius:10px; background: var(--bg-tertiary);">
                            <div class="progress-fill" style="width: {{ $surat->proses_persen }}%; background:#3b82f6;"></div>
                        </div>
                        <span style="font-size:12px; font-weight:700; color:#3b82f6;">{{ $surat->proses_persen }}%</span>
                    </div>
                </div>
            </div>

            {{-- FILE --}}
            @php
                $extWord     = $surat->file_word     ? strtolower(pathinfo($surat->file_word,     PATHINFO_EXTENSION)) : '';
                $extLampiran = $surat->file_lampiran ? strtolower(pathinfo($surat->file_lampiran, PATHINFO_EXTENSION)) : '';
            @endphp
            <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--border-color);">
                <div style="font-size:12px; font-weight:700; color:var(--text-secondary); margin-bottom:12px; letter-spacing:0.5px; opacity: 0.8;">DOKUMEN LAMPIRAN</div>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    @if($surat->file_word)
                        <div class="btn-group">
                            <button onclick="openPreview('word', '{{ route('admin.surat.preview', [$surat, 'word']) }}', '{{ addslashes($surat->judul) }}', '{{ $extWord }}')"
                                    class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-file-earmark-word me-1"></i>Preview Word
                            </button>
                            <a href="{{ route('admin.surat.download', [$surat, 'word']) }}" class="btn btn-sm btn-dark px-3" title="Download"><i class="bi bi-download"></i></a>
                        </div>
                    @endif
                    @if($surat->file_lampiran)
                        <div class="btn-group">
                            <button onclick="openPreview('lampiran', '{{ route('admin.surat.preview', [$surat, 'lampiran']) }}', 'Lampiran', '{{ $extLampiran }}')"
                                    class="btn btn-sm btn-info text-white px-3">
                                <i class="bi bi-paperclip me-1"></i>Lampiran
                            </button>
                            <a href="{{ route('admin.surat.download', [$surat, 'lampiran']) }}" class="btn btn-sm btn-dark px-3" title="Download"><i class="bi bi-download"></i></a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIWAYAT CATATAN --}}
        <div class="card" style="background: var(--bg-secondary); border-color: var(--border-color);">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);"><i class="bi bi-chat-dots me-2 text-primary"></i>Riwayat Catatan</h6>
            @forelse($surat->tahapans->whereNotNull('catatan')->reverse() as $hist)
                <div style="padding:14px; border-radius:10px; background:var(--bg-tertiary); margin-bottom:12px; border-left:4px solid var(--border-color);">
                    <div style="display:flex; justify-content:space-between; font-size:11px; margin-bottom:6px;">
                        <span class="fw-bold" style="color:var(--text-primary);">Tahap {{ $hist->tahap }}: {{ $hist->nama_tahap }}</span>
                        <span style="color:var(--text-secondary);">{{ $hist->selesai_pada?->format('d M, H:i') ?? '-' }}</span>
                    </div>
                    <p style="font-size:13px; margin:0; color:var(--text-primary); line-height:1.5;">"{{ $hist->catatan }}"</p>
                    <div style="font-size:11px; color:var(--text-secondary); margin-top:6px; font-weight:500;">Oleh: {{ $hist->diprosesByUser?->getRoleLabel() ?? 'Admin' }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">Belum ada catatan pemrosesan.</p>
            @endforelse
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div style="display:flex; flex-direction:column; gap:16px;">
        
        {{-- UPLOAD FILE ADMIN (hanya untuk admin_aspirasi di tahap 2) --}}
        @php
            $bisa_upload_admin = Auth::check() && Auth::user()->role === 'admin_aspirasi' && $surat->tahap_sekarang === 2;
        @endphp
        @if($bisa_upload_admin)
        <div class="card" style="background: var(--bg-secondary); border-color: var(--border-color);">
            <h6 class="fw-bold text-info mb-3"><i class="bi bi-cloud-upload me-2"></i>Perbarui File</h6>
            <p style="font-size: 11px; color: var(--text-secondary); margin-bottom: 14px; line-height: 1.4;">
                Ganti file Word dan/atau lampiran. File lama akan dihapus otomatis.
            </p>
            <form action="{{ route('admin.surat.uploadFileAdmin', $surat) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label class="form-label small fw-bold" style="color:var(--text-secondary); font-size: 11px;">
                        <i class="bi bi-file-earmark-word me-1"></i> File Word <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="file_word" class="form-control form-control-sm" accept=".docx,.doc" required
                           style="background: var(--bg-tertiary); color: var(--text-primary); border-color: var(--border-color); font-size: 12px;">
                    <small class="text-muted" style="font-size: 10px;">Max 5MB</small>
                    @error('file_word')
                        <div class="text-danger small mt-1" style="font-size: 11px;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold" style="color:var(--text-secondary); font-size: 11px;">
                        <i class="bi bi-paperclip me-1"></i> Lampiran (Opsional)
                    </label>
                    <input type="file" name="file_lampiran" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png"
                           style="background: var(--bg-tertiary); color: var(--text-primary); border-color: var(--border-color); font-size: 12px;">
                    <small class="text-muted" style="font-size: 10px;">PDF/JPG/PNG, Max 10MB</small>
                    @error('file_lampiran')
                        <div class="text-danger small mt-1" style="font-size: 11px;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-sm btn-info w-100 fw-bold py-1 shadow-sm" style="font-size: 12px;">
                    <i class="bi bi-upload me-1"></i> Perbarui
                </button>
            </form>
        </div>
        @endif
        
        {{-- QR CODE (CENTERED) --}}
        <div class="card text-center" style="padding:20px; background: var(--bg-secondary); border-color: var(--border-color);">
            <div style="font-size:11px; color:var(--text-secondary); font-weight:700; margin-bottom:16px; letter-spacing:1px; opacity: 0.8;">QR VERIFIKASI DOKUMEN</div>
            <div style="display:flex; justify-content:center; align-items:center; background:#fff; padding:12px; border-radius:12px; border:1px solid var(--border-color); margin: 0 auto 12px; width: fit-content;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('surat.verifikasi', $surat->uuid)) }}" 
                     alt="QR" style="width:140px; height:140px; display:block;">
            </div>
        </div>

        {{-- AKSI --}}
        @php $canApprove = Auth::user()->canApproveTahap($surat->tahap_sekarang); @endphp
        @if(in_array($surat->status, ['proses', 'revisi']) && $canApprove)
            <div class="card" style="background: var(--bg-secondary); border-color: var(--border-color);">
                <h6 class="fw-bold text-success mb-3"><i class="bi bi-check-circle-fill me-2"></i>Setujui & Teruskan</h6>
                <form action="{{ route('admin.surat.setujui', $surat) }}" method="POST">
                    @csrf
                    @if($surat->tahap_sekarang === 5)
                        <div class="mb-3">
                            <label class="small fw-bold" style="color:var(--text-secondary);">Nomor Surat *</label>
                            <input type="text" name="nomor_surat" required class="form-control form-control-sm" style="background: var(--bg-tertiary); color: var(--text-primary); border-color: var(--border-color);" placeholder="023/Metrologi/IV/2025">
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="small fw-bold" style="color:var(--text-secondary);">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="3" class="form-control form-control-sm" style="background: var(--bg-tertiary); color: var(--text-primary); border-color: var(--border-color);" placeholder="Instruksi untuk tahap selanjutnya..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">Setujui & Teruskan</button>
                </form>
            </div>

            <div class="card" style="background: var(--bg-secondary); border-color: var(--border-color);">
                <h6 class="fw-bold text-danger mb-3"><i class="bi bi-x-circle-fill me-2"></i>Tolak Surat</h6>
                <form action="{{ route('admin.surat.tolak', $surat) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="catatan" rows="2" required class="form-control form-control-sm" style="background: var(--bg-tertiary); color: var(--text-primary); border-color: var(--border-color);" placeholder="Alasan penolakan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm" onclick="return confirm('Tolak surat ini?')">Tolak Surat</button>
                </form>
            </div>
        @elseif($surat->status === 'selesai')
            <div class="card text-center py-4" style="background: var(--bg-secondary); border-color: var(--border-color);">
                <div class="h2 mb-2">✅</div>
                <div class="fw-bold text-success">Surat Telah Selesai</div>
                <div class="small text-muted">Semua validasi rampung</div>
            </div>
        @endif

        {{-- STATUS CURRENT TAHAP --}}
        <div class="card border-0 shadow-sm" style="background:var(--sidebar-bg); color:#fff; border-radius:12px;">
            <div style="font-size:10px; opacity:0.8; font-weight:800; margin-bottom:4px; letter-spacing: 0.5px;">POSISI DOKUMEN</div>
            <div style="font-size:24px; font-weight:800; line-height: 1.1;">Tahap {{ $surat->tahap_sekarang }}<span style="font-size:14px; opacity:0.5; font-weight:400;">/10</span></div>
            <div style="font-size:13px; color:#93c5fd; font-weight:600; margin-top: 4px;">{{ $surat->nama_tahap }}</div>
        </div>

    </div>
</div>

@include('admin.surat._preview-modal-partial')
@include('admin.surat._delete-requests')

@endsection