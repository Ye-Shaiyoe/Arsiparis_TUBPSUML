@extends('layouts.user')
@section('title', 'Profil: ' . $user->name)

@section('content')
<div class="container-fluid py-4">
    
    {{-- Back Button --}}
    <a href="{{ route('user.pegawai.index') }}" class="pg-back-btn">
        <i class="bi bi-arrow-left-circle-fill fs-5"></i> Kembali ke Pencarian
    </a>

    {{-- Profile Glass Hero Card --}}
    <div class="profile-hero-glass animate-in">
        <div style="height: 6px; background: linear-gradient(90deg, #1e3a8a 0%, #4361ee 50%, #0ea5e9 100%);"></div>
        <div class="profile-hero-inner d-flex justify-content-between align-items-center flex-wrap gap-4">
            <div class="d-flex align-items-center gap-4 flex-wrap flex-sm-nowrap">
                <div class="profile-avatar-box">
                    @if($user->profile_photo)
                        <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    @endif
                </div>

                <div class="profile-meta">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <h3 class="profile-name fw-extrabold mb-0" style="color: #0f172a;">{{ $user->name }}</h3>
                        <span class="profile-role-badge">{{ $user->getRoleLabel() }}</span>
                    </div>
                    <div class="profile-detail-row">
                        <div class="profile-detail-item">
                            <i class="bi bi-credit-card-2-front-fill"></i>
                            <span>NIP: <strong class="text-dark">{{ $user->nip ? (substr($user->nip, 0, 3) . str_repeat('*', max(0, strlen($user->nip) - 3))) : '—' }}</strong></span>
                        </div>
                        <div class="profile-detail-item">
                            <i class="bi bi-envelope-fill"></i>
                            <span class="text-dark">{{ $user->email }}</span>
                        </div>
                        <div class="profile-detail-item">
                            <i class="bi bi-calendar-event-fill"></i>
                            <span>Bergabung <strong class="text-dark">{{ $user->created_at->translatedFormat('M Y') }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 ms-auto flex-wrap">
                {{-- Year Filter Form --}}
                <form action="{{ route('user.pegawai.show', $user->uuid) }}" method="GET" id="yearForm" data-turbo="false" class="m-0">
                    <input type="hidden" name="heatmap_year" value="{{ $heatmapYear }}">
                    <div class="input-group-glass">
                        <span class="input-group-text-glass"><i class="bi bi-calendar3 text-cyan"></i></span>
                        <select name="tahun" class="form-select-glass" onchange="document.getElementById('yearForm').submit()">
                            @php $startYear = 2024; $currentYear = date('Y'); @endphp
                            @for($y = $currentYear; $y >= $startYear; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </form>

                <a href="mailto:{{ $user->email }}"
                   class="btn-email-glass">
                    <i class="bi bi-send-fill me-2"></i>Kirim Email
                </a>
            </div>
        </div>
    </div>

    {{-- Glowing Premium Stats Cards --}}
    <div class="row g-4 mb-4 animate-in" style="animation-delay: 0.1s;">
        <!-- Total Surat -->
        <div class="col-6 col-lg-3">
            <div class="premium-stat-card card-cyan">
                <div class="card-glow"></div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-envelope-paper-fill"></i>
                    </div>
                    <span class="trend-badge positive"><i class="bi bi-graph-up me-1"></i>100%</span>
                </div>
                <div class="stat-num">{{ $totalSurat }}</div>
                <div class="stat-title-custom">Total Pengajuan</div>
                <div class="stat-footer-text">Tahun {{ $tahun }}</div>
            </div>
        </div>
        <!-- Disetujui -->
        <div class="col-6 col-lg-3">
            <div class="premium-stat-card card-green">
                <div class="card-glow"></div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <span class="trend-badge positive-green">
                        @if($totalSurat > 0)
                            {{ round(($totalDisetujui / $totalSurat) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
                <div class="stat-num text-emerald">{{ $totalDisetujui }}</div>
                <div class="stat-title-custom">Telah Disetujui</div>
                <div class="stat-footer-text">Tahun {{ $tahun }}</div>
            </div>
        </div>
        <!-- Diproses -->
        <div class="col-6 col-lg-3">
            <div class="premium-stat-card card-amber">
                <div class="card-glow"></div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <span class="trend-badge warning-amber">
                        @if($totalSurat > 0)
                            {{ round(($totalProses / $totalSurat) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
                <div class="stat-num text-amber">{{ $totalProses }}</div>
                <div class="stat-title-custom">Sedang Diproses</div>
                <div class="stat-footer-text">Tahun {{ $tahun }}</div>
            </div>
        </div>
        <!-- Ditolak / Perlu Revisi -->
        <div class="col-6 col-lg-3">
            <div class="premium-stat-card card-rose">
                <div class="card-glow"></div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <span class="trend-badge critical-rose">
                        @if($totalSurat > 0)
                            {{ round(($totalDitolak / $totalSurat) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
                <div class="stat-num text-rose">{{ $totalDitolak }}</div>
                <div class="stat-title-custom">Ditolak / Revisi</div>
                <div class="stat-footer-text">Tahun {{ $tahun }}</div>
            </div>
        </div>
    </div>

    {{-- Heatmap Row --}}
    <div class="row mb-4 animate-in" style="animation-delay: 0.15s;">
        <div class="col-12">
            <div class="card-glass p-4" style="border-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1 text-gradient-primary d-flex align-items-center" style="font-size: 15px;">
                            <i class="bi bi-grid-3x3-gap-fill text-cyan me-2"></i>Heatmap Kontribusi Aktivitas Pegawai
                        </h5>
                        <p class="text-secondary mb-0" style="font-size: 11px;">Frekuensi kontribusi pengajuan surat harian pegawai.</p>
                    </div>
                    <div>
                        <form action="{{ route('user.pegawai.show', $user->uuid) }}" method="GET" id="heatmapYearForm" data-turbo="false" class="m-0">
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <div class="input-group-glass">
                                <span class="input-group-text-glass"><i class="bi bi-calendar3 text-cyan"></i></span>
                                <select name="heatmap_year" class="form-select-glass" onchange="document.getElementById('heatmapYearForm').submit()">
                                    @php $startYear = 2024; $currentYear = date('Y'); @endphp
                                    @for($y = $currentYear; $y >= $startYear; $y--)
                                        <option value="{{ $y }}" {{ $heatmapYear == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <x-activity-heatmap :data="$heatmapData" :selected-year="$heatmapYear" title="" />
            </div>
        </div>
    </div>

    {{-- Main Visual Charts Row --}}
    <div class="row g-4 mb-4 animate-in" style="animation-delay: 0.2s;">
        <!-- Line Chart -->
        <div class="col-lg-8">
            <div class="card-glass p-4 h-100" style="border-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-gradient-primary d-flex align-items-center" style="font-size: 15px;">
                        <i class="bi bi-graph-up-arrow text-cyan me-2"></i>Tren Aktivitas Bulanan Pegawai
                    </h5>
                    <span class="badge bg-cyan-soft text-cyan px-2.5 py-1.5 fw-bold" style="font-size: 11px;">12 Bulan</span>
                </div>
                <div style="height: 320px; position: relative;">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Doughnut Status Chart -->
        <div class="col-lg-4">
            <div class="card-glass p-4 h-100" style="border-radius: 20px;">
                <h5 class="fw-bold mb-4 text-gradient-primary d-flex align-items-center" style="font-size: 15px;">
                    <i class="bi bi-pie-chart text-emerald me-2"></i>Rasio Status Pengajuan
                </h5>
                <div style="height: 230px; position: relative; display: flex; justify-content: center; align-items: center;">
                    <canvas id="doughnutChart"></canvas>
                    <div class="absolute-center text-center">
                        <div class="fw-extrabold text-gradient-primary" style="font-size: 26px; line-height: 1;">
                            {{ $totalSurat }}
                        </div>
                        <div class="text-secondary" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px;">
                            Total Surat
                        </div>
                    </div>
                </div>
                <div class="row g-2 mt-3 pt-3 border-top border-white-5">
                    <div class="col-4 text-center">
                        <div class="text-emerald fw-bold mb-0" style="font-size: 14px;">{{ $totalDisetujui }}</div>
                        <div class="text-muted" style="font-size: 10px;">Setuju</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="text-amber fw-bold mb-0" style="font-size: 14px;">{{ $totalProses }}</div>
                        <div class="text-muted" style="font-size: 10px;">Proses</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="text-rose fw-bold mb-0" style="font-size: 14px;">{{ $totalDitolak }}</div>
                        <div class="text-muted" style="font-size: 10px;">Tolak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Advanced Section: Tables with Inline Sparkline Charts --}}
    <div class="row g-4 mb-4 animate-in" style="animation-delay: 0.25s;">
        <!-- Table 1: Bulanan + Sparkline Harian -->
        <div class="col-xl-7">
            <div class="card-glass p-4 h-100" style="border-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1 text-gradient-primary d-flex align-items-center" style="font-size: 15px;">
                            <i class="bi bi-table text-cyan me-2"></i>Analisis &amp; Aktivitas Bulanan Pegawai
                        </h5>
                        <p class="text-secondary mb-0" style="font-size: 11px;">Rincian total dan fluktuasi harian per bulan.</p>
                    </div>
                    <span class="badge bg-cyan-soft text-cyan px-2.5 py-1.5 fw-bold" style="font-size: 11px;">Sparkline Aktif</span>
                </div>
                <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                    <table class="table-custom align-middle">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Bulan</th>
                                <th class="text-center" style="width: 15%;">Total</th>
                                <th class="text-center" style="width: 30%;">Status</th>
                                <th class="text-center" style="width: 15%;">Selesai %</th>
                                <th class="text-center" style="width: 25%;">Tren Harian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyDetails as $mDetail)
                                <tr>
                                    <td class="fw-bold" style="color: var(--text-primary);">{{ $mDetail['name'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-soft text-primary fw-bold" style="font-size: 12px; min-width: 30px;">
                                            {{ $mDetail['total'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($mDetail['total'] > 0)
                                            <div class="progress-segmented-custom">
                                                <div class="segment-green" style="width: {{ ($mDetail['disetujui'] / $mDetail['total']) * 100 }}%" title="Disetujui: {{ $mDetail['disetujui'] }}"></div>
                                                <div class="segment-yellow" style="width: {{ ($mDetail['proses'] / $mDetail['total']) * 100 }}%" title="Diproses: {{ $mDetail['proses'] }}"></div>
                                                <div class="segment-red" style="width: {{ ($mDetail['ditolak'] / $mDetail['total']) * 100 }}%" title="Ditolak: {{ $mDetail['ditolak'] }}"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1 text-muted" style="font-size: 9px; font-weight: 600;">
                                                <span class="text-emerald">{{ $mDetail['disetujui'] }}</span>
                                                <span class="text-amber">{{ $mDetail['proses'] }}</span>
                                                <span class="text-rose">{{ $mDetail['ditolak'] }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted d-block text-center" style="font-size: 11px; font-style: italic;">Tidak ada data</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold">
                                        @if($mDetail['total'] > 0)
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <span class="text-emerald">{{ round(($mDetail['disetujui'] / $mDetail['total']) * 100) }}%</span>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($mDetail['total'] > 0)
                                            <div class="d-flex justify-content-center">
                                                <canvas id="sparkline-month-{{ $loop->index }}" width="110" height="26" class="sparkline-canvas" data-sparkline-data="{{ json_encode($mDetail['sparkline']) }}"></canvas>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-center align-items-center" style="height: 26px;">
                                                <div class="sparkline-placeholder"></div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Table 2: Jenis Surat + Sparkline Bulanan -->
        <div class="col-xl-5">
            <div class="card-glass p-4 h-100" style="border-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1 text-gradient-primary d-flex align-items-center" style="font-size: 15px;">
                            <i class="bi bi-tag text-emerald me-2"></i>Kinerja Per Jenis Surat Pegawai
                        </h5>
                        <p class="text-secondary mb-0" style="font-size: 11px;">Analisis efektivitas berdasarkan klasifikasi surat.</p>
                    </div>
                    <span class="badge bg-emerald-soft text-emerald px-2.5 py-1.5 fw-bold" style="font-size: 11px;">Kategori</span>
                </div>
                <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                    <table class="table-custom align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Jenis Surat</th>
                                <th class="text-center" style="width: 15%;">Total</th>
                                <th class="text-center" style="width: 20%;">Rasio</th>
                                <th class="text-center" style="width: 25%;">Tren 12B</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jenisDetails as $jDetail)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-truncate" style="color: var(--text-primary); max-width: 150px;" title="{{ $jDetail['label'] }}">
                                            {{ $jDetail['label'] }}
                                        </div>
                                        <div class="d-flex align-items-center gap-1.5 mt-0.5" style="font-size: 10px;">
                                            <span class="text-emerald" title="Disetujui"><i class="bi bi-check-circle me-0.5"></i>{{ $jDetail['disetujui'] }}</span>
                                            <span class="text-muted">|</span>
                                            <span class="text-rose" title="Ditolak"><i class="bi bi-x-circle me-0.5"></i>{{ $jDetail['ditolak'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-cyan-soft text-cyan fw-bold" style="font-size: 12px;">
                                            {{ $jDetail['total'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($jDetail['total'] > 0)
                                            @php 
                                                $rate = round(($jDetail['disetujui'] / $jDetail['total']) * 100); 
                                                $colorClass = $rate >= 75 ? 'text-emerald' : ($rate >= 50 ? 'text-amber' : 'text-rose');
                                            @endphp
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold {{ $colorClass }}" style="font-size: 13px;">{{ $rate }}%</span>
                                                <div class="progress-mini-bar mt-1">
                                                    <div class="progress-mini-fill" style="width: {{ $rate }}%; background-color: var(--{{ $rate >= 75 ? 'emerald' : ($rate >= 50 ? 'amber' : 'rose') }});"></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <canvas id="sparkline-jenis-{{ $loop->index }}" width="100" height="26" class="sparkline-canvas-bar" data-sparkline-data="{{ json_encode($jDetail['trend']) }}"></canvas>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted" style="font-style: italic; font-size: 12px;">
                                        Tidak ada data pengajuan surat pada tahun ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Distribusi Jenis Surat & Bar Chart Row --}}
    <div class="row g-4 animate-in" style="animation-delay: 0.3s;">
        <!-- Distribusi Jenis Surat Polar Area -->
        <div class="col-lg-6">
            <div class="card-glass p-4 h-100" style="border-radius: 20px;">
                <h5 class="fw-bold mb-4 text-gradient-primary d-flex align-items-center" style="font-size: 15px;">
                    <i class="bi bi-compass text-cyan me-2"></i>Komparasi Volume Jenis Surat
                </h5>
                <div style="height: 270px; position: relative; display: flex; justify-content: center;">
                    <canvas id="jenisChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Bar Chart Tren -->
        <div class="col-lg-6">
            <div class="card-glass p-4 h-100" style="border-radius: 20px;">
                <h5 class="fw-bold mb-4 text-gradient-primary d-flex align-items-center" style="font-size: 15px;">
                    <i class="bi bi-bar-chart-fill text-amber me-2"></i>Frekuensi Pengajuan Surat
                </h5>
                <div style="height: 270px; position: relative;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Stylings for Advanced Visual Stats -->
<style>
    /* Gradient text helper */
    .text-gradient-primary {
        background: linear-gradient(135deg, #1e293b 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .text-cyan {
        color: #06b6d4 !important;
    }
    
    .text-emerald {
        color: #10b981 !important;
    }
    
    .text-amber {
        color: #f59e0b !important;
    }
    
    .text-rose {
        color: #f43f5e !important;
    }
    
    /* Card Glassmorphism layout */
    .card-glass {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card-glass:hover {
        box-shadow: 0 15px 35px -8px rgba(0, 0, 0, 0.06);
    }
    
    /* Select form elements */
    .input-group-glass {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        padding: 0.15rem 0.5rem;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    
    .input-group-text-glass {
        background: transparent;
        border: none;
        padding-right: 0.25rem;
    }
    
    .form-select-glass {
        background: transparent;
        border: none;
        outline: none;
        color: var(--text-primary);
        font-weight: 700;
        font-size: 13.5px;
        padding: 0.4rem 2rem 0.4rem 0.5rem;
        cursor: pointer;
    }
    
    .form-select-glass:focus {
        box-shadow: none;
    }
    
    /* Premium Stats Cards styles */
    .premium-stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.02);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .premium-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.07);
    }
    
    .premium-stat-card.card-cyan:hover {
        border-color: rgba(6, 182, 212, 0.4);
    }
    .premium-stat-card.card-green:hover {
        border-color: rgba(16, 185, 129, 0.4);
    }
    .premium-stat-card.card-amber:hover {
        border-color: rgba(245, 158, 11, 0.4);
    }
    .premium-stat-card.card-rose:hover {
        border-color: rgba(244, 63, 94, 0.4);
    }
    
    .card-glow {
        position: absolute;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0) 70%);
        top: -65px;
        right: -65px;
        opacity: 0.12;
        pointer-events: none;
        transition: transform 0.5s ease;
    }
    
    .premium-stat-card:hover .card-glow {
        transform: scale(1.3);
        opacity: 0.22;
    }
    
    .stat-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    
    .card-cyan .stat-icon-wrapper {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }
    .card-green .stat-icon-wrapper {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .card-amber .stat-icon-wrapper {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    .card-rose .stat-icon-wrapper {
        background: rgba(244, 63, 94, 0.1);
        color: #f43f5e;
    }
    
    .trend-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 0.25rem 0.5rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
    }
    
    .trend-badge.positive {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }
    
    .trend-badge.positive-green {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .trend-badge.warning-amber {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .trend-badge.critical-rose {
        background: rgba(244, 63, 94, 0.1);
        color: #f43f5e;
    }
    
    .stat-num {
        font-size: 32px;
        font-weight: 900;
        line-height: 1.1;
        margin-top: 0.65rem;
        letter-spacing: -0.03em;
        color: var(--text-primary);
    }
    
    .stat-title-custom {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }
    
    .stat-footer-text {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 0.5rem;
    }
    
    /* Segmented status indicator */
    .progress-segmented-custom {
        display: flex;
        height: 6px;
        border-radius: 99px;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255,255,255,0.4);
    }
    
    .segment-green {
        background-color: #10b981;
        transition: width 0.3s ease;
    }
    
    .segment-yellow {
        background-color: #f59e0b;
        transition: width 0.3s ease;
    }
    
    .segment-red {
        background-color: #f43f5e;
        transition: width 0.3s ease;
    }
    
    /* Table styles */
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-custom th {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid rgba(0, 0, 0, 0.04);
    }
    
    .table-custom td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        font-size: 13px;
    }
    
    .table-custom tr:last-child td {
        border-bottom: none;
    }
    
    /* mini progress bar */
    .progress-mini-bar {
        width: 100%;
        height: 4px;
        background: rgba(0, 0, 0, 0.04);
        border-radius: 99px;
        overflow: hidden;
    }
    
    .progress-mini-fill {
        height: 100%;
        border-radius: 99px;
    }
    
    /* Sparkline canvas placeholder */
    .sparkline-placeholder {
        width: 60px;
        height: 2px;
        background: rgba(0, 0, 0, 0.06);
        border-radius: 99px;
    }
    
    .absolute-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
    }
    
    /* Badges */
    .bg-cyan-soft {
        background-color: rgba(6, 182, 212, 0.12) !important;
    }
    .bg-emerald-soft {
        background-color: rgba(16, 185, 129, 0.12) !important;
    }
    .bg-primary-soft {
        background-color: rgba(37, 99, 235, 0.09) !important;
    }
    
    .border-white-5 {
        border-color: rgba(0,0,0,0.06) !important;
    }

    /* Back button & profile hero customizations */
    .pg-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        color: #1e3a8a;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.22s ease-in-out;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        margin-bottom: 24px;
    }
    .pg-back-btn:hover {
        background: #1e3a8a;
        color: white;
        transform: translateX(-4px);
        box-shadow: 0 6px 16px rgba(30, 58, 138, 0.15);
    }
    .profile-hero-glass {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 24px;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 28px;
    }
    .profile-hero-inner {
        padding: 36px;
    }
    .profile-avatar-box {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 4px solid white;
        overflow: hidden;
        background: linear-gradient(135deg, #1e3a8a, #4361ee);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 800;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(67, 97, 238, 0.2);
    }
    .profile-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-meta {
        flex: 1;
        min-width: 250px;
    }
    .profile-name {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }
    .profile-role-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 99px;
        background: rgba(67, 97, 238, 0.08);
        color: #4361ee;
        border: 1px solid rgba(67, 97, 238, 0.15);
        vertical-align: middle;
    }
    .profile-detail-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 12px;
    }
    .profile-detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: #64748b;
        font-weight: 500;
    }
    .profile-detail-item i {
        font-size: 16px;
        color: #4361ee;
    }

    .btn-email-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 42px;
        padding: 0 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        background: linear-gradient(135deg, #1e3a8a 0%, #4361ee 100%);
        border: none;
        color: white;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 14px rgba(67, 97, 238, 0.3);
    }
    .btn-email-glass:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        color: white;
    }
</style>

<!-- Import Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    (function() {
        // Shared colors definition for charts
        const colors = {
            cyan: '#06b6d4',
            cyanLight: 'rgba(6, 182, 212, 0.4)',
            cyanTransparent: 'rgba(6, 182, 212, 0.0)',
            emerald: '#10b981',
            amber: '#f59e0b',
            rose: '#f43f5e',
            gray: '#64748b',
            border: 'rgba(0,0,0,0.03)'
        };

        // Line Chart Configuration
        const ctxLine = document.getElementById('lineChart');
        if (ctxLine) {
            const ctx = ctxLine.getContext('2d');
            let gradientLine = ctx.createLinearGradient(0, 0, 0, 300);
            gradientLine.addColorStop(0, colors.cyanLight);
            gradientLine.addColorStop(1, colors.cyanTransparent);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Surat',
                        data: {!! json_encode($chartData) !!},
                        borderColor: colors.cyan,
                        backgroundColor: gradientLine,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: colors.cyan,
                        pointBorderWidth: 2.5,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: colors.cyan,
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2.5,
                        fill: true,
                        tension: 0.38
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#fff',
                            bodyColor: '#e2e8f0',
                            padding: 10,
                            borderRadius: 8,
                            boxPadding: 4
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: colors.gray, font: { family: 'Plus Jakarta Sans', weight: '600' } },
                            grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                        },
                        x: {
                            ticks: { color: colors.gray, font: { family: 'Plus Jakarta Sans', weight: '600' } },
                            grid: { display: false, drawBorder: false }
                        }
                    }
                }
            });
        }

        // Doughnut Chart Configuration
        const ctxDoughnut = document.getElementById('doughnutChart');
        if (ctxDoughnut) {
            new Chart(ctxDoughnut.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($statusLabels) !!},
                    datasets: [{
                        data: {!! json_encode($statusData) !!},
                        backgroundColor: [
                            '#10b981', // Disetujui (Green)
                            '#f43f5e', // Ditolak (Red)
                            '#f59e0b'  // Diproses (Yellow/Orange)
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 3.5,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '76%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            borderRadius: 8
                        }
                    }
                }
            });
        }

        // Distribusi Jenis Surat Polar Area Chart
        const jenisCtx = document.getElementById('jenisChart');
        if (jenisCtx) {
            const jenisData = @json($jenisSurat);
            const jenisLabels = @json(\App\Models\Surat::JENIS_LABEL);
            
            const labels = Object.keys(jenisData).map(key => jenisLabels[key] || key);
            const data = Object.values(jenisData);
            const colorsArray = [
                'rgba(6, 182, 212, 0.8)', 
                'rgba(16, 185, 129, 0.8)', 
                'rgba(245, 158, 11, 0.8)', 
                'rgba(244, 63, 94, 0.8)', 
                'rgba(99, 102, 241, 0.8)', 
                'rgba(168, 85, 247, 0.8)', 
                'rgba(236, 72, 153, 0.8)'
            ];
            
            new Chart(jenisCtx.getContext('2d'), {
                type: 'polarArea',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colorsArray.slice(0, data.length),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 16,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: colors.gray,
                                font: { family: 'Plus Jakarta Sans', size: 10.5, weight: '600' }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            borderRadius: 8
                        }
                    },
                    scales: {
                        r: {
                            ticks: { display: false },
                            grid: { color: 'rgba(0,0,0,0.04)' }
                        }
                    }
                }
            });
        }

        // Bar Chart Tren
        const barCtx = document.getElementById('barChart');
        if (barCtx) {
            new Chart(barCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Surat',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(6, 182, 212, 0.75)',
                        hoverBackgroundColor: '#06b6d4',
                        borderColor: '#06b6d4',
                        borderWidth: 0,
                        borderRadius: 6,
                        barPercentage: 0.55
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            borderRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: colors.gray, font: { family: 'Plus Jakarta Sans', weight: '600' } },
                            grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                        },
                        x: {
                            ticks: { color: colors.gray, font: { family: 'Plus Jakarta Sans', weight: '600' } },
                            grid: { display: false, drawBorder: false }
                        }
                    }
                }
            });
        }

        // --- INLINE SPARKLINE INITIALIZATIONS ---
        
        // 1. Monthly Daily Sparklines (Line Style)
        document.querySelectorAll('.sparkline-canvas').forEach(canvas => {
            const data = JSON.parse(canvas.getAttribute('data-sparkline-data') || '[]');
            new Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.map((_, i) => i + 1),
                    datasets: [{
                        data: data,
                        borderColor: '#06b6d4',
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: true,
                        backgroundColor: 'rgba(6, 182, 212, 0.08)',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    }
                }
            });
        });

        // 2. Jenis Monthly Trend Sparklines (Bar Style)
        document.querySelectorAll('.sparkline-canvas-bar').forEach(canvas => {
            const data = JSON.parse(canvas.getAttribute('data-sparkline-data') || '[]');
            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.map((_, i) => i + 1),
                    datasets: [{
                        data: data,
                        backgroundColor: '#10b981',
                        borderRadius: 2,
                        barPercentage: 0.7
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    }
                }
            });
        });

    })();
</script>
@endsection
