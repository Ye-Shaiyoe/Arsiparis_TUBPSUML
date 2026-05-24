@extends('layouts.user')
@section('title', 'Detail Activity')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('user.activity-log.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;background:var(--bg-tertiary);color:var(--text-primary);border-color:var(--border-color);">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="flex-grow-1">
        <h5 class="fw-bold mb-0" style="color:var(--text-primary);">Detail Aktivitas</h5>
        <small class="text-muted">Informasi lengkap aksi Anda</small>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-8">
        {{-- Info Utama --}}
        <div class="card card-custom mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="rounded-3 p-3" style="background:{{ $log->action_color }}20;">
                        <i class="bi {{ $log->action_icon }}" style="font-size:32px;color:{{ $log->action_color }};"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" style="color:var(--text-primary);">
                            {{ ucfirst($log->action) }}
                        </h5>
                        <p class="text-muted mb-0" style="font-size:13px;">
                            {{ $log->description ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                </div>

                <hr style="border-color:var(--border-color);">

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div style="font-size:11px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">WAKTU AKTIVITAS</div>
                        <div style="color:var(--text-primary);font-weight:500;">
                            {{ $log->created_at->format('d F Y, H:i:s') }}
                        </div>
                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                    </div>

                    <div class="col-sm-6">
                        <div style="font-size:11px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">JENIS AKSI</div>
                        <div>
                            <span class="badge rounded-pill" style="background:{{ $log->action_color }};color:white;font-size:11px;">
                                {{ ucfirst($log->action) }}
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div style="font-size:11px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">MODEL</div>
                        <div style="color:var(--text-primary);">
                            {{ $log->model_type ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div style="font-size:11px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">MODEL ID</div>
                        <div style="color:var(--text-primary);">
                            {{ $log->model_id ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div style="font-size:11px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">IP ADDRESS</div>
                        <div style="color:var(--text-primary);font-family:monospace;font-size:12px;">
                            {{ $log->ip_address ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div style="font-size:11px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">PERANGKAT</div>
                        <div style="color:var(--text-primary);font-size:12px;">
                            @if($log->user_agent)
                                <small>{{ Illuminate\Support\Str::limit($log->user_agent, 50) }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Perubahan Data (jika ada) --}}
        @if($log->changes && is_array($log->changes) && count($log->changes) > 0)
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:var(--text-primary);">Perubahan Data</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" style="font-size:12px;">
                            <thead style="background:var(--bg-tertiary);">
                                <tr>
                                    <th style="color:var(--text-primary);font-weight:700;padding:0.75rem;">Field</th>
                                    <th style="color:var(--text-primary);font-weight:700;padding:0.75rem;">Nilai Lama</th>
                                    <th style="color:var(--text-primary);font-weight:700;padding:0.75rem;">Nilai Baru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log->changes as $field => $change)
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:0.75rem;color:var(--text-primary);font-weight:500;">{{ $field }}</td>
                                        <td style="padding:0.75rem;color:var(--text-secondary);">
                                            @if(is_array($change) && isset($change['old']))
                                                <code style="background:var(--bg-tertiary);padding:4px 8px;border-radius:4px;font-size:11px;">
                                                    {{ Illuminate\Support\Str::limit($change['old'], 40) }}
                                                </code>
                                            @endif
                                        </td>
                                        <td style="padding:0.75rem;color:var(--text-primary);">
                                            @if(is_array($change) && isset($change['new']))
                                                <code style="background:#dcfce720;padding:4px 8px;border-radius:4px;font-size:11px;color:#15803d;">
                                                    {{ Illuminate\Support\Str::limit($change['new'], 40) }}
                                                </code>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar Info --}}
    <div class="col-12 col-lg-4">
        <div class="card card-custom">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:var(--text-primary);">Informasi User</h6>
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width:56px;height:56px;border-radius:12px;overflow:hidden;background:var(--bg-tertiary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        @if($log->user->profile_photo)
                            <img src="{{ Storage::url($log->user->profile_photo) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="font-weight:700;font-size:20px;color:var(--text-secondary);">
                                {{ strtoupper(substr($log->user->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div style="min-width:0;">
                        <div style="font-weight:600;color:var(--text-primary);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $log->user->name }}
                        </div>
                        <small class="text-muted">{{ $log->user->email }}</small>
                    </div>
                </div>

                <hr style="border-color:var(--border-color);">

                <div style="font-size:13px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border-color);">
                        <span class="text-muted">NIP:</span>
                        <strong style="color:var(--text-primary);">{{ $log->user->nip ?? '-' }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-bottom:12px;">
                        <span class="text-muted">Total Aktivitas:</span>
                        <strong style="color:var(--text-primary);">
                            {{ $log->user->activityLogs->count() ?? 0 }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
