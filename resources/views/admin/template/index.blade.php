@extends('layouts.admin')
@section('title', 'Kelola Template Surat')

@section('content')

<style>
    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .doc-preview-card {
        background: var(--bg-secondary);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border-color);
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .doc-preview-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: #3b82f6;
    }

    .doc-preview-top {
        height: 160px;
        background: var(--bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .doc-preview-top img {
        width: 85%;
        height: 85%;
        object-fit: cover;
        object-position: top;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.5s ease;
    }

    .doc-preview-card:hover .doc-preview-top img {
        transform: scale(1.05);
    }

    .doc-info-section {
        padding: 15px;
        background: #1e3a5f;
        color: white;
        flex-grow: 1;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background 0.3s ease;
    }

    html.dark-mode .doc-info-section {
        background: #1f2937;
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    .doc-icon-box {
        width: 38px;
        height: 38px;
        background: #2563eb;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
    }

    .doc-name {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        color: #f8fafc;
    }

    .doc-footer {
        padding: 10px 15px;
        background: #1e3a5f;
        border-top: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #94a3b8;
        transition: background 0.3s ease;
    }

    html.dark-mode .doc-footer {
        background: #1f2937;
    }

    .doc-preview-card:hover .doc-info-section,
    .doc-preview-card:hover .doc-footer {
        background: #1e293b;
    }

    html.dark-mode .doc-preview-card:hover .doc-info-section,
    html.dark-mode .doc-preview-card:hover .doc-footer {
        background: #374151;
    }

    /* Action Buttons Overlay */
    .doc-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        gap: 6px;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 10;
    }

    .doc-preview-card:hover .doc-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-secondary);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: scale(1.1);
        background: var(--bg-tertiary);
    }

    .action-btn.btn-delete {
        color: #ef4444;
    }

    .action-btn.btn-download {
        color: #2563eb;
    }

    /* Form Overrides */
    .form-control-custom {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 10px;
        font-size: 13px;
        width: 100%;
        transition: all 0.3s;
    }

    .form-control-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
</style>

<div class="row g-4">
    {{-- DAFTAR TEMPLATE --}}
    <div class="col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:var(--text-primary);">📄 Template Surat</h5>
                        <p class="mb-0 text-muted small">Kelola berkas .docx contoh untuk pegawai</p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" style="border-radius: 8px;">
                        Total: {{ $files->count() }} File
                    </span>
                </div>


                @if($files->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3" style="font-size: 40px;">📭</div>
                        <h6 class="text-muted">Belum ada template yang diunggah.</h6>
                        <p class="text-muted small">Silakan gunakan form di sebelah kanan untuk menambah template baru.</p>
                    </div>
                @else
                    <div class="template-grid">
                        @foreach($files as $file)
                            <div class="doc-preview-card">
                                {{-- Hover Actions --}}
                                <div class="doc-actions">
                                    <a href="{{ $file['url'] }}" class="action-btn btn-download" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form action="{{ route('admin.template.destroy') }}" method="POST"
                                          onsubmit="return confirm('Hapus template {{ $file['nama'] }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="path" value="{{ $file['path'] }}">
                                        <button type="submit" class="action-btn btn-delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="doc-preview-top">
                                    <img src="{{ asset('images/template_previewss.png') }}" alt="Preview">
                                    <div style="position:absolute; top:10px; left:10px;">
                                        <span class="badge bg-white text-dark shadow-sm" style="font-size:9px; border-radius:6px; opacity:0.9;">
                                            {{ strtoupper($file['ext'] ?? 'DOCX') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="doc-info-section">
                                    <div class="doc-icon-box {{ $file['ext'] ?? 'docx' }}" style="{{ ($file['ext'] ?? '') === 'pdf' ? 'background: #ef4444; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);' : '' }}">
                                        @if(($file['ext'] ?? '') === 'pdf')
                                            <i class="bi bi-file-pdf-fill text-white" style="font-size:20px;"></i>
                                        @else
                                            <i class="bi bi-file-earmark-word-fill text-white" style="font-size:20px;"></i>
                                        @endif
                                    </div>
                                    <div class="doc-name">{{ $file['nama'] }}</div>
                                </div>
                                <div class="doc-footer">
                                    <span>{{ $file['ukuran'] }}</span>
                                    <span>{{ $file['diupload'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-custom sticky-top" style="top: 80px; z-index: 5;">

            <div class="card-body p-4">
                <h6 class="fw-bold mb-4" style="color:var(--text-primary);">
                    <i class="bi bi-cloud-arrow-up-fill me-2 text-primary"></i> Upload Template Baru
                </h6>
                
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius:12px; font-size:13px;">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.template.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Nama Template <span class="text-danger">*</span></label>
                        <input type="text" name="nama_file" required
                                placeholder="Contoh: Format Surat Tugas"
                                value="{{ old('nama_file') }}"
                                class="form-control-custom">
                        @error('nama_file')
                            <div class="text-danger small mt-1" style="font-size:11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-muted">File Template (.docx, .doc, .pdf) <span class="text-danger">*</span></label>
                        <div class="p-3 border-dashed rounded-3 text-center" style="border: 2px dashed var(--border-color); background: var(--bg-tertiary);">
                            <input type="file" name="file_template" required accept=".docx,.doc,.pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control form-control-sm border-0 bg-transparent" style="color: var(--text-primary);">
                            <div class="mt-2 text-muted" style="font-size:11px;">Format: Word (.docx, .doc) atau PDF (.pdf) &bull; Maks. 10MB</div>
                        </div>
                        @error('file_template')
                            <div class="text-danger small mt-1" style="font-size:11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius:10px;">
                        ⬆ Unggah Template
                    </button>
                </form>
                
                <div class="mt-4 p-3 rounded-3" style="background: var(--bg-primary); border: 1px solid var(--border-color);">
                    <h6 class="fw-bold text-primary mb-2" style="font-size:12px;">Info Penting:</h6>
                    <ul class="mb-0 text-muted ps-3" style="font-size:11px; line-height:1.6;">
                        <li>Mendukung format <strong>.docx</strong>, <strong>.doc</strong>, dan <strong>.pdf</strong>.</li>
                        <li>Pastikan ukuran file tidak melebihi 10MB.</li>
                        <li>Template yang diunggah akan langsung tersedia bagi seluruh pegawai.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection