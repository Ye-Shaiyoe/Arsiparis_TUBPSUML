@extends('layouts.user')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0 fw-bold" style="color: var(--text-primary);">
                <i class="bi bi-bar-chart-line text-primary me-2"></i>Statistik Saya
            </h2>
            <p class="text-secondary mb-0" style="font-size: 13px;">Ringkasan dan visualisasi data pengajuan surat Anda.</p>
        </div>
    </div>

    <!-- Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total Surat -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon text-primary"><i class="bi bi-envelope-paper"></i></div>
                <div class="stat-value">{{ $totalSurat }}</div>
                <div class="stat-label">Total Pengajuan</div>
            </div>
        </div>
        <!-- Disetujui -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon text-success"><i class="bi bi-check-circle"></i></div>
                <div class="stat-value text-success">{{ $totalDisetujui }}</div>
                <div class="stat-label">Disetujui</div>
            </div>
        </div>
        <!-- Diproses -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-value text-warning">{{ $totalProses }}</div>
                <div class="stat-label">Diproses</div>
            </div>
        </div>
        <!-- Ditolak -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon text-danger"><i class="bi bi-x-circle"></i></div>
                <div class="stat-value text-danger">{{ $totalDitolak }}</div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4">
        <!-- Line Chart -->
        <div class="col-md-8">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4" style="font-size: 15px;"><i class="bi bi-graph-up text-primary me-2"></i>Tren Pengajuan Surat (6 Bulan Terakhir)</h5>
                <div style="height: 300px; position: relative;">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Doughnut Chart -->
        <div class="col-md-4">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4" style="font-size: 15px;"><i class="bi bi-pie-chart text-success me-2"></i>Status Pengajuan</h5>
                <div style="height: 250px; position: relative; display: flex; justify-content: center;">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart Configuration
        const ctxLine = document.getElementById('lineChart').getContext('2d');
        
        // Gradient for Line Chart
        let gradientLine = ctxLine.createLinearGradient(0, 0, 0, 300);
        gradientLine.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
        gradientLine.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Surat',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: gradientLine,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#64748b' },
                        grid: { color: 'rgba(255,255,255,0.4)', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#64748b' },
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });

        // Doughnut Chart Configuration
        const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($statusLabels) !!},
                datasets: [{
                    data: {!! json_encode($statusData) !!},
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)', // Disetujui (Green)
                        'rgba(239, 68, 68, 0.8)',  // Ditolak (Red)
                        'rgba(245, 158, 11, 0.8)'  // Diproses (Yellow/Orange)
                    ],
                    borderColor: 'rgba(255,255,255,0.5)',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#475569',
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
