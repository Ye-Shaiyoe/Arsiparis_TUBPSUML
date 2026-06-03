@extends('layouts.user')
@section('title', 'Direktori Pegawai')

@section('content')
<style>
    .pegawai-search-wrap {
        background: white;
        border-radius: 20px;
        padding: 36px;
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.06);
        border: 1px solid rgba(241, 245, 249, 0.9);
        margin-bottom: 28px;
    }
    .pegawai-search-input {
        height: 52px;
        border-radius: 14px !important;
        border: 1.5px solid #e2e8f0;
        font-size: 14px;
        padding: 0 18px;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f8fafc;
    }
    .pegawai-search-input:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        background: white;
    }
    .pegawai-search-btn {
        height: 52px;
        padding: 0 28px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        background: linear-gradient(135deg, #1e3a8a 0%, #4361ee 100%);
        border: none;
        color: white;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 6px 18px rgba(67, 97, 238, 0.3);
    }
    .pegawai-search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(67, 97, 238, 0.4);
        color: white;
    }
    .pegawai-reset-btn {
        height: 52px;
        padding: 0 20px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 14px;
        background: #f1f5f9;
        border: none;
        color: #64748b;
        transition: all 0.2s;
    }
    .pegawai-reset-btn:hover { background: #e2e8f0; color: #1e293b; }

    .pegawai-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        border: 1px solid rgba(241, 245, 249, 0.9);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .pegawai-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    }
    .pegawai-cover {
        height: 76px;
        background: linear-gradient(135deg, #1e3a8a 0%, #4361ee 60%, #0ea5e9 100%);
        position: relative;
    }
    .pegawai-avatar {
        position: absolute;
        bottom: -36px;
        left: 50%;
        transform: translateX(-50%);
        width: 72px;
        height: 72px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        overflow: hidden;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 800;
        color: #4361ee;
        flex-shrink: 0;
    }
    .pegawai-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .pegawai-body { padding: 48px 20px 20px; text-align: center; }
    .pegawai-name {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 4px;
    }
    .pegawai-nip {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .pegawai-role-badge {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 99px;
        background: rgba(67, 97, 238, 0.08);
        color: #4361ee;
        border: 1px solid rgba(67, 97, 238, 0.15);
        margin-bottom: 16px;
    }
    .pegawai-action {
        display: block;
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        color: #4361ee;
        text-decoration: none;
        transition: all 0.2s;
    }
    .pegawai-action:hover {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    }
    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(67,97,238,0.08) 0%, rgba(14,165,233,0.08) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 36px;
    }
</style>

<div class="container-fluid px-0">

    {{-- Header --}}
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); border-radius: 20px; padding: 32px 36px; color: white; margin-bottom: 28px; box-shadow: 0 15px 40px rgba(15, 23, 42, 0.2);">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1">Direktori Pegawai</h4>
                <p class="mb-0" style="opacity: 0.75; font-size: 13px;">Cari rekan kerja berdasarkan Nama atau NIP</p>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="pegawai-search-wrap">
        <form action="{{ route('user.pegawai.index') }}" method="GET" data-turbo="false">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div style="flex: 1; min-width: 200px; position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 16px; z-index: 2;"></i>
                    <input type="text" name="search"
                           class="form-control pegawai-search-input"
                           style="padding-left: 44px;"
                           placeholder="Masukkan Nama atau NIP pegawai..."
                           value="{{ request('search') }}"
                           autocomplete="off">
                </div>
                <button type="submit" class="pegawai-search-btn">
                    <i class="bi bi-search me-2"></i> Cari Pegawai
                </button>
                @if(request('search'))
                    <a href="{{ route('user.pegawai.index') }}" class="pegawai-reset-btn text-decoration-none d-flex align-items-center">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
                @endif
            </div>
            @if(request('search'))
                @if(strlen(trim(request('search'))) < 2)
                    <p class="mb-0 mt-3 text-danger" style="font-size: 13px;">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Kata kunci pencarian minimal 2 karakter.
                    </p>
                @else
                    <p class="mb-0 mt-3" style="font-size: 13px; color: #64748b;">
                        Menampilkan hasil untuk: <strong>"{{ request('search') }}"</strong>
                        @if($users->total() > 0)
                            — ditemukan <strong>{{ $users->total() }}</strong> pegawai
                        @endif
                    </p>
                @endif
            @else
                <p class="mb-0 mt-3" style="font-size: 13px; color: #94a3b8;">
                    <i class="bi bi-info-circle me-1"></i>
                    Gunakan kata kunci minimal 2 karakter untuk mulai mencari.
                </p>
            @endif
        </form>
    </div>

    {{-- Results --}}
    @if($users->isNotEmpty())
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 20px;">
            @foreach($users as $pegawai)
                <div class="pegawai-card">
                    <div class="pegawai-cover">
                        <div class="pegawai-avatar">
                            @if($pegawai->profile_photo)
                                <img src="{{ Storage::url($pegawai->profile_photo) }}" alt="">
                            @else
                                {{ strtoupper(substr($pegawai->name, 0, 2)) }}
                            @endif
                        </div>
                    </div>
                    <div class="pegawai-body">
                        <div class="pegawai-name" title="{{ $pegawai->name }}">{{ $pegawai->name }}</div>
                        <div class="pegawai-nip">NIP: {{ $pegawai->nip ? (substr($pegawai->nip, 0, 3) . str_repeat('*', max(0, strlen($pegawai->nip) - 3))) : '—' }}</div>
                        <div class="pegawai-role-badge">{{ $pegawai->getRoleLabel() }}</div>
                        @if($pegawai->uuid)
                            <a href="{{ route('user.pegawai.show', $pegawai->uuid) }}" class="pegawai-action">
                                <i class="bi bi-person-lines-fill me-1"></i> Lihat Detail
                            </a>
                        @else
                            <button class="pegawai-action" disabled style="opacity: 0.5; cursor: not-allowed;">
                                <i class="bi bi-person-lines-fill me-1"></i> Tidak Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    @else
        <div class="empty-state">
            @if(request()->filled('search') && strlen(trim(request('search'))) >= 2)
                <div class="empty-state-icon">
                    <i class="bi bi-person-slash text-muted"></i>
                </div>
                <h5 class="fw-bold mb-2">Pegawai Tidak Ditemukan</h5>
                <p class="text-muted mb-0">Tidak ada pegawai dengan nama atau NIP "<strong>{{ request('search') }}</strong>".<br>Coba kata kunci lain.</p>
            @else
                <div class="empty-state-icon" style="background: linear-gradient(135deg, rgba(67,97,238,0.1) 0%, rgba(14,165,233,0.1) 100%); font-size: 40px;">
                    🔍
                </div>
                <h5 class="fw-bold mb-2">Cari Pegawai</h5>
                <p class="text-muted mb-0">Ketik nama atau NIP rekan kamu di kolom pencarian di atas untuk mulai mencari.</p>
            @endif
        </div>
    @endif

</div>
@endsection
