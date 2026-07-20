@extends('layouts.user')
@section('title', 'Detail Surat')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('user.surat.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;background:var(--bg-tertiary);color:var(--text-primary);border-color:var(--border-color);">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0" style="color:var(--text-primary);">Detail & Tracking Surat</h5>
        <small class="text-muted">Pantau progress pengajuan surat kamu</small>
    </div>
    {{-- Tombol Aksi --}}
    <div class="d-flex gap-2">
        @if($surat->created_at && $surat->created_at->diffInMinutes(now()) <= 15)
        <button type="button" 
                class="btn btn-sm btn-warning text-dark d-flex align-items-center gap-2" 
                style="border-radius:8px;font-size:13px;"
                data-bs-toggle="modal" 
                data-bs-target="#editModal">
            <i class="bi bi-pencil-square"></i> Edit Surat
        </button>
        @endif
        
        <button type="button" 
                class="btn btn-sm btn-danger d-flex align-items-center gap-2" 
                style="border-radius:8px;font-size:13px;"
                data-bs-toggle="modal" 
                data-bs-target="#deleteModal">
            <i class="bi bi-trash"></i> Hapus Surat
        </button>
    </div>
</div>

<div class="row g-3">

    {{-- KOLOM KIRI --}}
    <div class="col-12 col-lg-8">

        {{-- INFO SURAT --}}
        <div class="card card-custom mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-2" style="color:var(--text-primary);">{{ $surat->judul }}</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge rounded-pill" style="background:#ede9fe;color:#6d28d9;font-size:11px;">
                                {{ $surat->jenis_label }}
                            </span>
                            <span class="badge rounded-pill badge-{{ $surat->sifat }}" style="font-size:11px;">
                                {{ ucfirst($surat->sifat) }}
                            </span>
                            @if($surat->status === 'selesai')
                                <span class="badge rounded-pill" style="background:#dcfce7;color:#15803d;font-size:11px;">✓ Selesai</span>
                            @elseif($surat->status === 'ditolak')
                                <span class="badge rounded-pill" style="background:#fee2e2;color:#b91c1c;font-size:11px;">✗ Ditolak</span>
                            @elseif($surat->status === 'revisi')
                                <span class="badge rounded-pill" style="background:#fef3c7;color:#b45309;font-size:11px;">📝 Revisi</span>
                            @elseif($surat->status === 'revisi_admin')
                                <span class="badge rounded-pill" style="background:#f3e8ff;color:#6b21a8;font-size:11px;">⚙️ Admin Revisi</span>
                            @elseif($surat->status === 'draft')
                                <span class="badge rounded-pill" style="background:#f1f5f9;color:#64748b;font-size:11px;">📄 Draf</span>
                            @else
                                <span class="badge rounded-pill" style="background:#dbeafe;color:#1d4ed8;font-size:11px;">⏱ Diproses</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row g-3" style="font-size:13px;">
                    <div class="col-sm-6">
                        <div style="color:var(--text-secondary);font-size:11px;font-weight:600;margin-bottom:3px;">TUJUAN SURAT</div>
                        <div style="color:var(--text-primary);">{{ $surat->tujuan }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="color:var(--text-secondary);font-size:11px;font-weight:600;margin-bottom:3px;">TANGGAL PENGAJUAN</div>
                        <div style="color:var(--text-primary);">{{ $surat->created_at?->format('d F Y, H:i') ?? '-' }}</div>
                    </div>
                    @if($surat->nomor_surat)
                    <div class="col-sm-6">
                        <div style="color:var(--text-secondary);font-size:11px;font-weight:600;margin-bottom:3px;">NOMOR SURAT</div>
                        <div class="fw-semibold" style="color:#1e3a5f;">{{ $surat->nomor_surat }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="color:var(--text-secondary);font-size:11px;font-weight:600;margin-bottom:3px;">TANGGAL SURAT</div>
                        <div style="color:var(--text-primary);">{{ $surat->tanggal_surat?->format('d M Y') ?? '—' }}</div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        <div style="color:var(--text-secondary);font-size:11px;font-weight:600;margin-bottom:3px;">DEADLINE SLA</div>
                        <div style="color:{{ $surat->sla_status === 'terlambat' ? '#b91c1c' : 'var(--text-primary)' }};font-weight:500;">
                            {{ $surat->deadline_sla?->format('d M Y, H:i') ?? '—' }}
                            @if($surat->sla_status === 'terlambat')
                                <span class="badge bg-danger ms-1" style="font-size:10px;">Terlambat</span>
                            @endif
                        </div>
                    </div>
                    @if($surat->alasan_keterlambatan)
                    <div class="col-12 mt-2">
                        <div style="color:#b91c1c;font-size:11px;font-weight:700;margin-bottom:4px;letter-spacing:0.5px;">ALASAN KETERLAMBATAN ADMIN</div>
                        <div class="p-2 px-3 rounded-3 d-flex align-items-center gap-2" style="background:#fff1f2; border:1px solid #fecaca; color:#b91c1c; font-size:13px; font-weight:600;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>{{ $surat->alasan_keterlambatan }}</span>
                        </div>
                    </div>
                    @endif
                    @if($surat->catatan_pengusul)
                    <div class="col-12">
                        <div style="color:var(--text-secondary);font-size:11px;font-weight:600;margin-bottom:4px;letter-spacing:0.5px;">CATATAN PENGUSUL</div>
                        <div style="color:var(--text-primary);font-style:italic;line-height:1.5;">
                            "{{ $surat->catatan_pengusul }}"
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Progress overall --}}
                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:12px;font-weight:600;color:var(--text-primary);">Progress Keseluruhan</span>
                        <span style="font-size:12px;font-weight:700;color:#1e3a5f;">{{ $surat->proses_persen }}%</span>
                    </div>
                    <div class="progress" style="height:8px;border-radius:99px;background:var(--border-color);">
                        <div class="progress-bar" role="progressbar"
                             style="width:{{ $surat->proses_persen }}%;background:#1e3a5f;border-radius:99px;">
                        </div>
                    </div>
                    <div style="font-size:11px;color:var(--text-secondary);margin-top:4px;">
                        Tahap {{ $surat->tahap_sekarang }} dari 10 · {{ $surat->nama_tahap }}
                    </div>
                </div>

                {{-- Lampiran --}}
                <div class="mt-4 pt-3 border-top">
                    <div style="font-size:12px; font-weight:700; color:var(--text-secondary); margin-bottom:12px; letter-spacing:0.5px;">DOKUMEN LAMPIRAN</div>
                    
                    @if($surat->file_dihapus_pada)
                        <div class="alert alert-secondary d-flex align-items-center gap-2" style="font-size:12px; border-radius:10px; border:none; background:var(--bg-tertiary);">
                            <i class="bi bi-info-circle-fill text-secondary"></i>
                            <div>
                                <strong>File Fisik Telah Dihapus</strong><br>
                                File ini telah dibersihkan dari penyimpanan pada {{ $surat->file_dihapus_pada?->format('d M Y, H:i') ?? '-' }}. Tracking histori tetap tersedia.
                            </div>
                        </div>
                    @else
                        <div class="d-flex gap-2 flex-wrap">
                            @if($surat->file_word)
                                @php $extWord = strtolower(pathinfo($surat->file_word, PATHINFO_EXTENSION)); @endphp
                                <a href="{{ route('user.surat.preview', [$surat, 'word']) }}"
                                   class="btn btn-sm d-flex align-items-center gap-2"
                                   style="font-size:12px;border:1px solid var(--border-color);border-radius:8px;color:#1e3a5f;background:var(--bg-secondary);">
                                     @if($extWord === 'pdf')
                                         <i class="bi bi-file-earmark-pdf text-danger"></i> Preview / Unduh PDF Surat
                                     @else
                                         <i class="bi bi-file-earmark-word" style="color:#2563eb;"></i> Preview / Unduh Surat
                                     @endif
                                 </a>
                            @endif
                            @if($surat->file_lampiran)
                                @php $extLamp = strtolower(pathinfo($surat->file_lampiran, PATHINFO_EXTENSION)); @endphp
                                <a href="{{ route('user.surat.preview', [$surat, 'lampiran']) }}"
                                   class="btn btn-sm d-flex align-items-center gap-2"
                                   style="font-size:12px;border:1px solid var(--border-color);border-radius:8px;color:#1e3a5f;background:var(--bg-secondary);">
                                    @if(in_array($extLamp, ['doc', 'docx']))
                                        <i class="bi bi-file-earmark-word text-primary"></i>
                                    @else
                                        <i class="bi bi-paperclip"></i>
                                    @endif
                                    Preview / Unduh Lampiran
                                </a>
                            @endif

                            @if($surat->status === 'selesai' && ($surat->file_word || $surat->file_lampiran))
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2"
                                        style="font-size:12px; border-radius:8px;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#purgeFilesModal">
                                    <i class="bi bi-shield-lock"></i> Bersihkan File Fisik
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- UPLOAD ULANG FILE PERBAIKAN (jika ditolak) --}}
                @if($surat->status === 'ditolak')
                <div class="mt-4 pt-3 border-top">
                    <div class="alert alert-warning" style="font-size:13px;border-radius:8px;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size:16px;"></i>
                            <div class="flex-grow-1">
                                <strong>Surat Ditolak - Upload Ulang File Perbaikan</strong>
                                <p class="mb-0 mt-1" style="font-size:12px;">
                                    Anda bisa mengupload ulang file Word dan/atau lampiran yang sudah diperbaiki.
                                    @if($surat->revisi_count > 0)
                                        <br><span class="text-muted">Sudah {{ $surat->revisi_count }}x revisi.</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('user.surat.reupload', $surat) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i> / <i class="bi bi-file-earmark-word text-primary"></i> File Surat (.docx/.doc/.pdf) <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="file_word" class="form-control" accept=".docx,.doc,.pdf" required
                                       style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);">
                                <small class="text-muted">Upload file Word atau PDF yang sudah diperbaiki (max 5MB)</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                    <i class="bi bi-paperclip text-secondary"></i> File Lampiran (opsional)
                                </label>
                                <input type="file" name="file_lampiran" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                                       style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);">
                                <small class="text-muted">PDF/JPG/PNG/Word/Excel (max 20MB)</small>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" style="font-size:13px;border-radius:8px;">
                                    <i class="bi bi-upload"></i> Upload File Perbaikan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- RATING SURAT --}}
        @if($surat->status === 'selesai')
        <div class="card card-custom mb-3" style="border-radius: 16px; border: 1px solid var(--border-color); background: var(--bg-secondary);">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:var(--text-primary); display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-star-fill text-warning"></i> Penilaian Pelayanan Surat
                </h6>
                @if(is_null($surat->rating))
                    <p class="text-muted small mb-3">Surat Anda telah selesai diproses. Bagaimana penilaian Anda terhadap kecepatan dan kualitas pelayanan kami?</p>
                    <form action="{{ route('user.surat.rate', $surat) }}" method="POST">
                        @csrf
                        <div class="d-flex align-items-center gap-3">
                            <div class="star-rating d-flex flex-row-reverse justify-content-end gap-1">
                                <input type="radio" id="star5" name="rating" value="5" class="d-none" />
                                <label for="star5" class="bi bi-star-fill text-muted fs-4 cursor-pointer" style="transition: color 0.2s;" title="Sangat Baik"></label>
                                
                                <input type="radio" id="star4" name="rating" value="4" class="d-none" />
                                <label for="star4" class="bi bi-star-fill text-muted fs-4 cursor-pointer" style="transition: color 0.2s;" title="Baik"></label>
                                
                                <input type="radio" id="star3" name="rating" value="3" class="d-none" />
                                <label for="star3" class="bi bi-star-fill text-muted fs-4 cursor-pointer" style="transition: color 0.2s;" title="Cukup"></label>
                                
                                <input type="radio" id="star2" name="rating" value="2" class="d-none" />
                                <label for="star2" class="bi bi-star-fill text-muted fs-4 cursor-pointer" style="transition: color 0.2s;" title="Buruk"></label>
                                
                                <input type="radio" id="star1" name="rating" value="1" class="d-none" />
                                <label for="star1" class="bi bi-star-fill text-muted fs-4 cursor-pointer" style="transition: color 0.2s;" title="Sangat Buruk"></label>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary py-2 px-3" style="border-radius: 8px; font-weight: 600; font-size: 13px; background: #1e3a5f; border-color: #1e3a5f;">
                                Kirim Rating
                            </button>
                        </div>
                    </form>
                    <style>
                        .star-rating:hover label {
                            color: #cbd5e1 !important;
                        }
                        .star-rating label:hover,
                        .star-rating label:hover ~ label {
                            color: #ffc107 !important;
                        }
                        .star-rating input:checked ~ label {
                            color: #ffc107 !important;
                        }
                        .cursor-pointer {
                            cursor: pointer;
                        }
                    </style>
                @else
                    <p class="text-muted small mb-2">Terima kasih atas penilaian Anda terhadap pelayanan kami!</p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $surat->rating)
                                    <i class="bi bi-star-fill text-warning fs-5"></i>
                                @else
                                    <i class="bi bi-star text-muted fs-5"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="fw-bold text-muted ms-2" style="font-size: 14px;">({{ $surat->rating }} / 5 Bintang)</span>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- TRACKING TIMELINE --}}
        <div class="card card-custom mb-3 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fw-bold mb-0" style="color:var(--text-primary);">
                        <i class="bi bi-map me-2"></i>Tracking Progress Surat
                    </h6>
                    <span class="badge bg-light text-dark" style="font-size:10px; border:1px solid var(--border-color);">
                        Status: {{ $surat->nama_tahap }}
                    </span>
                </div>

                {{-- Container Horizontal Scroll --}}
                <div class="table-responsive pb-2" style="scrollbar-width: thin;">
                    <div class="d-flex align-items-start pt-3 px-2" style="min-width: 800px; margin-bottom: 20px;">
                        @foreach($surat->tahapans as $index => $tahapan)
                            <div class="flex-grow-1 position-relative" style="flex-basis: 0;">
                                {{-- Garis Penghubung --}}
                                @if(!$loop->last)
                                    @php
                                        $nextTahapan = $surat->tahapans[$index + 1];
                                        $lineColor = '#e5e7eb'; // default abu-abu
                                        if ($tahapan->status === 'selesai') {
                                            $lineColor = '#22c55e'; // hijau jika sudah lewat
                                        }
                                        if ($tahapan->status === 'ditolak' || $nextTahapan->status === 'ditolak') {
                                            $lineColor = '#fee2e2'; // merah muda jika ada penolakan
                                        }
                                    @endphp
                                    <div style="position: absolute; top: 18px; left: 50%; width: 100%; height: 3px; background: {{ $lineColor }}; z-index: 1;"></div>
                                @endif

                                {{-- Lingkaran Status --}}
                                <div class="d-flex flex-column align-items-center position-relative" style="z-index: 2;">
                                    <div class="step-circle {{ $tahapan->status }}" style="
                                        width:38px;height:38px;font-size:14px;
                                        display: flex; align-items: center; justify-content: center;
                                        border-radius: 50%;
                                        background: {{ $tahapan->status === 'selesai' ? '#22c55e' : ($tahapan->status === 'proses' ? '#1e3a5f' : ($tahapan->status === 'ditolak' ? '#ef4444' : '#f3f4f6')) }};
                                        color: {{ $tahapan->status === 'menunggu' ? '#9ca3af' : '#fff' }};
                                        transition: all 0.3s ease;
                                        border: 2px solid {{ $tahapan->status === 'menunggu' ? '#e5e7eb' : 'transparent' }};
                                        {{ $tahapan->status === 'proses' ? 'box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.2);' : '' }}
                                    ">
                                        @if($tahapan->status === 'selesai')
                                            <i class="bi bi-check-lg"></i>
                                        @elseif($tahapan->status === 'proses')
                                            <i class="bi bi-hourglass-split"></i>
                                        @elseif($tahapan->status === 'ditolak')
                                            <i class="bi bi-x-lg"></i>
                                        @else
                                            <span style="font-weight: 700;">{{ $tahapan->tahap }}</span>
                                        @endif
                                    </div>

                                    {{-- Label Tahapan --}}
                                    <div class="text-center mt-3 px-1">
                                        <div style="font-size:11px; font-weight:700; color:var(--text-primary); line-height:1.2; min-height: 28px; display: flex; align-items: center; justify-content: center;">
                                            {{ $tahapan->nama_tahap }}
                                        </div>
                                        <div style="font-size:9px; color:var(--text-secondary); margin-top:4px; font-weight: 500;">
                                            @if($tahapan->selesai_pada)
                                                {{ $tahapan->selesai_pada?->format('d/m/y') }}<br>{{ $tahapan->selesai_pada?->format('H:i') }}
                                            @elseif($tahapan->status === 'proses')
                                                <span class="text-primary">Sedang diproses</span>
                                            @else
                                                <span class="opacity-50">Menunggu</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- CATATAN ADMIN & REVIEW --}}
        @php
            $catatans = $surat->tahapans->whereNotNull('catatan')->sortByDesc('updated_at');
        @endphp

        @if($catatans->count() > 0)
        <div class="card card-custom mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4" style="color:var(--text-primary);">
                    <i class="bi bi-chat-left-dots me-2 text-primary"></i>Catatan & Feedback Admin
                </h6>
                
                <div class="d-flex flex-column gap-3">
                    @foreach($catatans as $tahapan)
                        <div class="p-3 rounded-4 border-0 shadow-sm" style="
                            background: {{ $tahapan->status === 'ditolak' ? '#fff1f2' : ($tahapan->status === 'selesai' ? '#f0fdf4' : '#f8fafc') }};
                            border-left: 4px solid {{ $tahapan->status === 'ditolak' ? '#ef4444' : ($tahapan->status === 'selesai' ? '#22c55e' : '#1e3a5f') }} !important;
                        ">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold" style="font-size:12px; color:{{ $tahapan->status === 'ditolak' ? '#b91c1c' : ($tahapan->status === 'selesai' ? '#15803d' : '#1e3a5f') }};">
                                        Tahap: {{ $tahapan->nama_tahap }}
                                    </span>
                                    @if($tahapan->diprosesByUser)
                                        <small class="text-muted" style="font-size:10px;">
                                            <i class="bi bi-person-check"></i> {{ $tahapan->diprosesByUser->getRoleLabel() }}
                                        </small>
                                    @endif
                                </div>
                                <span class="badge bg-white text-muted border" style="font-size:10px;">
                                    {{ $tahapan->updated_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <div class="mt-2" style="font-size:13px; color:var(--text-primary); line-height:1.6; font-style: italic;">
                                "{!! nl2br(e($tahapan->catatan)) !!}"
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif


    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-12 col-lg-4">

        {{-- QR CODE VERIFIKASI --}}
        <div class="card card-custom mb-3">
            <div class="card-body p-4 text-center">
                <h6 class="fw-bold mb-3" style="color:var(--text-primary);font-size:13px;text-transform:uppercase;letter-spacing:0.05em;">QR Code Verifikasi</h6>
                <div style="background:white; padding:10px; border-radius:12px; display:inline-block; border:1px solid var(--border-color); margin-bottom:12px;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('surat.verifikasi', $surat->uuid)) }}" 
                         alt="QR Code Verifikasi" style="display:block; width:120px; height:120px;">
                </div>
                <div style="font-size:11px; color:var(--text-secondary); line-height:1.4;">
                    Ini adalah QR Code unik untuk memverifikasi keaslian surat ini di portal publik.
                </div>
            </div>
        </div>

        {{-- STATUS CARD --}}
        @php
            $statusCardBg = match($surat->status) {
                'selesai'     => 'linear-gradient(135deg,#15803d,#22c55e)',
                'ditolak'     => 'linear-gradient(135deg,#b91c1c,#ef4444)',
                'revisi'      => 'linear-gradient(135deg,#f59e0b,#fbbf24)',
                'revisi_admin' => 'linear-gradient(135deg,#7c3aed,#a78bfa)',
                'draft'       => 'linear-gradient(135deg,#64748b,#94a3b8)',
                default       => 'linear-gradient(135deg,#1e3a5f,#2563eb)',
            };
            $statusIcon = match($surat->status) {
                'selesai'     => '✅',
                'ditolak'     => '❌',
                'revisi'      => '📝',
                'revisi_admin' => '⚙️',
                'draft'       => '📄',
                default       => '⏳',
            };
            $statusTitle = match($surat->status) {
                'selesai'     => 'Surat Selesai',
                'ditolak'     => 'Surat Ditolak',
                'revisi'      => 'File Perbaikan Menunggu Review',
                'revisi_admin' => 'Sedang Direvisi Admin',
                'draft'       => 'Draf Surat',
                default       => 'Tahap ' . $surat->tahap_sekarang . '/10',
            };
            $statusSubtitle = match($surat->status) {
                'proses'      => $surat->nama_tahap,
                'selesai'     => 'Semua tahapan selesai',
                'revisi'      => 'Menunggu admin approve file baru',
                'revisi_admin' => 'Admin Aspirasi sedang meninjau ulang',
                'draft'       => 'Belum diajukan ke admin',
                default       => 'Perlu perbaikan',
            };
        @endphp

        <div class="card card-custom mb-3" style="
            background:{{ $statusCardBg }};
            color:#fff;">
            <div class="card-body p-4 text-center">
                <div style="font-size:42px;margin-bottom:8px;">
                    {{ $statusIcon }}
                </div>
                <div style="font-size:16px;font-weight:700;">
                    {{ $statusTitle }}
                </div>
                <div style="font-size:12px;opacity:0.8;margin-top:4px;">
                    {{ $statusSubtitle }}
                    @if($surat->revisi_count > 0)
                        <br>Revisi ke-{{ $surat->revisi_count }}
                    @endif
                </div>
            </div>
        </div>

        {{-- RINGKASAN TAHAPAN --}}
        <div class="card card-custom mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:var(--text-primary);font-size:13px;">📋 Ringkasan Tahapan</h6>
                @foreach($surat->tahapans as $tahapan)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div style="
                            width:20px;height:20px;border-radius:50%;flex-shrink:0;
                            display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;
                            background:{{ $tahapan->status === 'selesai' ? '#dcfce7' : ($tahapan->status === 'proses' ? '#dbeafe' : ($tahapan->status === 'ditolak' ? '#fee2e2' : '#f3f4f6')) }};
                            color:{{ $tahapan->status === 'selesai' ? '#15803d' : ($tahapan->status === 'proses' ? '#1d4ed8' : ($tahapan->status === 'ditolak' ? '#b91c1c' : '#9ca3af')) }};
                        ">
                            @if($tahapan->status === 'selesai') ✓
                            @elseif($tahapan->status === 'proses') →
                            @elseif($tahapan->status === 'ditolak') ✗
                            @else {{ $tahapan->tahap }}
                            @endif
                        </div>
                        <div style="font-size:11px;
                            color:{{ $tahapan->status === 'menunggu' ? 'var(--text-secondary)' : 'var(--text-primary)' }};
                            font-weight:{{ $tahapan->status === 'proses' ? '600' : '400' }};">
                            {{ $tahapan->nama_tahap }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SLA INFO --}}
        @if(in_array($surat->status, ['proses', 'revisi', 'revisi_admin']))
        <div class="card card-custom" style="
            background:{{ $surat->sla_status==='terlambat' ? '#fef2f2' : '#eff6ff' }};
            border:1px solid {{ $surat->sla_status === 'terlambat' ? '#fca5a5' : '#bfdbfe' }} !important;">
            <div class="card-body px-4 py-3">
                <div style="font-size:12px;font-weight:600;
                    color:{{ $surat->sla_status==='terlambat' ? '#b91c1c' : '#1d4ed8' }};margin-bottom:4px;">
                    @if($surat->sla_status==='terlambat')
                        ⚠ SLA Terlampaui!
                    @else
                        ⏱ SLA 1 Hari Kerja
                    @endif
                </div>
                <div style="font-size:12px;color:var(--text-primary);">
                    Deadline: <strong>{{ $surat->deadline_sla?->Format('d M Y, H:i') ?? '—' }}</strong>
                </div>
                @if($surat->sla_status !== 'terlambat' && $surat->deadline_sla)
                    <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;">
                        Sisa: <strong>{{ $surat->sisa_jam }}</strong>
                    </div>
                @endif
                @if($surat->alasan_keterlambatan)
                    <div style="font-size:11px;color:#b91c1c;margin-top:8px;padding-top:8px;border-top:1px solid #fca5a5;">
                        <i class="bi bi-info-circle-fill me-1"></i>Alasan: <strong>{{ $surat->alasan_keterlambatan }}</strong>
                    </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

@push('modals')
{{-- Modal Hapus Surat --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('user.surat.requestDelete', $surat) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header" style="background:#fee2e2;border-bottom:1px solid #fca5a5;">
                    <h5 class="modal-title" id="deleteModalLabel" style="color:#b91c1c;">
                        <i class="bi bi-trash"></i> Hapus Surat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;color:var(--text-primary);">
                        Apakah Anda yakin ingin menghapus surat:
                    </p>
                    <div class="alert alert-light" style="border-left:4px solid #1e3a5f;font-size:13px;background:var(--bg-tertiary);color:var(--text-primary);border-color:var(--border-color);">
                    <strong>{{ $surat->judul }}</strong><br>
                    <span class="text-muted">{{ $surat->jenis_label }} · {{ $surat->created_at?->format('d M Y') ?? '-' }}</span>
                    </div>
                    @php
                        $bisaLangsungHapus = in_array($surat->status, ['draft', 'ditolak', 'selesai'])
                            || ($surat->status === 'proses' && $surat->tahap_sekarang <= 2);
                        $existingRequest = \App\Models\SuratDeleteRequest::where('surat_id', $surat->id)->where('status', 'pending')->first();
                    @endphp

                    @if($bisaLangsungHapus)
                        <div class="alert alert-warning" style="font-size:13px;">
                            <i class="bi bi-exclamation-triangle"></i>
                            @if($surat->status === 'proses' && $surat->tahap_sekarang <= 2)
                                Surat masih di tahap awal (Verifikasi Arsiparis). Akan <strong>langsung dihapus</strong> tanpa perlu persetujuan admin.
                            @else
                                Surat ini akan <strong>langsung dihapus</strong> tanpa perlu persetujuan admin.
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                Alasan Penghapusan <span class="text-muted">(Opsional)</span>
                            </label>
                            <textarea name="alasan" class="form-control" rows="2" 
                                      placeholder="Jelaskan alasan penghapusan surat..." 
                                      style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);"></textarea>
                        </div>
                    @elseif($existingRequest)
                        <div class="alert alert-info" style="font-size:13px;">
                            <i class="bi bi-clock-history"></i> 
                            Permintaan hapus sedang menunggu persetujuan admin.
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                Alasan Penghapusan <span class="text-danger">*</span>
                            </label>
                            <textarea name="alasan" class="form-control" rows="3" 
                                      placeholder="Jelaskan alasan penghapusan surat..." 
                                      required 
                                      style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);"></textarea>
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
@endpush


@if($surat->status === 'selesai' && !$surat->file_dihapus_pada)
@push('modals')
{{-- Modal Purge Files --}}
<div class="modal fade" id="purgeFilesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Bersihkan File Fisik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px; height:64px;">
                        <i class="bi bi-shield-lock-fill text-danger" style="font-size:32px;"></i>
                    </div>
                    <h6 class="fw-bold">Hapus File Saja?</h6>
                    <p class="text-muted small">Tindakan ini akan menghapus file Word dan Lampiran dari server secara permanen untuk menjaga privasi/storage.</p>
                </div>
                
                <div class="alert alert-info border-0 mb-0" style="font-size:12px; border-radius:12px;">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Jangan Khawatir:</strong> Seluruh riwayat pemrosesan (tracking), catatan admin, dan status surat tetap akan tersimpan di dashboard ini. Hanya file dokumennya saja yang tidak akan bisa didownload lagi.
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('user.surat.file_index') }}" style="font-size:11px; color:#3b82f6; text-decoration:none;">
                        <i class="bi bi-gear me-1"></i> Lihat semua file yang bisa dibersihkan
                    </a>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 p-4">
                <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal" style="border-radius:10px; font-size:13px; font-weight:600;">Batal</button>
                <form action="{{ route('user.surat.purgeFiles', $surat) }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 py-2" style="border-radius:10px; font-size:13px; font-weight:600;">Ya, Bersihkan File</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush
@endif

{{-- Modal Edit Surat --}}
@if($surat->created_at && $surat->created_at->diffInMinutes(now()) <= 15)
@push('modals')
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('user.surat.updateMetadata', $surat) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="editModalLabel">
                        <i class="bi bi-pencil-square text-warning"></i> Edit Detail Surat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning" style="font-size:12px; border-radius:10px;">
                        <i class="bi bi-info-circle-fill"></i> Anda hanya di perbolehkan mengedit surat ini selama 15 menit setelah diajukan. Waktu tersisa: <strong>{{ floor(15 - $surat->created_at->diffInMinutes(now())) }} Menit</strong>.
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">Judul Surat</label>
                        <input type="text" name="judul" class="form-control" value="{{ $surat->judul }}" required style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">Tujuan Surat</label>
                        <input type="text" name="tujuan" class="form-control" value="{{ $surat->tujuan }}" required style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">Jenis Surat</label>
                            <select name="jenis" class="form-select" required style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);">
                                <option value="nota_dinas" {{ $surat->jenis === 'nota_dinas' ? 'selected' : '' }}>Nota Dinas</option>
                                <option value="surat_dinas" {{ $surat->jenis === 'surat_dinas' ? 'selected' : '' }}>Surat Dinas</option>
                                <option value="surat_keputusan" {{ $surat->jenis === 'surat_keputusan' ? 'selected' : '' }}>Surat Keputusan</option>
                                <option value="surat_pernyataan" {{ $surat->jenis === 'surat_pernyataan' ? 'selected' : '' }}>Surat Pernyataan</option>
                                <option value="surat_keterangan" {{ $surat->jenis === 'surat_keterangan' ? 'selected' : '' }}>Surat Keterangan</option>
                                <option value="surat_undangan" {{ $surat->jenis === 'surat_undangan' ? 'selected' : '' }}>Surat Undangan</option>
                                <option value="surat_lainnya" {{ $surat->jenis === 'surat_lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">Sifat Surat</label>
                            <select name="sifat" class="form-select" required style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);">
                                <option value="biasa" {{ $surat->sifat === 'biasa' ? 'selected' : '' }}>Biasa</option>
                                <option value="segera" {{ $surat->sifat === 'segera' ? 'selected' : '' }}>Segera</option>
                                <option value="rahasia" {{ $surat->sifat === 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600;color:var(--text-primary);">Catatan (Opsional)</label>
                        <textarea name="catatan_pengusul" rows="3" maxlength="1000" class="form-control" style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);" placeholder="Tambahkan catatan untuk admin jika diperlukan (maks 1000 karakter)">{{ $surat->catatan_pengusul }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;font-size:13px;">Batal</button>
                    <button type="submit" class="btn btn-warning" style="border-radius:8px;font-size:13px;font-weight:600;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endif

@endsection