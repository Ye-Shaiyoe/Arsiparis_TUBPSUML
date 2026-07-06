@extends('layouts.user')

@section('content')
    <div class="container-fluid py-3">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card-custom p-4 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-success flex-shrink-0"
                            style="width:52px;height:52px;background:rgba(16,185,129,0.12);">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold text-uppercase"
                                style="font-size:10px;letter-spacing:.08em;">Proses aktif</div>
                            <div class="fs-4 fw-bold text-dark">{{ $aktifNormal }}</div>
                            <div class="small text-muted">Dalam tenggat SLA</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom p-4 h-100 border-danger border-opacity-25">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-danger flex-shrink-0"
                            style="width:52px;height:52px;background:rgba(239,68,68,0.12);">
                            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold text-uppercase"
                                style="font-size:10px;letter-spacing:.08em;">Perlu perhatian</div>
                            <div class="fs-4 fw-bold text-danger">{{ $aktifTerlambat }}</div>
                            <div class="small text-muted">Melewati deadline SLA</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="card-custom p-4 h-100">
                    <p class="small text-muted mb-2 mb-lg-3">
                        Grafik menampilkan surat Anda yang <strong>selesai</strong> per bulan: dibandingkan tepat waktu vs
                        terlambat terhadap deadline,
                        serta rata-rata jam dari pengajuan hingga selesai.
                    </p>
                    <a href="{{ route('user.surat.table') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="bi bi-table me-1"></i> Lihat semua surat
                    </a>
                </div>
            </div>
        </div>

        <style>
            .last-border-0:last-child {
                border-bottom: none !important;
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
        </style>

        <div class="row g-4 mb-4">
            <!-- Grafik -->
            <div class="col-12">
                <div class="card-custom p-4">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
                        <div>
                            <h2 class="h5 fw-bold mb-1" style="color: var(--text-primary);">
                                <i class="bi bi-speedometer2 text-primary me-2"></i>Tren SLA Surat Saya
                            </h2>
                            <p class="text-muted mb-0 small">6 bulan terakhir — batang: jumlah selesai · garis: rata-rata
                                jam pemrosesan</p>
                        </div>
                        <div class="d-flex flex-wrap gap-3 small">
                            <span class="d-inline-flex align-items-center gap-2"><span class="rounded"
                                    style="width:12px;height:12px;background:#10b981;"></span> Tepat waktu</span>
                            <span class="d-inline-flex align-items-center gap-2"><span class="rounded"
                                    style="width:12px;height:12px;background:#ef4444;"></span> Terlambat</span>
                        </div>
                    </div>
                    <div style="height: 380px; position: relative;">
                        <canvas id="userSlaMixedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Status SLA Surat Aktif -->
            <div class="col-12">
                <div class="card-custom p-0 overflow-hidden">
                    <div class="p-4 border-bottom">
                        <h2 class="h6 fw-bold mb-0" style="color: var(--text-primary);">
                            <i class="bi bi-speedometer text-primary me-2"></i>Status SLA Surat Aktif
                        </h2>
                    </div>
                    <div class="p-4" style="max-height: 600px; overflow-y: auto;">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                            @forelse($suratAktif as $s)
                                @php
                                    // SLA = 30 jam kerja. Gunakan deadline_sla untuk akurasi.
                                    $slaTotalJam = 30;
                                    if ($s->deadline_sla) {
                                        $hoursElapsed = round($s->created_at->diffInHours(now()), 1);
                                        $isTerlambat  = now()->gt($s->deadline_sla);
                                        $persen       = $s->deadline_sla
                                            ? min(100, round(($s->created_at->diffInMinutes(now()) / $s->created_at->diffInMinutes($s->deadline_sla)) * 100))
                                            : min(100, round(($hoursElapsed / $slaTotalJam) * 100));
                                    } else {
                                        $hoursElapsed = round($s->created_at->diffInHours(now()), 1);
                                        $isTerlambat  = $hoursElapsed >= $slaTotalJam;
                                        $persen       = min(100, round(($hoursElapsed / $slaTotalJam) * 100));
                                    }

                                    $sisaJamText = $s->sisa_jam;
                                    $hampirHabis = !$isTerlambat && ($s->deadline_sla
                                        ? now()->diffInHours($s->deadline_sla, false) <= 6
                                        : $hoursElapsed >= ($slaTotalJam - 6));

                                    if ($isTerlambat) {
                                        $colorClass = 'bg-danger';
                                    } elseif ($hampirHabis) {
                                        $colorClass = 'bg-warning text-dark';
                                    } else {
                                        $colorClass = 'bg-success';
                                    }
                                @endphp
                                <div class="col">
                                    <div class="p-3 border rounded-3 h-100 bg-white shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-dark text-truncate me-2">{{ $s->judul }}</span>
                                            @if($isTerlambat)
                                                <span class="badge rounded-pill bg-danger-subtle text-danger px-2 py-1"
                                                    style="font-size: 10px;">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Terlambat
                                                </span>
                                            @elseif($hampirHabis)
                                                <span class="badge rounded-pill bg-warning-subtle text-warning px-2 py-1"
                                                    style="font-size: 10px; color: #92400e !important;">
                                                    <i class="bi bi-clock-history me-1"></i>Hampir
                                                </span>
                                            @endif
                                        </div>
                                        <!-- SLA Progress Bar (basis 30 jam kerja) -->
                                        <div class="rounded-pill overflow-hidden bg-light mb-2" style="height: 6px;">
                                            <div class="h-100 {{ $colorClass }}"
                                                style="width: {{ $persen }}%; transition: width 1s ease-in-out;">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted" style="font-size: 11px;">
                                                <div class="fw-medium text-dark">{{ $s->nama_tahap }}</div>
                                                <div>Tahap {{ $s->tahap_sekarang }}/10</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-light text-dark border fw-semibold"
                                                    style="font-size: 11px;">
                                                    {{ $isTerlambat ? $sisaJamText : 'Sisa ' . $sisaJamText }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5 w-100">
                                        <div class="mb-3">
                                            <i class="bi bi-envelope-check text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="text-muted fw-bold">Tidak ada surat aktif</h6>
                                        <p class="text-muted small mb-0">Semua surat Anda telah selesai atau belum diajukan.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            (function() {
                function initSlaChart() {
                    const canvas = document.getElementById('userSlaMixedChart');
                    if (!canvas) return;
                    
                    // Check if Chart is defined (might be loading from CDN)
                    if (typeof Chart === 'undefined') {
                        setTimeout(initSlaChart, 100);
                        return;
                    }

                    // Destroy existing chart if any to prevent canvas reuse errors
                    const existingChart = Chart.getChart(canvas);
                    if (existingChart) existingChart.destroy();

                    const ctx = canvas.getContext('2d');

                    const labels = @json($monthLabels);
                    const tepat = @json($tepatWaktu);
                    const telat = @json($terlambatSelesai);
                    const avgJam = @json($rataJamSelesai);

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    type: 'bar',
                                    label: 'Selesai tepat waktu',
                                    data: tepat,
                                    backgroundColor: 'rgba(16, 185, 129, 0.75)',
                                    borderColor: '#059669',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                    yAxisID: 'y',
                                    order: 2,
                                },
                                {
                                    type: 'bar',
                                    label: 'Selesai terlambat',
                                    data: telat,
                                    backgroundColor: 'rgba(239, 68, 68, 0.75)',
                                    borderColor: '#dc2626',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                    yAxisID: 'y',
                                    order: 2,
                                },
                                {
                                    type: 'line',
                                    label: 'Rata-rata jam (pengajuan → selesai)',
                                    data: avgJam,
                                    borderColor: '#4361ee',
                                    backgroundColor: 'rgba(67, 97, 238, 0.08)',
                                    borderWidth: 3,
                                    tension: 0.35,
                                    fill: false,
                                    pointRadius: 5,
                                    pointBackgroundColor: '#4361ee',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    yAxisID: 'y1',
                                    order: 1,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: { usePointStyle: true, padding: 16, font: { size: 11, weight: '600' } },
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (ctx) {
                                            const v = ctx.parsed.y;
                                            if (ctx.dataset.yAxisID === 'y1') {
                                                return ctx.dataset.label + ': ' + v + ' jam';
                                            }
                                            return ctx.dataset.label + ': ' + v + ' surat';
                                        },
                                    },
                                },
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    position: 'left',
                                    beginAtZero: true,
                                    stacked: false,
                                    title: { display: true, text: 'Jumlah surat', font: { size: 11, weight: '600' } },
                                    ticks: { stepSize: 1 },
                                    grid: { color: 'rgba(0,0,0,0.06)' },
                                },
                                y1: {
                                    type: 'linear',
                                    position: 'right',
                                    beginAtZero: true,
                                    grid: { drawOnChartArea: false },
                                    title: { display: true, text: 'Jam', font: { size: 11, weight: '600' } },
                                },
                                x: {
                                    grid: { display: false },
                                },
                            },
                        },
                    });
                }

                // Initialize on Turbo load
                document.addEventListener('turbo:load', initSlaChart);
                
                // Also try immediate init for the first load
                if (document.readyState !== 'loading') {
                    initSlaChart();
                } else {
                    document.addEventListener('DOMContentLoaded', initSlaChart);
                }
            })();
        </script>
    @endpush