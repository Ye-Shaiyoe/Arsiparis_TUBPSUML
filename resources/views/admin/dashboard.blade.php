@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
    <style>
        /* Modernized Stat Cards */
        .stat-card-new {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card-new:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }

        .stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-card-new.blue .stat-icon-box {
            background: #eff6ff;
            color: #3b82f6;
        }

        .stat-card-new.green .stat-icon-box {
            background: #ecfdf5;
            color: #10b981;
        }

        .stat-card-new.amber .stat-icon-box {
            background: #fffbeb;
            color: #f59e0b;
        }

        .stat-card-new.red .stat-icon-box {
            background: #fef2f2;
            color: #ef4444;
        }

        .stat-info {
            flex: 1;
        }

        .stat-label-new {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value-new {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.1;
            margin: 4px 0;
        }

        .stat-sub-new {
            font-size: 11px;
            color: var(--text-secondary);
            opacity: 0.8;
        }

        /* Table Improvements */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background: var(--bg-tertiary);
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .pulse-live {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }

        .pulse-live::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid #22c55e;
            animation: pulseSimple 2s infinite;
        }

        @keyframes pulseSimple {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }

        .filter-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 12px 20px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
    </style>

    {{-- HEADER & FILTER --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Dashboard
                    Overview</h1>
                <div x-data="{ live: true }" x-show="live"
                    class="flex items-center gap-2 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20 rounded-lg text-[10px] font-bold tracking-widest">
                    <span class="pulse-live"></span> LIVE
                </div>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">Monitoring aktivitas persuratan unit
                kerja secara real-time.</p>
        </div>

        <form action="{{ route('admin.dashboard') }}" method="GET" class="filter-section shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider"><i class="bi bi-funnel"></i> Filter
                Periode</span>
            <div class="flex gap-2">
                <select name="bulan"
                    class="form-select !py-1.5 !px-3 !text-xs !font-semibold !rounded-lg !border-slate-200 dark:!border-slate-700 !bg-slate-50 dark:!bg-slate-800"
                    onchange="this.form.submit()">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulanSelected == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun"
                    class="form-select !py-1.5 !px-3 !text-xs !font-semibold !rounded-lg !border-slate-200 dark:!border-slate-700 !bg-slate-50 dark:!bg-slate-800"
                    onchange="this.form.submit()">
                    @php $startYear = 2024;
                    $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ $tahunSelected == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </form>
    </div>

    <div x-data="dashboardData" class="space-y-6">

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card-new blue">
                <div class="stat-icon-box shadow-sm"><i class="bi bi-envelope-paper"></i></div>
                <div class="stat-info">
                    <div class="stat-label-new">Total Surat</div>
                    <div class="stat-value-new" x-text="stats.totalBulanIni">{{ $totalBulanIni }}</div>
                    <div class="stat-sub-new">{{ \Carbon\Carbon::create()->month($bulanSelected)->translatedFormat('F') }}
                        {{ $tahunSelected }}</div>
                </div>
            </div>
            <div class="stat-card-new green">
                <div class="stat-icon-box shadow-sm"><i class="bi bi-check2-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-label-new">Selesai</div>
                    <div class="stat-value-new" x-text="stats.totalSelesai">{{ $totalSelesai }}</div>
                    <div class="stat-sub-new">Terarsipkan sistem</div>
                </div>
            </div>
            <div class="stat-card-new amber">
                <div class="stat-icon-box shadow-sm"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-info">
                    <div class="stat-label-new">Diproses</div>
                    <div class="stat-value-new" x-text="stats.totalProses">{{ $totalProses }}</div>
                    <div class="stat-sub-new">Menunggu aksi admin</div>
                </div>
            </div>
            <div class="stat-card-new red">
                <div class="stat-icon-box shadow-sm"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <div class="stat-label-new">Overdue SLA</div>
                    <div class="stat-value-new" x-text="stats.totalTerlambat">{{ $totalTerlambat }}</div>
                    <div class="stat-sub-new">Perlu penanganan kilat</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ANTRIAN AKSI --}}
            <div class="card !p-0 overflow-hidden lg:col-span-3 shadow-sm border-slate-200 dark:border-slate-800">
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="bi bi-list-task text-blue-500"></i> Antrian Menunggu Aksi
                            <span x-show="antrian.count > 0"
                                class="px-1.5 py-0.5 bg-red-500 text-white text-[10px] rounded-md"
                                x-text="antrian.count"></span>
                        </h2>
                        <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Segera proses surat-surat di bawah ini.</p>
                    </div>
                    <a href="{{ route('admin.surat.index') }}"
                        class="text-[12px] font-bold text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua <i
                            class="bi bi-chevron-right"></i></a>
                </div>

                <div class="table-wrap">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Judul & Tanggal</th>
                                <th>Pengusul</th>
                                <th>Klasifikasi</th>
                                <th>Posisi Tahap</th>
                                <th>Status</th>
                                <th>SLA</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="antrian.items.length === 0">
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <div class="flex flex-col items-center opacity-40">
                                            <i class="bi bi-check2-all text-4xl mb-2"></i>
                                            <p class="text-xs font-bold uppercase tracking-widest">Semua surat telah
                                                diproses</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="surat in antrian.items" :key="surat.uuid || surat.id">
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td>
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm line-clamp-1"
                                            x-text="surat.judul"></div>
                                        <div class="text-[10px] text-slate-500 mt-1 font-medium"><i
                                                class="bi bi-calendar-event me-1"></i> <span
                                                x-text="formatDate(surat.created_at)"></span></div>
                                    </td>
                                    <td>
                                        <div class="text-xs font-semibold text-slate-700 dark:text-slate-300"
                                            x-text="surat.user ? surat.user.name : '—'"></div>
                                    </td>
                                    <td>
                                        <div class="flex gap-1">
                                            <span class="badge badge-purple !text-[9px]" x-text="surat.jenis"></span>
                                            <span class="badge !text-[9px]"
                                                :class="surat.sifat === 'segera' ? 'badge-red' : 'badge-gray'"
                                                x-text="surat.sifat || 'Biasa'"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="text-[11px] font-bold text-blue-600"
                                                x-text="'Tahap ' + surat.tahap_sekarang"></div>
                                            <div
                                                class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full max-w-[60px] overflow-hidden">
                                                <div class="h-full bg-blue-500"
                                                    :style="'width:' + (surat.tahap_sekarang * 10) + '%'"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge !text-[10px]" :class="surat.status === 'revisi' ? 'badge-yellow' : 
                                                         surat.status === 'revisi_admin' ? 'badge-yellow' :
                                                         surat.status === 'ditolak' ? 'badge-red' :
                                                         surat.status === 'selesai' ? 'badge-green' :
                                                         surat.status === 'proses' ? 'badge-blue' : 'badge-gray'"
                                            x-text="surat.status_label || surat.status"></span>
                                    </td>
                                    <td>
                                        <span
                                            :class="surat.sla_status === 'terlambat' ? 'text-red-500' : 'text-emerald-500'"
                                            class="text-[10px] font-bold flex items-center gap-1">
                                            <i class="bi"
                                                :class="surat.sla_status === 'terlambat' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill'"></i>
                                            <span x-text="surat.sla_status === 'terlambat' ? 'TERLAMBAT' : 'AKTIF'"></span>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a :href="'/Admin/Surat/' + surat.uuid"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="bi bi-arrow-right-short text-xl"></i>
                                        </a>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- CHART --}}
            <div class="card lg:col-span-2 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="bi bi-graph-up text-blue-500"></i> Statistik Perkembangan
                        </h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Analisis 6 bulan terakhir</p>
                    </div>
                </div>
                <div class="w-full relative" style="height: 300px; width: 100%;">
                    <canvas id="mixedChart"></canvas>
                </div>
            </div>

            {{-- REKAP JENIS --}}
            <div class="card shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="bi bi-pie-chart text-purple-500"></i> Rekap Jenis
                        </h2>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($rekapJenis as $jenis => $jumlah)
                        <div
                            class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span
                                    class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ \App\Models\Surat::JENIS_LABEL[$jenis] ?? $jenis }}</span>
                            </div>
                            <span
                                class="text-xs font-bold text-slate-900 dark:text-white px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md">{{ $jumlah }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 text-center py-10">Data kosong</p>
                    @endforelse
                </div>
            </div>

            {{-- SURAT TERBARU --}}
            <div class="card lg:col-span-2 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="bi bi-clock-history text-amber-500"></i> Aktivitas Terakhir
                        </h2>
                    </div>
                </div>
                <div class="space-y-4">
                    @forelse($suratTerbaru as $surat)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 group-hover:text-blue-600 transition-colors">
                                    <i class="bi bi-file-earmark-text text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4
                                        class="text-xs font-bold text-slate-800 dark:text-white truncate max-w-[200px] lg:max-w-[400px]">
                                        {{ $surat->judul }}</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">{{ $surat->user?->name ?? '—' }} ·
                                        {{ $surat->created_at?->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($surat->status === 'selesai')
                                    <span class="badge badge-green !text-[9px]">Selesai</span>
                                @elseif($surat->status === 'ditolak')
                                    <span class="badge badge-red !text-[9px]">Ditolak</span>
                                @elseif($surat->status === 'revisi' || $surat->status === 'revisi_admin')
                                    <span class="badge badge-yellow !text-[9px]">Revisi</span>
                                @else
                                    <span class="badge badge-blue !text-[9px]">Proses</span>
                                @endif
                                <a href="{{ route('admin.surat.show', $surat) }}"
                                    class="p-1.5 text-slate-400 hover:text-blue-500 transition-colors">
                                    <i class="bi bi-box-arrow-in-up-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 text-center py-10">Data kosong</p>
                    @endforelse
                </div>
            </div>

            {{-- PEMROSES --}}
            <div class="card shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="bi bi-people text-emerald-500"></i> Pengolah Teraktif
                        </h2>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($suratDenganPengolah->take(5) as $surat)
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold">
                                {{ strtoupper(substr($surat->tahapans->last()?->diprosesByUser?->name ?? '?', 0, 2)) }}
                            </div>
                            <div class="flex-1">
                                <div class="text-[11px] font-bold text-slate-800 dark:text-white">
                                    {{ $surat->tahapans->last()?->diprosesByUser?->name ?? 'Sistem' }}</div>
                                <div class="text-[9px] text-slate-500">
                                    {{ $surat->tahapans->last()?->diprosesByUser?->getRoleLabel() ?? 'Admin' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        function initDashboardChart() {
            if (typeof Chart === 'undefined') {
                console.error("Chart.js failed to load. Retrying in 1s...");
                setTimeout(initDashboardChart, 1000);
                return;
            }

            const canvas = document.getElementById('mixedChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartMonths),
                    datasets: [
                        {
                            label: 'Surat Selesai',
                            type: 'line',
                            data: @json($chartSelesai),
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'Surat Masuk',
                            data: @json($chartMasuk),
                            backgroundColor: isDark ? 'rgba(59, 130, 246, 0.4)' : 'rgba(59, 130, 246, 0.7)',
                            borderRadius: 6,
                            barThickness: 30,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                color: textColor,
                                font: { size: 11, weight: '600' }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { color: textColor, font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // Jalankan init chart
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDashboardChart);
        } else {
            initDashboardChart();
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardData', () => ({
                stats: {
                    totalBulanIni: {{ $totalBulanIni ?? 0 }},
                    totalSelesai: {{ $totalSelesai ?? 0 }},
                    totalProses: {{ $totalProses ?? 0 }},
                    totalTerlambat: {{ $totalTerlambat ?? 0 }},
                },
                antrian: {
                    items: @json($antrian ?? []),
                    count: {{ $antrianCount ?? 0 }},
                },
                connecting: false,
                isFetching: false,
                pollingInterval: 30000,

                init() {
                    console.log("Dashboard Alpine initialized");
                    this.startPolling();
                },

                startPolling() {
                    setInterval(() => this.updateData(), this.pollingInterval);
                },

                updateData() {
                    if (this.isFetching || document.hidden) return;
                    this.isFetching = true;
                    this.connecting = true;

                    const url = new URL('{{ route("admin.dashboard.liveData") }}', window.location.origin);
                    url.searchParams.append('bulan', '{{ $bulanSelected }}');
                    url.searchParams.append('tahun', '{{ $tahunSelected }}');

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(r => {
                            if (!r.ok) throw new Error("Network response was not ok");
                            return r.json();
                        })
                        .then(data => {
                            if (data && data.stats) {
                                this.stats = data.stats;
                                this.antrian.items = data.antrian?.items || [];
                                this.antrian.count = data.antrian?.count || 0;
                            }
                        })
                        .catch(err => console.error("Error fetching live data:", err))
                        .finally(() => {
                            this.isFetching = false;
                            this.connecting = false;
                        });
                },

                formatDate(date) {
                    if (!date) return '';
                    return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                }
            }));
        });
    </script>
@endpush