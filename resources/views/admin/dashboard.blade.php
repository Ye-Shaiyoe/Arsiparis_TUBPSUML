@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
    <style>
        /* Modernized Stat Cards with Wave Background */
        .stat-card-new {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .stat-card-new::before {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 80px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 150' preserveAspectRatio='none'%3E%3Cpath d='M0 120 C 50 120, 80 20, 130 80 C 180 140, 210 20, 260 80 C 310 140, 340 120, 400 120' fill='none' stroke='white' stroke-width='4' stroke-linecap='round' opacity='0.2'/%3E%3C/svg%3E");
            background-size: 100% 100%;
            background-position: bottom;
            background-repeat: no-repeat;
            pointer-events: none;
            transition: transform 0.4s ease;
        }

        .stat-card-new:hover::before {
            transform: scaleY(1.2) translateY(-5px);
        }

        .stat-card-new:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stat-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
            z-index: 2;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Gradient Variants */
        .stat-card-new.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; }
        .stat-card-new.green { background: linear-gradient(135deg, #10b981, #047857); border: none; }
        .stat-card-new.amber { background: linear-gradient(135deg, #f59e0b, #b45309); border: none; }
        .stat-card-new.red { background: linear-gradient(135deg, #ef4444, #b91c1c); border: none; }

        .stat-info {
            flex: 1;
            z-index: 2;
        }

        .stat-label-new {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.85);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value-new {
            font-size: 32px;
            font-weight: 800;
            color: white;
            line-height: 1;
            margin: 6px 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-sub-new {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        /* Table Improvements */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background: var(--bg-tertiary);
            padding: 14px 16px;
            font-size: 10px;
            font-weight: 800;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 1px solid var(--border-color);
            opacity: 0.8;
        }

        .table-custom tbody td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 13px;
            transition: background 0.2s ease;
        }

        .table-custom tbody tr:hover td {
            background: rgba(59, 130, 246, 0.02);
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        /* Live Indicator */
        .pulse-live {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }

        .pulse-live::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid #10b981;
            animation: pulseSimple 2s infinite;
        }

        @keyframes pulseSimple {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        .filter-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .dashboard-header {
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }
    </style>

    {{-- HEADER & FILTER --}}
    <div class="dashboard-header flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard Overview</h1>
                <div x-data="{ live: true }" x-show="live"
                    class="flex items-center gap-2 px-3 py-1 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded-full text-[10px] font-black tracking-widest">
                    <span class="pulse-live"></span> LIVE
                </div>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-semibold opacity-80">Monitoring aktivitas persuratan secara real-time.</p>
        </div>

        <form action="{{ route('admin.dashboard') }}" method="GET" class="filter-section">
            <div class="flex items-center gap-2 px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg">
                <i class="bi bi-calendar3 text-blue-500 text-xs"></i>
                <select name="bulan"
                    class="bg-transparent border-none text-xs font-bold focus:ring-0 cursor-pointer text-slate-700 dark:text-slate-200"
                    onchange="this.form.submit()">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulanSelected == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <div class="w-px h-3 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                <select name="tahun"
                    class="bg-transparent border-none text-xs font-bold focus:ring-0 cursor-pointer text-slate-700 dark:text-slate-200"
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
                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/80 dark:bg-slate-800/50">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 bg-blue-500 rounded-full"></div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                Antrian Menunggu Aksi
                                <span x-show="antrian.count > 0"
                                    class="px-2 py-0.5 bg-red-500 text-white text-[10px] font-black rounded-full"
                                    x-text="antrian.count"></span>
                            </h2>
                            <p class="text-[11px] text-slate-500 mt-0.5 font-semibold opacity-70">Segera proses surat-surat di bawah ini.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.surat.index') }}"
                        class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-[11px] font-black text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                        LIHAT SEMUA <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>

                <div class="table-wrap">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th class="!pl-8">Judul & Tanggal</th>
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
                                    <td colspan="7" class="text-center py-16">
                                        <div class="flex flex-col items-center opacity-30">
                                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                                <i class="bi bi-check2-all text-3xl text-emerald-500"></i>
                                            </div>
                                            <p class="text-xs font-black uppercase tracking-[2px]">Semua surat telah diproses</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="surat in antrian.items" :key="surat.uuid || surat.id">
                                <tr class="group">
                                    <td class="!pl-8">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-blue-600 transition-colors"
                                            x-text="surat.judul"></div>
                                        <div class="text-[10px] text-slate-500 mt-1 font-semibold flex items-center gap-1">
                                            <i class="bi bi-calendar-event"></i> 
                                            <span x-text="formatDate(surat.created_at)"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-xs font-bold text-slate-700 dark:text-slate-300"
                                            x-text="surat.user ? surat.user.name : '—'"></div>
                                    </td>
                                    <td>
                                        <div class="flex gap-1">
                                            <span class="badge badge-purple !text-[9px] font-black uppercase tracking-wider" x-text="surat.jenis"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="text-[11px] font-black text-slate-900 dark:text-slate-200"
                                                x-text="'T' + surat.tahap_sekarang"></div>
                                            <div
                                                class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full max-w-[80px] overflow-hidden border border-slate-200/50 dark:border-slate-700/50">
                                                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600"
                                                    :style="'width:' + (surat.tahap_sekarang * 10) + '%'"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge !text-[10px] font-black uppercase tracking-wider" :class="surat.status === 'revisi' ? 'badge-yellow' : 
                                                         surat.status === 'revisi_admin' ? 'badge-yellow' :
                                                         surat.status === 'ditolak' ? 'badge-red' :
                                                         surat.status === 'selesai' ? 'badge-green' :
                                                         surat.status === 'proses' ? 'badge-blue' : 'badge-gray'"
                                            x-text="surat.status_label || surat.status"></span>
                                    </td>
                                    <td>
                                        <div :class="surat.sla_status === 'terlambat' ? 'text-red-500 bg-red-500/10' : 'text-emerald-500 bg-emerald-500/10'"
                                             class="text-[9px] font-black px-2 py-1 rounded-md inline-flex items-center gap-1 uppercase tracking-wider">
                                            <i class="bi" :class="surat.sla_status === 'terlambat' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill'"></i>
                                            <span x-text="surat.sla_status === 'terlambat' ? 'Overdue' : 'Active'"></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a :href="'/Admin/Surat/' + surat.uuid"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="bi bi-arrow-right-short text-2xl"></i>
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
            <div class="card lg:col-span-2 shadow-sm border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-6 px-2">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white flex items-center gap-2 uppercase tracking-wider">
                            <i class="bi bi-clock-history text-amber-500"></i> Aktivitas Terakhir
                        </h2>
                    </div>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($suratTerbaru as $surat)
                        <div class="flex items-center justify-between group py-4 px-2 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all rounded-xl">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                    <i class="bi bi-file-earmark-text text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4
                                        class="text-sm font-bold text-slate-800 dark:text-white truncate max-w-[180px] lg:max-w-[350px] group-hover:text-blue-600 transition-colors">
                                        {{ $surat->judul }}</h4>
                                    <p class="text-[10px] text-slate-500 mt-1 font-semibold flex items-center gap-2">
                                        <span class="text-blue-500">{{ $surat->user?->name ?? '—' }}</span> 
                                        <span class="opacity-30">•</span>
                                        <span>{{ $surat->created_at?->diffForHumans() }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($surat->status === 'selesai')
                                    <span class="badge badge-green !text-[9px] font-black uppercase tracking-wider px-2">Selesai</span>
                                @elseif($surat->status === 'ditolak')
                                    <span class="badge badge-red !text-[9px] font-black uppercase tracking-wider px-2">Ditolak</span>
                                @elseif($surat->status === 'revisi' || $surat->status === 'revisi_admin')
                                    <span class="badge badge-yellow !text-[9px] font-black uppercase tracking-wider px-2">Revisi</span>
                                @else
                                    <span class="badge badge-blue !text-[9px] font-black uppercase tracking-wider px-2">Proses</span>
                                @endif
                                <a href="{{ route('admin.surat.show', $surat) }}"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 transition-all">
                                    <i class="bi bi-box-arrow-in-up-right text-sm"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 opacity-40">
                            <i class="bi bi-inbox text-4xl mb-2"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">Belum ada aktivitas</p>
                        </div>
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

        {{-- ACTIVITY HEATMAP --}}
        <div class="mb-6">
            <x-activity-heatmap :data="$heatmapData" :selected-year="$heatmapYear" title="Aktivitas Pemrosesan Surat" />
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
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.45,
                            pointRadius: 4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Surat Terlambat (SLA)',
                            type: 'line',
                            data: @json($chartTerlambat),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.45,
                            pointRadius: 4,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Surat Masuk',
                            data: @json($chartMasuk),
                            backgroundColor: isDark ? 'rgba(59, 130, 246, 0.2)' : 'rgba(59, 130, 246, 0.4)',
                            hoverBackgroundColor: '#3b82f6',
                            borderRadius: 8,
                            barThickness: 24,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: textColor,
                                font: { size: 11, weight: '700' }
                            }
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1e293b' : '#ffffff',
                            titleColor: isDark ? '#f1f5f9' : '#1e293b',
                            bodyColor: isDark ? '#cbd5e1' : '#475569',
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { 
                                color: textColor, 
                                font: { size: 10, weight: '600' },
                                padding: 10
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                color: textColor, 
                                font: { size: 10, weight: '600' },
                                padding: 10
                            }
                        }
                    }
                }
            });
        }

        // Jalankan init chart
        document.addEventListener('turbo:load', initDashboardChart);
        if (document.readyState !== 'loading') initDashboardChart();
        else document.addEventListener('DOMContentLoaded', initDashboardChart);

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

                init() {
                    console.log("Dashboard Alpine initialized");
                    // AJAX Polling removed as per user request
                },

                formatDate(date) {
                    if (!date) return '';
                    return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                }
            }));
        });
    </script>
@endpush