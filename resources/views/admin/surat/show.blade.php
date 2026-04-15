@extends('layouts.admin')
@section('title', 'Detail Surat')

@section('content')

<div style="display:grid; grid-template-columns:1fr 340px; gap:16px; align-items:start;">

    {{-- KOLOM KIRI: INFO SURAT + TRACKING --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- INFO UTAMA --}}
        <div class="card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
                <div>
                    <h2 style="font-size:18px; font-weight:700; color:#111827; margin-bottom:6px;">
                        {{ $surat->judul }}
                    </h2>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <span class="badge badge-purple">{{ $surat->jenis_label }}</span>
                        @if($surat->sifat === 'segera')
                            <span class="badge badge-red">Segera</span>
                        @elseif($surat->sifat === 'rahasia')
                            <span class="badge badge-amber">Rahasia</span>
                        @else
                            <span class="badge badge-gray">Biasa</span>
                        @endif
                        @if($surat->status === 'selesai')
                            <span class="badge badge-green">✓ Selesai</span>
                        @elseif($surat->status === 'ditolak')
                            <span class="badge badge-red">✗ Ditolak</span>
                        @elseif($surat->status === 'revisi')
                            <span class="badge badge-amber">📝 Revisi ke-{{ $surat->revisi_count }}</span>
                        @else
                            <span class="badge badge-amber">● Proses</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.surat.index') }}" class="btn btn-sm">← Kembali</a>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; font-size:13px;">
                <div>
                    <div style="color:#6b7280; font-size:11px; margin-bottom:2px;">Pengusul</div>
                    <div style="font-weight:500;">{{ $surat->user?->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="color:#6b7280; font-size:11px; margin-bottom:2px;">Tujuan Surat</div>
                    <div>{{ $surat->tujuan }}</div>
                </div>
                <div>
                    <div style="color:#6b7280; font-size:11px; margin-bottom:2px;">Tanggal Pengajuan</div>
                    <div>{{ $surat->created_at?->format('d M Y, H:i') ?? '—' }}</div>
                </div>
                <div>
                    <div style="color:#6b7280; font-size:11px; margin-bottom:2px;">Nomor Surat</div>
                    <div>{{ $surat->nomor_surat ?? '— (belum dinomori)' }}</div>
                </div>
                <div>
                    <div style="color:#6b7280; font-size:11px; margin-bottom:2px;">Deadline SLA</div>
                    <div @style([
                        'color' => $surat->sla_status === 'terlambat' ? '#b91c1c' : '#374151',
                        'font-weight' => '500',
                    ])>
                        {{ $surat->deadline_sla ? $surat->deadline_sla->format('d M Y, H:i') : '—' }}
                        @if($surat->sla_status === 'terlambat') ⚠ Terlambat @endif
                    </div>
                </div>
                <div>
                    <div style="color:#6b7280; font-size:11px; margin-bottom:2px;">Progress</div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div class="progress-bar" style="flex:1;">
                            <div
                                class="progress-fill"
                                @style(['width' => min(100, max(0, (int) $surat->proses_persen)).'%'])
                            ></div>
                        </div>
                        <span style="font-size:12px; font-weight:600; color:#1d4ed8;">{{ $surat->proses_persen }}%</span>
                    </div>
                </div>
            </div>

            {{-- FILE --}}
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid #f3f4f6;">
                <div style="font-size:12px; color:#6b7280; margin-bottom:10px; font-weight:600; letter-spacing:.5px;">LAMPIRAN</div>
                
                @if($surat->file_dihapus_pada)
                    <div style="padding:12px; background:#fef3c7; border-radius:6px; border-left:4px solid #f59e0b; font-size:12px; color:#92400e;">
                        ⚠️ File sudah kadaluarsa dan dihapus (3 hari setelah persetujuan). Riwayat surat masih tersedia.
                    </div>
                @else
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">

                        {{-- File Word --}}
                        @if($surat->file_word)
                            <div style="display:flex; gap:6px;">
                                <button type="button"
                                        onclick="openPreview('word', '{{ route('admin.surat.preview', [$surat, 'word']) }}', '{{ $surat->judul }}', 'word')"
                                        class="btn btn-sm btn-primary"
                                        style="display:inline-flex; align-items:center; gap:5px;">
                                    👁 Preview .docx
                                </button>
                                <a href="{{ route('admin.surat.download', [$surat, 'word']) }}"
                                   class="btn btn-sm"
                                   style="display:inline-flex; align-items:center; gap:5px;">
                                    ⬇ Download .docx
                                </a>
                            </div>
                        @endif

                        {{-- File Lampiran --}}
                        @if($surat->file_lampiran)
                            @php
                                $lampiranExt = strtolower(pathinfo($surat->file_lampiran, PATHINFO_EXTENSION));
                                $isPdf = $lampiranExt === 'pdf';
                            @endphp
                            <div style="display:flex; gap:6px;">
                                <button type="button"
                                        onclick="openPreview('lampiran', '{{ route('admin.surat.preview', [$surat, 'lampiran']) }}', '{{ $surat->judul }} – Lampiran', '{{ $lampiranExt }}')"
                                        class="btn btn-sm btn-primary"
                                        style="display:inline-flex; align-items:center; gap:5px;">
                                    👁 Preview Lampiran
                                </button>
                                <a href="{{ route('admin.surat.download', [$surat, 'lampiran']) }}"
                                   class="btn btn-sm"
                                   style="display:inline-flex; align-items:center; gap:5px;">
                                    ⬇ Download Lampiran
                                </a>
                            </div>
                        @endif

                    </div>
                    
                    @if($surat->file_expires_at && $surat->status === 'selesai')
                        <div style="margin-top:10px; padding:8px 12px; background:#eff6ff; border-radius:6px; border-left:4px solid #3b82f6; font-size:11px; color:#1d4ed8;">
                            ℹ️ File akan dihapus otomatis pada: <strong>{{ $surat->file_expires_at->format('d M Y, H:i') }}</strong> (3 hari setelah persetujuan)
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- TRACKING TAHAPAN --}}
        <div class="card">
            <h2 style="font-size:15px; font-weight:600; margin-bottom:16px;">📍 Riwayat Tahapan</h2>
            <div style="position:relative;">
                @foreach($surat->tahapans as $tahapan)
                    @php
                        $tahapanTitleColor = match ($tahapan->status) {
                            'proses' => '#1d4ed8',
                            'menunggu' => '#9ca3af',
                            default => '#111827',
                        };
                        $tahapanCircleColors = match ($tahapan->status) {
                            'selesai' => ['background' => '#dcfce7', 'color' => '#15803d'],
                            'proses' => ['background' => '#dbeafe', 'color' => '#1d4ed8'],
                            'ditolak' => ['background' => '#fee2e2', 'color' => '#b91c1c'],
                            default => ['background' => '#f3f4f6', 'color' => '#9ca3af'],
                        };
                    @endphp
                    <div style="display:flex; gap:14px; margin-bottom:0;">
                        {{-- Garis vertikal --}}
                        <div style="display:flex; flex-direction:column; align-items:center; width:28px; flex-shrink:0;">
                            {{-- Lingkaran status --}}
                            <div @style(array_merge([
                                'width' => '28px',
                                'height' => '28px',
                                'border-radius' => '50%',
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'font-size' => '12px',
                                'font-weight' => '700',
                                'flex-shrink' => '0',
                            ], $tahapanCircleColors))>
                                @if($tahapan->status === 'selesai') ✓
                                @elseif($tahapan->status === 'proses') →
                                @elseif($tahapan->status === 'ditolak') ✗
                                @else {{ $tahapan->tahap }}
                                @endif
                            </div>
                            {{-- Garis --}}
                            @if(!$loop->last)
                                <div @style([
                                    'width' => '2px',
                                    'flex' => '1',
                                    'min-height' => '24px',
                                    'background' => $tahapan->status === 'selesai' ? '#86efac' : '#e5e7eb',
                                    'margin' => '4px 0',
                                ])></div>
                            @endif
                        </div>

                        {{-- Konten tahapan --}}
                        <div @style(['padding-bottom' => $loop->last ? '0' : '20px', 'flex' => '1'])>
                            <div style="display:flex; align-items:center; justify-content:space-between;">
                                <div @style(['font-size' => '13px', 'font-weight' => '600', 'color' => $tahapanTitleColor])>
                                    {{ $tahapan->nama_tahap }}
                                </div>
                                @if($tahapan->selesai_pada)
                                    <div style="font-size:11px; color:#9ca3af;">
                                        {{ $tahapan->selesai_pada->format('d M Y, H:i') }}
                                    </div>
                                @endif
                            </div>
                            @if($tahapan->diprosesByUser)
                                <div style="font-size:11px; color:#6b7280; margin-top:2px;">
                                    oleh {{ $tahapan->diprosesByUser->name }}
                                </div>
                            @endif
                            @if($tahapan->catatan)
                                <div style="font-size:12px; color:#374151; margin-top:4px;
                                            background:#f9fafb; padding:6px 10px; border-radius:6px;
                                            border-left:3px solid #e5e7eb;">
                                    {{ $tahapan->catatan }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN: AKSI --}}
    <div style="display:flex; flex-direction:column; gap:12px;">

        @php
            $canApprove = Auth::user()->canApproveTahap($surat->tahap_sekarang);
        @endphp

        @if($surat->status === 'proses' && $canApprove)

            {{-- SETUJUI --}}
            <div class="card">
                <h2 style="font-size:14px; font-weight:600; margin-bottom:12px; color:#15803d;">
                    ✅ Setujui & Teruskan
                </h2>
                <form action="{{ route('admin.surat.setujui', $surat) }}" method="POST">
                    @csrf

                    {{-- Input nomor surat hanya muncul di tahap 5 --}}
                    @if($surat->tahap_sekarang === 5)
                        <div style="margin-bottom:10px;">
                            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">
                                Nomor Surat <span style="color:#b91c1c;">*</span>
                            </label>
                            <input type="text" name="nomor_surat" required
                                   placeholder="Contoh: 024/KU.01/IV/2025"
                                   style="width:100%; padding:7px 10px; border:1px solid #e5e7eb;
                                          border-radius:7px; font-size:13px; box-sizing:border-box;">
                        </div>
                    @endif

                    <div style="margin-bottom:10px;">
                        <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">
                            Catatan (opsional)
                        </label>
                        <textarea name="catatan" rows="3" placeholder="Tambahkan catatan..."
                                  style="width:100%; padding:7px 10px; border:1px solid #e5e7eb;
                                         border-radius:7px; font-size:13px; resize:vertical; box-sizing:border-box;">
                        </textarea>
                    </div>

                    <div style="font-size:12px; color:#6b7280; margin-bottom:10px; padding:8px 10px;
                                background:#f0fdf4; border-radius:6px; border:1px solid #bbf7d0;">
                        Tahap berikutnya:
                        <strong style="color:#15803d;">
                            {{ \App\Models\Surat::NAMA_TAHAP[$surat->tahap_sekarang + 1] ?? 'Selesai' }}
                        </strong>
                    </div>

                    <button type="submit" class="btn btn-success" style="width:100%;">
                        ✓ Setujui & Teruskan
                    </button>
                </form>
            </div>

            {{-- TOLAK --}}
            <div class="card">
                <h2 style="font-size:14px; font-weight:600; margin-bottom:12px; color:#b91c1c;">
                    ✗ Tolak Surat
                </h2>
                <form action="{{ route('admin.surat.tolak', $surat) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:10px;">
                        <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">
                            Alasan Penolakan <span style="color:#b91c1c;">*</span>
                        </label>
                        <textarea name="catatan" rows="3" required
                                  placeholder="Tuliskan alasan penolakan..."
                                  style="width:100%; padding:7px 10px; border:1px solid #e5e7eb;
                                         border-radius:7px; font-size:13px; resize:vertical; box-sizing:border-box;">
                        </textarea>
                        @error('catatan')
                            <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger" style="width:100%;"
                            onclick="return confirm('Yakin ingin menolak surat ini?')">
                        ✗ Tolak Surat
                    </button>
                </form>
            </div>

        @elseif($surat->status === 'proses' && !$canApprove)
            <div class="card" style="text-align:center; padding:24px; background:#fef3c7; border:1px solid #f59e0b;">
                <div style="font-size:32px; margin-bottom:8px;">⚠️</div>
                <div style="font-size:14px; font-weight:600; color:#92400e;">Anda Tidak Berwenang</div>
                <div style="font-size:12px; color:#a16207; margin-top:4px;">
                    Role Anda ({{ Auth::user()->getRoleLabel() }}) tidak sesuai untuk tahap ini.
                </div>
            </div>

        @elseif($surat->status === 'selesai')
            <div class="card" style="text-align:center; padding:24px;">
                <div style="font-size:32px; margin-bottom:8px;">✅</div>
                <div style="font-size:14px; font-weight:600; color:#15803d;">Surat Selesai</div>
                <div style="font-size:12px; color:#6b7280; margin-top:4px;">Semua tahapan telah selesai</div>
            </div>
        @elseif($surat->status === 'revisi')
            {{-- FILE REVISI MENUNGGU REVIEW --}}
            <div class="card" style="text-align:center; padding:24px; background:#fef3c7; border:1px solid #f59e0b;">
                <div style="font-size:32px; margin-bottom:8px;">📝</div>
                <div style="font-size:14px; font-weight:600; color:#92400e;">File Perbaikan Menunggu Review</div>
                <div style="font-size:12px; color:#a16207; margin-top:4px;">
                    User sudah upload file revisi ke-{{ $surat->revisi_count }} pada {{ $surat->revisi_uploaded_at?->format('d M Y, H:i') }}
                </div>
                <div style="font-size:11px; color:#6b7280; margin-top:8px; padding:8px; background:rgba(255,255,255,0.5); border-radius:6px;">
                    Silakan review file baru dan klik Setujui atau Tolak di bawah.
                </div>
            </div>

            {{-- Tampilkan form setujui & tolak untuk status revisi --}}
            @php
                $canApprove = Auth::user()->canApproveTahap($surat->tahap_sekarang);
            @endphp

            @if($canApprove)
                {{-- SETUJUI --}}
                <div class="card">
                    <h2 style="font-size:14px; font-weight:600; margin-bottom:12px; color:#15803d;">
                        ✅ Setujui File Revisi & Teruskan
                    </h2>
                    <form action="{{ route('admin.surat.setujui', $surat) }}" method="POST">
                        @csrf

                        @if($surat->tahap_sekarang === 5)
                            <div style="margin-bottom:10px;">
                                <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">
                                    Nomor Surat <span style="color:#b91c1c;">*</span>
                                </label>
                                <input type="text" name="nomor_surat" required
                                       placeholder="Contoh: 024/KU.01/IV/2025"
                                       style="width:100%; padding:7px 10px; border:1px solid #e5e7eb;
                                              border-radius:7px; font-size:13px; box-sizing:border-box;">
                            </div>
                        @endif

                        <div style="margin-bottom:10px;">
                            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">
                                Catatan (opsional)
                            </label>
                            <textarea name="catatan" rows="3" placeholder="Tambahkan catatan..."
                                      style="width:100%; padding:7px 10px; border:1px solid #e5e7eb;
                                             border-radius:7px; font-size:13px; resize:vertical; box-sizing:border-box;">
                            </textarea>
                        </div>

                        <div style="font-size:12px; color:#6b7280; margin-bottom:10px; padding:8px 10px;
                                    background:#f0fdf4; border-radius:6px; border:1px solid #bbf7d0;">
                            Tahap berikutnya:
                            <strong style="color:#15803d;">
                                {{ \App\Models\Surat::NAMA_TAHAP[$surat->tahap_sekarang + 1] ?? 'Selesai' }}
                            </strong>
                        </div>

                        <button type="submit" class="btn btn-success" style="width:100%;">
                            ✓ Setujui File Revisi & Teruskan
                        </button>
                    </form>
                </div>

                {{-- TOLAK --}}
                <div class="card">
                    <h2 style="font-size:14px; font-weight:600; margin-bottom:12px; color:#b91c1c;">
                        ✗ Tolak File Revisi
                    </h2>
                    <form action="{{ route('admin.surat.tolak', $surat) }}" method="POST">
                        @csrf
                        <div style="margin-bottom:10px;">
                            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">
                                Alasan Penolakan <span style="color:#b91c1c;">*</span>
                            </label>
                            <textarea name="catatan" rows="3" required
                                      placeholder="Tuliskan alasan penolakan file revisi..."
                                      style="width:100%; padding:7px 10px; border:1px solid #e5e7eb;
                                             border-radius:7px; font-size:13px; resize:vertical; box-sizing:border-box;">
                            </textarea>
                            @error('catatan')
                                <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger" style="width:100%;"
                                onclick="return confirm('Yakin ingin menolak file revisi ini? User bisa upload ulang.')">
                            ✗ Tolak File Revisi
                        </button>
                    </form>
                </div>
            @else
                <div class="card" style="text-align:center; padding:24px; background:#fef3c7; border:1px solid #f59e0b;">
                    <div style="font-size:32px; margin-bottom:8px;">⚠️</div>
                    <div style="font-size:14px; font-weight:600; color:#92400e;">Anda Tidak Berwenang</div>
                    <div style="font-size:12px; color:#a16207; margin-top:4px;">
                        Role Anda ({{ Auth::user()->getRoleLabel() }}) tidak sesuai untuk tahap ini.
                    </div>
                </div>
            @endif

        @else
            <div class="card" style="text-align:center; padding:24px;">
                <div style="font-size:32px; margin-bottom:8px;">❌</div>
                <div style="font-size:14px; font-weight:600; color:#b91c1c;">Surat Ditolak</div>
                <div style="font-size:12px; color:#6b7280; margin-top:4px;">Pengusul perlu merevisi dan mengajukan ulang</div>
            </div>
        @endif

        {{-- INFO TAHAP SEKARANG --}}
        <div class="card" style="background:#f8fafc;">
            <div style="font-size:11px; color:#6b7280; margin-bottom:6px; font-weight:600;">POSISI SEKARANG</div>
            <div style="font-size:22px; font-weight:700; color:#1e3a5f;">
                Tahap {{ $surat->tahap_sekarang }}/10
            </div>
            <div style="font-size:13px; color:#374151; margin-top:2px;">{{ $surat->nama_tahap }}</div>
        </div>

    </div>
</div>

{{-- ========== MODAL PREVIEW FILE ========== --}}
<div id="previewModal"
     style="display:none; position:fixed; inset:0; z-index:9999;
            background:rgba(10,15,30,.75); backdrop-filter:blur(6px);
            animation:fadeIn .2s ease;"
     onclick="handleOverlayClick(event)">

    <div id="previewModalBox"
         style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
                width:92vw; max-width:1100px; height:90vh;
                background:#fff; border-radius:16px; overflow:hidden;
                display:flex; flex-direction:column;
                box-shadow:0 32px 80px rgba(0,0,0,.45);">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:14px 20px; background:#1e3a5f; color:#fff; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:20px;">📄</span>
                <div>
                    <div id="previewTitle" style="font-size:14px; font-weight:600;">Preview Dokumen</div>
                    <div id="previewSubtitle" style="font-size:11px; color:#93c5fd; margin-top:1px;"></div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <a id="previewDownloadBtn"
                   href="#"
                   style="padding:7px 16px; background:rgba(255,255,255,.15);
                          border:1px solid rgba(255,255,255,.3); border-radius:8px;
                          color:#fff; font-size:12px; font-weight:500; text-decoration:none;
                          transition:background .2s;"
                   onmouseover="this.style.background='rgba(255,255,255,.25)'"
                   onmouseout="this.style.background='rgba(255,255,255,.15)'">
                    ⬇ Download
                </a>
                <button onclick="closePreview()"
                        style="background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3);
                               border-radius:8px; color:#fff; padding:7px 12px; cursor:pointer;
                               font-size:14px; font-weight:600; transition:background .2s;"
                        onmouseover="this.style.background='rgba(255,255,255,.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,.15)'">✕</button>
            </div>
        </div>

        {{-- Body --}}
        <div id="previewBody" style="flex:1; overflow:hidden; position:relative; background:#f1f5f9;">

            {{-- Loading indicator --}}
            <div id="previewLoader"
                 style="position:absolute; inset:0; display:flex; flex-direction:column;
                        align-items:center; justify-content:center; gap:16px; z-index:2;">
                <div style="width:48px; height:48px; border:4px solid #e2e8f0;
                            border-top-color:#1e3a5f; border-radius:50%;
                            animation:spin .8s linear infinite;"></div>
                <div style="font-size:13px; color:#64748b;">Memuat dokumen…</div>
            </div>

            {{-- Iframe untuk PDF --}}
            <iframe id="previewPdfFrame"
                    src="about:blank"
                    style="display:none; width:100%; height:100%; border:none;"></iframe>

            {{-- Container untuk Word HTML --}}
            <div id="previewWordHtml"
                 style="display:none; width:100%; height:100%; overflow:auto; padding:24px;
                        background:#fff; font-family:'Times New Roman',serif; font-size:14pt;
                        line-height:1.6; color:#000;">
            </div>

            {{-- Container untuk Image --}}
            <div id="previewImageContainer"
                 style="display:none; width:100%; height:100%; overflow:auto;
                        align-items:center; justify-content:center; background:#1e293b;">
                <img id="previewImage"
                     style="max-width:95%; max-height:95%; object-fit:contain;
                            border-radius:8px; box-shadow:0 8px 32px rgba(0,0,0,.3);">
            </div>

            {{-- Pesan tidak bisa preview --}}
            <div id="previewNoSupport"
                 style="display:none; position:absolute; inset:0; align-items:center;
                        justify-content:center; flex-direction:column; gap:16px; font-size:14px; color:#64748b;">
                <div style="font-size:48px;">📁</div>
                <div id="previewNoSupportMsg">Format file tidak dapat di-preview langsung.</div>
                <a id="previewFallbackDownload" href="#"
                   class="btn btn-primary">⬇ Download File</a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes spin   { to{transform:rotate(360deg)} }
</style>

<script>
let currentDownloadUrl = '';

function openPreview(tipe, previewUrl, title, ext) {
    const modal       = document.getElementById('previewModal');
    const loader      = document.getElementById('previewLoader');
    const pdfFr       = document.getElementById('previewPdfFrame');
    const wordHtml    = document.getElementById('previewWordHtml');
    const imgCont     = document.getElementById('previewImageContainer');
    const img         = document.getElementById('previewImage');
    const noSupp      = document.getElementById('previewNoSupport');
    const dlBtn       = document.getElementById('previewDownloadBtn');
    const fallDl      = document.getElementById('previewFallbackDownload');
    const ttl         = document.getElementById('previewTitle');
    const sub         = document.getElementById('previewSubtitle');
    const noSuppMsg   = document.getElementById('previewNoSupportMsg');

    // Reset semua
    pdfFr.style.display   = 'none';
    pdfFr.src             = 'about:blank';
    wordHtml.style.display = 'none';
    wordHtml.innerHTML    = '';
    imgCont.style.display = 'none';
    img.src               = '';
    noSupp.style.display  = 'none';
    loader.style.display  = 'flex';

    // Download URL
    const downloadUrl = previewUrl.replace('/preview/', '/download/');
    currentDownloadUrl = downloadUrl;
    dlBtn.href  = downloadUrl;
    fallDl.href = downloadUrl;

    // Title
    ttl.textContent = title;
    sub.textContent = '.' + ext.toUpperCase() + ' — klik ✕ untuk tutup';

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';

    // Get content URL dari previewUrl (ambil bagian /preview-content/)
    const contentUrl = previewUrl.replace('/preview/', '/preview-content/');

    // Fetch content via API
    fetch(contentUrl)
        .then(res => res.json())
        .then(data => {
            loader.style.display = 'none';

            if (data.type === 'html') {
                // Word: tampilkan HTML
                wordHtml.innerHTML = data.content;
                wordHtml.style.display = 'block';
            } else if (data.type === 'pdf') {
                // PDF: tampilkan di iframe
                pdfFr.src = data.url;
                pdfFr.style.display = 'block';
            } else if (data.type === 'image') {
                // Image: tampilkan gambar
                img.src = data.url;
                imgCont.style.display = 'flex';
            } else {
                // Error / tidak support
                noSuppMsg.textContent = data.error || 'Format file tidak dapat di-preview langsung.';
                noSupp.style.display = 'flex';
            }
        })
        .catch(err => {
            console.error('Preview error:', err);
            loader.style.display = 'none';
            noSuppMsg.textContent = 'Gagal memuat preview. Silakan download file.';
            noSupp.style.display = 'flex';
        });
}

function closePreview() {
    const modal       = document.getElementById('previewModal');
    const pdfFr       = document.getElementById('previewPdfFrame');
    const wordHtml    = document.getElementById('previewWordHtml');
    const imgCont     = document.getElementById('previewImageContainer');

    modal.style.display   = 'none';
    pdfFr.src             = 'about:blank';
    pdfFr.style.display   = 'none';
    wordHtml.innerHTML    = '';
    wordHtml.style.display = 'none';
    imgCont.style.display = 'none';
    document.body.style.overflow = '';
}

function handleOverlayClick(e) {
    if (e.target === document.getElementById('previewModal')) {
        closePreview();
    }
}

// Tutup dengan Esc
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePreview();
});
</script>

{{-- Include partial untuk delete requests --}}
@include('admin.surat._delete-requests')

{{-- SISTEM KOMENTAR / DISKUSI --}}
<x-komentar-section :surat="$surat" />

{{-- Pass user ID to JavaScript --}}
<script>
    window.currentUserId = {{ auth()->id() }};
    window.isAdmin = true;
</script>

@endsection