@extends('layouts.user')

@section('content')

<style>
    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .doc-preview-card {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #eef2f6;
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .doc-preview-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: #dbeafe;
    }

    .doc-preview-top {
        height: 160px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .doc-preview-top img {
        width: 90%;
        height: 90%;
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
        background: #0f172a;
        color: white;
        flex-grow: 1;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .doc-icon-box {
        width: 38px;
        height: 38px;
        background: #ef4444;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .doc-icon-box.docx {
        background: #2563eb;
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
        background: #0f172a;
        border-top: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #94a3b8;
    }

    .doc-preview-card:hover .doc-info-section,
    .doc-preview-card:hover .doc-footer {
        background: #1e293b;
    }
</style>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" style="border-radius:8px;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0" style="color:#1e3a5f;">📄 Template Surat</h5>
        <small class="text-muted">Unduh template untuk mengajukan surat</small>
    </div>
</div>

<div class="template-grid">
    @forelse($templates as $tpl)
        <div class="doc-preview-card" onclick="window.open('{{ $tpl['url'] }}', '_blank')">
            <div class="doc-preview-top">
                <img src="{{ asset('images/template_previewss.png') }}" alt="Preview">
                <div style="position:absolute; top:10px; right:10px;">
                    <span class="badge bg-white text-dark shadow-sm" style="font-size:9px; border-radius:6px; opacity:0.9;">
                        {{ strtoupper($tpl['ext']) }}
                    </span>
                </div>
            </div>
            <div class="doc-info-section">
                <div class="doc-icon-box {{ $tpl['ext'] }}">
                    @if($tpl['ext'] == 'pdf')
                        <i class="bi bi-file-pdf-fill text-white" style="font-size:20px;"></i>
                    @else
                        <i class="bi bi-file-earmark-word-fill text-white" style="font-size:20px;"></i>
                    @endif
                </div>
                <div class="doc-name">{{ $tpl['nama'] }}</div>
            </div>
            <div class="doc-footer">
                <span>{{ strtoupper($tpl['ext']) }} &bull; {{ $tpl['size'] }}</span>
                <div class="d-flex align-items-center gap-1">
                    <i class="bi bi-download"></i>
                    <span>Unduh</span>
                </div>
            </div>
        </div>
    @empty
        <div class="card card-custom w-100">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-0" style="font-size:14px;">Belum ada template yang diunggah admin.</p>
            </div>
        </div>
    @endforelse
</div>

@endsection
