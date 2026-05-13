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
                        <div class="text-muted small fw-semibold text-uppercase" style="font-size:10px;letter-spacing:.08em;">Proses aktif</div>
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
                        <div class="text-muted small fw-semibold text-uppercase" style="font-size:10px;letter-spacing:.08em;">Perlu perhatian</div>
                        <div class="fs-4 fw-bold text-danger">{{ $aktifTerlambat }}</div>
                        <div class="small text-muted">Melewati deadline SLA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-4">
            <div class="card-custom p-4 h-100">
                <p class="small text-muted mb-2 mb-lg-3">
                    Grafik menampilkan surat Anda yang <strong>selesai</strong> per bulan: dibandingkan tepat waktu vs terlambat terhadap deadline,
                    serta rata-rata jam dari pengajuan hingga selesai.
                </p>
                <a href="{{ route('user.surat.table') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="bi bi-table me-1"></i> Lihat semua surat
                </a>
            </div>
        </div>
    </div>

    <div class="card-custom p-4 mb-4">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h5 fw-bold mb-1" style="color: var(--text-primary);">
                    <i class="bi bi-speedometer2 text-primary me-2"></i>Tren SLA Surat Saya
                </h2>
                <p class="text-muted mb-0 small">6 bulan terakhir — batang: jumlah selesai · garis: rata-rata jam pemrosesan</p>
            </div>
            <div class="d-flex flex-wrap gap-3 small">
                <span class="d-inline-flex align-items-center gap-2"><span class="rounded" style="width:12px;height:12px;background:#10b981;"></span> Tepat waktu</span>
                <span class="d-inline-flex align-items-center gap-2"><span class="rounded" style="width:12px;height:12px;background:#ef4444;"></span> Terlambat</span>
                <span class="d-inline-flex align-items-center gap-2"><span class="rounded-circle border border-primary" style="width:10px;height:10px;background:#4361ee;"></span> Rata-rata jam</span>
            </div>
        </div>
        <div style="height: 380px; position: relative;">
            <canvas id="userSlaMixedChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('userSlaMixedChart');
    if (!ctx) return;

    const labels = @json($monthLabels);
    const tepat = @json($tepatWaktu);
    const telat = @json($terlambatSelesai);
    const avgJam = @json($rataJamSelesai);

    new Chart(ctx.getContext('2d'), {
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
});
</script>
@endpush
