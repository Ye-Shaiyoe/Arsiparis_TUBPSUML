@extends('layouts.user')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate-in">
        <div>
            <h2 class="h4 mb-0 fw-bold" style="color: var(--text-primary);">
                <i class="bi bi-bar-chart-line text-primary me-2"></i>Statistik Saya
            </h2>
            <p class="text-secondary mb-0" style="font-size: 13px;">Ringkasan dan visualisasi data pengajuan surat Anda.</p>
        </div>
        <div>
            <form action="{{ route('user.statistik.index') }}" method="GET" id="yearForm" data-turbo="false">
                <select name="tahun" class="form-select" onchange="document.getElementById('yearForm').submit()" style="width: 140px; border-radius: 10px; font-weight: 600; border-color: #e5e7eb;">
                    @php $startYear = 2024; $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <!-- Cards Row -->
    <div class="row g-4 mb-4 animate-in" style="animation-delay: 0.1s;">
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
        <!-- Ditolak </> -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon text-danger"><i class="bi bi-x-circle"></i></div>
                <div class="stat-value text-danger">{{ $totalDitolak }}</div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
    </div>

    <!-- Heatmap Row (GitHub-style Contribution Grid) -->
    <div class="row mb-4 animate-in" style="animation-delay: 0.15s;">
        <div class="col-12">
            <x-activity-heatmap :data="$heatmapData" :selected-year="$heatmapYear" title="Kontribusi Aktivitas Pengajuan Surat" />
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 animate-in" style="animation-delay: 0.2s;">
        <!-- Line Chart -->
        <div class="col-md-8">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4" style="font-size: 15px;"><i class="bi bi-graph-up text-primary me-2"></i>Tren Pengajuan Surat (Line Chart)</h5>
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

    <!-- Additional Charts Row -->
    <div class="row g-4 mt-1 animate-in" style="animation-delay: 0.3s;">
        <!-- Distribusi Jenis Surat -->
        <div class="col-md-6">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4" style="font-size: 15px;"><i class="bi bi-pie-chart-fill text-info me-2"></i>Distribusi Jenis Surat</h5>
                <div style="height: 250px; position: relative; display: flex; justify-content: center;">
                    <canvas id="jenisChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Bar Chart Tren -->
        <div class="col-md-6">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4" style="font-size: 15px;"><i class="bi bi-bar-chart-fill text-warning me-2"></i>Tren Pengajuan Surat (Bar Chart)</h5>
                <div style="height: 250px; position: relative;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    (function() {
        // Line Chart Configuration
        const ctxLine = document.getElementById('lineChart');
        if (ctxLine) {
            const ctx = ctxLine.getContext('2d');
            // Gradient for Line Chart
            let gradientLine = ctx.createLinearGradient(0, 0, 0, 300);
            gradientLine.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
            gradientLine.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ctx, {
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
        }

        // Distribusi Jenis Surat
        const jenisCtx = document.getElementById('jenisChart');
        if (jenisCtx) {
            const jenisData = @json($jenisSurat);
            const jenisLabels = @json(\App\Models\Surat::JENIS_LABEL);
            
            const labels = Object.keys(jenisData).map(key => jenisLabels[key] || key);
            const data = Object.values(jenisData);
            const colors = [
                'rgba(30, 58, 95, 0.8)', 
                'rgba(37, 99, 235, 0.8)', 
                'rgba(34, 197, 94, 0.8)', 
                'rgba(245, 158, 11, 0.8)', 
                'rgba(239, 68, 68, 0.8)', 
                'rgba(139, 92, 246, 0.8)', 
                'rgba(236, 72, 153, 0.8)'
            ];
            
            new Chart(jenisCtx.getContext('2d'), {
                type: 'polarArea',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors.slice(0, data.length),
                        borderWidth: 1,
                        borderColor: '#ffffff',
                        hoverOffset: 8
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
                                font: { size: 12 }
                            }
                        }
                    },
                    scales: {
                        r: {
                            ticks: { display: false }
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
                        backgroundColor: 'rgba(30, 58, 95, 0.8)',
                        borderColor: '#1e3a5f',
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.6
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
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: { color: '#64748b' },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    })();
</script>
@endsection
