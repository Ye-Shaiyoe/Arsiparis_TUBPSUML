@extends('layouts.admin')

@section('title', 'System Logs')

@section('content')
<div class="card">
    <div class="section-header">
        <div>
            <h2>📋 Log Sistem</h2>
            <small style="color:var(--text-secondary);">Riwayat aktivitas dan kesalahan sistem (Ramah Pengguna)</small>
        </div>
        <div style="display:flex; gap:10px;">
            @if($currentFile)
                <a href="{{ route('admin.logs.download', $currentFile) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-download"></i> Unduh File
                </a>
                <form action="{{ route('admin.logs.clear', $currentFile) }}" method="POST" style="display:inline;" onsubmit="return confirm('Kosongkan riwayat ini?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-amber">
                        <i class="bi bi-eraser"></i> Bersihkan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:20px;">
        {{-- Daftar File --}}
        <div style="flex:1; min-width:200px;">
            <div style="font-weight:600; margin-bottom:10px; font-size:14px; color:var(--text-primary);">Pilih Tanggal Log</div>
            <div style="display:flex; flex-direction:column; gap:5px;">
                @forelse($files as $file)
                    <a href="{{ route('admin.logs.index', ['file' => $file]) }}" 
                       class="log-file-item {{ $currentFile === $file ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        {{ $file }}
                    </a>
                @empty
                    <div style="padding:10px; color:var(--text-secondary); font-size:13px; text-align:center;">
                        Tidak ada log
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Konten Log --}}
        <div style="flex:4; min-width:400px;">
            <div class="table-wrap">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background:var(--bg-secondary); text-align:left;">
                            <th style="padding:12px; font-size:13px; width:180px;">Waktu Kejadian</th>
                            <th style="padding:12px; font-size:13px; width:100px;">Status</th>
                            <th style="padding:12px; font-size:13px;">Pesan Aktivitas / Kesalahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr style="border-bottom:1px solid var(--border-color);">
                                <td style="padding:12px; font-size:12px; color:var(--text-secondary); vertical-align:top;">
                                    {{ \Carbon\Carbon::parse($log['timestamp'])->translatedFormat('d M Y, H:i:s') }}
                                </td>
                                <td style="padding:12px; vertical-align:top;">
                                    <span class="badge badge-{{ $log['level_class'] }}" style="text-transform:uppercase; font-size:10px;">
                                        {{ $log['level'] }}
                                    </span>
                                </td>
                                <td style="padding:12px; vertical-align:top;">
                                    <div style="font-size:13px; color:var(--text-primary); font-weight:500;">
                                        {{ $log['message'] }}
                                    </div>
                                    @if($log['context'])
                                        <details style="margin-top:5px;">
                                            <summary style="font-size:11px; color:#3b82f6; cursor:pointer;">Detail Teknis</summary>
                                            <pre style="background:#f8f9fa; padding:8px; border-radius:4px; font-size:11px; margin-top:5px; white-space:pre-wrap; color:#666; border:1px solid #ddd;">{{ $log['context'] }}</pre>
                                        </details>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding:40px; text-align:center; color:var(--text-secondary);">
                                    Tidak ada aktivitas yang tercatat pada file ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .log-file-item {
        padding:10px 15px; 
        border-radius:8px; 
        text-decoration:none; 
        font-size:13px; 
        display:flex; 
        align-items:center; 
        gap:10px;
        color: var(--text-primary);
        background: var(--bg-secondary);
        transition: all 0.2s;
    }
    .log-file-item.active {
        background-color: var(--primary-color) !important;
        color: white !important;
    }
    .log-file-item:hover:not(.active) {
        background-color: #e2e8f0;
    }
    .btn-amber {
        background-color: #f59e0b;
        color: white;
    }
    .btn-amber:hover {
        background-color: #d97706;
    }
</style>
@endsection
