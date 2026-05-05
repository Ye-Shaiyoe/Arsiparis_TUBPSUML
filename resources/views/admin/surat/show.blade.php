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
            .dark .step-label-h { color: #94a3b8; }
            .dark .step-item-h.active .step-label-h { color: #60a5fa; }
            .dark .step-item-h.completed .step-label-h { color: #e2e8f0; }
            
            /* Placeholder fix for dark mode */
            ::placeholder {
                color: var(--text-secondary) !important;
                opacity: 0.7;
            }
            .dark ::placeholder {
                color: #94a3b8 !important;
                opacity: 0.8;
            }
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
                        @if($surat->status === 'revisi_admin') <span class="badge" style="background:rgba(251,191,36,0.1);color:#f59e0b;border:1px solid #fbbf24;">Admin Revisi</span> @endif
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
                @if($surat->catatan_pengusul)
                <div style="grid-column: 1 / -1; margin-top: 10px;">
                    <div style="font-size:11px; color:var(--text-secondary); margin-bottom:4px; font-weight:700; letter-spacing:0.5px; opacity: 0.8;">CATATAN DARI PENGUSUL</div>
                    <div style="font-size:13px; color:var(--text-primary); background:var(--bg-tertiary); padding:12px; border-radius:8px; border:1px solid var(--border-color); font-style:italic;">
                        "{{ $surat->catatan_pengusul }}"
                    </div>
                </div>
                @endif
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
                            <a href="{{ route('admin.surat.preview', [$surat, 'word']) }}" target="_blank"
                                    class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-file-earmark-word me-1"></i>Preview Word
                            </a>
                            <a href="{{ route('admin.surat.download', [$surat, 'word']) }}" class="btn btn-sm btn-dark px-3" title="Download"><i class="bi bi-download"></i></a>
                        </div>
                    @endif
                    @if($surat->file_lampiran)
                        <div class="btn-group">
                            <a href="{{ route('admin.surat.preview', [$surat, 'lampiran']) }}" target="_blank"
                                    class="btn btn-sm btn-info text-white px-3">
                                <i class="bi bi-paperclip me-1"></i>Lampiran
                            </a>
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
                <p class="small mb-0" style="color: var(--text-secondary); opacity: 0.7;">Belum ada catatan pemrosesan.</p>
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
                    <input type="file" name="file_lampiran" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           style="background: var(--bg-tertiary); color: var(--text-primary); border-color: var(--border-color); font-size: 12px;">
                    <small class="text-muted" style="font-size: 10px;">PDF/JPG/PNG/Word, Max 10MB</small>
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
        @if(in_array($surat->status, ['proses', 'revisi', 'revisi_admin']) && $canApprove)
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
                        <textarea name="catatan" rows="3" class="form-control form-control-sm" 
                            style="background: var(--bg-tertiary); color: var(--text-primary); border-color: var(--border-color);" 
                            placeholder="Instruksi untuk tahap selanjutnya..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">Setujui & Teruskan</button>
                </form>
            </div>

            <div class="card" style="background: var(--bg-secondary); border-color: var(--border-color);">
                <h6 class="fw-bold text-danger mb-3"><i class="bi bi-x-circle-fill me-2"></i>Tolak Surat</h6>

                {{-- Pilihan Jenis Tolak --}}
                <div style="display:flex; gap:8px; margin-bottom:14px;">
                    <label id="opt-user" onclick="setJenisTolak('ke_user')"
                        style="flex:1; cursor:pointer; border:2px solid #fca5a5; border-radius:10px; padding:10px 12px; display:flex; align-items:flex-start; gap:8px; transition:all 0.2s; background:rgba(239,68,68,0.1);">
                        <input type="radio" name="_jenis_tolak_ui" value="ke_user" checked style="margin-top:3px; accent-color:#ef4444;">
                        <div>
                            <div style="font-size:12px; font-weight:700; color:#ef4444;">↩ Kembalikan ke User</div>
                            <div style="font-size:10px; color:var(--text-secondary); margin-top:2px; line-height:1.3;">User diminta revisi / upload ulang file</div>
                        </div>
                    </label>
                    @if($surat->tahap_sekarang > 2)
                    <label id="opt-admin" onclick="setJenisTolak('ke_admin_aspirasi')"
                        style="flex:1; cursor:pointer; border:2px solid var(--border-color); border-radius:10px; padding:10px 12px; display:flex; align-items:flex-start; gap:8px; transition:all 0.2s; background:var(--bg-tertiary);">
                        <input type="radio" name="_jenis_tolak_ui" value="ke_admin_aspirasi" style="margin-top:3px; accent-color:#f59e0b;">
                        <div>
                            <div style="font-size:12px; font-weight:700; color:var(--text-primary); opacity:0.7;">🔄 Revisi Admin Aspirasi</div>
                            <div style="font-size:10px; color:var(--text-secondary); margin-top:2px; line-height:1.3;">Dikembalikan ke Admin Aspirasi (Tahap 2)</div>
                        </div>
                    </label>
                    @endif
                </div>


                <form id="form-tolak" action="{{ route('admin.surat.tolak', $surat) }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_tolak" id="input-jenis-tolak" value="ke_user">
                    <div class="mb-3">
                        <label class="small fw-bold" style="color:var(--text-secondary); font-size:11px;" id="label-catatan">Alasan Penolakan *</label>
                        <textarea name="catatan" rows="2" required class="form-control form-control-sm"
                            style="background: var(--bg-tertiary); color: var(--text-primary); border-color: var(--border-color);"
                            id="textarea-catatan"
                            placeholder="Alasan penolakan / instruksi revisi untuk user..."></textarea>
                    </div>
                    <button type="button" id="btn-tolak-submit"
                        class="btn btn-danger w-100 fw-bold py-2 shadow-sm"
                        onclick="konfirmasiTolak()">
                        <i class="bi bi-x-circle me-1"></i> <span id="label-btn-tolak">Tolak & Kembalikan ke User</span>
                    </button>
                </form>
            </div>

            {{-- Modal Konfirmasi Tolak --}}
            <div id="modal-tolak" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
                <div style="background:var(--bg-secondary); border-radius:16px; padding:28px; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.3); border:1px solid var(--border-color);">
                    <div style="font-size:32px; text-align:center; margin-bottom:12px;" id="modal-tolak-icon">❌</div>
                    <h5 style="text-align:center; font-weight:700; color:var(--text-primary); margin-bottom:8px;" id="modal-tolak-title">Tolak & Kembalikan ke User?</h5>
                    <p style="text-align:center; font-size:13px; color:var(--text-secondary); margin-bottom:20px;" id="modal-tolak-desc">
                        User akan mendapat notifikasi dan diminta untuk merevisi surat.
                    </p>
                    <div style="display:flex; gap:10px;">
                        <button type="button" onclick="tutupModal()" class="btn w-100" style="background:var(--bg-tertiary); border-color:var(--border-color); color:var(--text-primary);">
                            Batal
                        </button>
                        <button type="button" id="modal-btn-konfirmasi" onclick="submitTolak()"
                            class="btn w-100 fw-bold btn-danger">
                            Ya, Tolak Surat
                        </button>
                    </div>
                </div>
            </div>

        @elseif($surat->status === 'selesai')
            <div class="card text-center py-4" style="background: var(--bg-secondary); border-color: var(--border-color);">
                <div class="h2 mb-2">✅</div>
                <div class="fw-bold text-success">Surat Telah Selesai</div>
                <div class="small text-muted">Semua validasi rampung</div>
            </div>
        @endif

        {{-- STATUS CURRENT TAHAP --}}
        <div class="card border-0 shadow-sm" style="background:var(--bg-tertiary); border:1px solid var(--border-color); border-radius:12px;">
            <div style="font-size:10px; color:var(--text-secondary); font-weight:800; margin-bottom:4px; letter-spacing: 0.5px; opacity:0.8;">POSISI DOKUMEN</div>
            <div style="font-size:24px; font-weight:800; line-height: 1.1; color:var(--text-primary);">Tahap {{ $surat->tahap_sekarang }}<span style="font-size:14px; opacity:0.5; font-weight:400;">/10</span></div>
            <div style="font-size:13px; color:#3b82f6; font-weight:600; margin-top: 4px;">{{ $surat->nama_tahap }}</div>
        </div>

    </div>
</div>

@include('admin.surat._delete-requests')

@endsection

@push('scripts')
<script>
// ==== LOGIKA TOLAK SURAT ====
let jenisTolakAktif = 'ke_user';

function setJenisTolak(jenis) {
    jenisTolakAktif = jenis;
    document.getElementById('input-jenis-tolak').value = jenis;

    const optUser  = document.getElementById('opt-user');
    const optAdmin = document.getElementById('opt-admin');
    const btnLabel = document.getElementById('label-btn-tolak');
    const textarea = document.getElementById('textarea-catatan');
    const btnEl    = document.getElementById('btn-tolak-submit');

    if (!optUser || !optAdmin) return;

    if (jenis === 'ke_user') {
        optUser.style.borderColor  = '#ef4444';
        optUser.style.background   = 'rgba(239,68,68,0.1)';
        optUser.querySelector('.fw-bold').style.color = '#ef4444';
        optUser.querySelector('.fw-bold').style.opacity = '1';

        optAdmin.style.borderColor = 'var(--border-color)';
        optAdmin.style.background  = 'var(--bg-tertiary)';
        optAdmin.querySelector('.fw-bold').style.color = 'var(--text-primary)';
        optAdmin.querySelector('.fw-bold').style.opacity = '0.7';

        btnLabel.textContent       = 'Tolak & Kembalikan ke User';
        btnEl.className            = 'btn btn-danger w-100 fw-bold py-2 shadow-sm';
        btnEl.style.background     = '';
        btnEl.style.borderColor    = '';
        textarea.placeholder       = 'Alasan penolakan / instruksi revisi untuk user...';
        optUser.querySelector('input').checked = true;
    } else {
        optAdmin.style.borderColor = '#f59e0b';
        optAdmin.style.background  = 'rgba(245,158,11,0.1)';
        optAdmin.querySelector('.fw-bold').style.color = '#f59e0b';
        optAdmin.querySelector('.fw-bold').style.opacity = '1';

        optUser.style.borderColor  = 'var(--border-color)';
        optUser.style.background   = 'var(--bg-tertiary)';
        optUser.querySelector('.fw-bold').style.color = 'var(--text-primary)';
        optUser.querySelector('.fw-bold').style.opacity = '0.7';

        btnLabel.textContent       = 'Revisi ke Admin Aspirasi';
        btnEl.className            = 'btn w-100 fw-bold py-2 shadow-sm';
        btnEl.style.background     = '#f59e0b';
        btnEl.style.color          = '#fff';
        btnEl.style.borderColor    = '#f59e0b';
        textarea.placeholder       = 'Alasan / catatan untuk Admin Aspirasi...';
        optAdmin.querySelector('input').checked = true;
    }
}

function konfirmasiTolak() {
    const catatan = document.getElementById('textarea-catatan').value.trim();
    if (!catatan) {
        document.getElementById('textarea-catatan').focus();
        alert('Harap isi alasan penolakan terlebih dahulu.');
        return;
    }

    const modal       = document.getElementById('modal-tolak');
    const icon        = document.getElementById('modal-tolak-icon');
    const title       = document.getElementById('modal-tolak-title');
    const desc        = document.getElementById('modal-tolak-desc');
    const btnKonfirm  = document.getElementById('modal-btn-konfirmasi');

    if (jenisTolakAktif === 'ke_admin_aspirasi') {
        icon.textContent        = '🔄';
        title.textContent       = 'Kembalikan ke Admin Aspirasi?';
        desc.textContent        = 'Surat akan dikembalikan ke Tahap 2 (Admin Aspirasi) untuk direvisi. User akan mendapat notifikasi.';
        btnKonfirm.textContent  = 'Ya, Kembalikan ke Admin Aspirasi';
        btnKonfirm.className    = 'btn w-100 fw-bold';
        btnKonfirm.style.background   = '#f59e0b';
        btnKonfirm.style.color        = '#fff';
        btnKonfirm.style.borderColor  = '#f59e0b';
    } else {
        icon.textContent        = '❌';
        title.textContent       = 'Tolak & Kembalikan ke User?';
        desc.textContent        = 'User akan mendapat notifikasi bahwa suratnya ditolak dan diminta untuk merevisi.';
        btnKonfirm.textContent  = 'Ya, Tolak Surat';
        btnKonfirm.className    = 'btn w-100 fw-bold btn-danger';
        btnKonfirm.style.background  = '';
        btnKonfirm.style.color       = '';
        btnKonfirm.style.borderColor = '';
    }

    modal.style.display = 'flex';
}

function tutupModal() {
    document.getElementById('modal-tolak').style.display = 'none';
}

function submitTolak() {
    tutupModal();
    document.getElementById('form-tolak').submit();
}

// Tutup modal jika klik background
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal-tolak');
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) tutupModal();
        });
    }
});
</script>
@endpush