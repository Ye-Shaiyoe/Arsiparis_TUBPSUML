
@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 style="font-size:1.8rem; font-weight:800; color:var(--text-primary); margin:0;">Dashboard Overview</h1>
            <p style="font-size:13px; color:var(--text-secondary); margin:4px 0 0 0;">Monitoring aktivitas persuratan {{ \Carbon\Carbon::create()->month($bulanSelected)->translatedFormat('F') }} {{ $tahunSelected }}</p>
        </div>
        <form action="{{ route('admin.dashboard') }}" method="GET" style="display:flex; gap:10px; align-items:center; background:var(--bg-secondary); padding:8px 12px; border-radius:12px; border:1px solid var(--border-color);">
            <div style="font-size:12px; font-weight:600; color:var(--text-secondary); margin-right:4px;">FILTER:</div>
            <select name="bulan" class="form-select" onchange="this.form.submit()" style="width:140px; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); font-size:13px; padding:5px 10px;">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $bulanSelected == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
            <select name="tahun" class="form-select" onchange="this.form.submit()" style="width:110px; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); font-size:13px; padding:5px 10px;">
                @php $startYear = 2024; $currentYear = date('Y'); @endphp
                @for($y = $currentYear; $y >= $startYear; $y--)
                    <option value="{{ $y }}" {{ $tahunSelected == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    <div x-data="dashboardData()" x-init="initDashboard()" style="position:relative;">

        {{-- LOADING INDICATOR --}}
        <div x-show="connecting"
            style="position:fixed; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg, #3b82f6, #8b5cf6); animation:pulse 1.5s infinite; z-index:9999;">
        </div>

        {{-- STAT CARDS --}}

        <div class="stat-grid">
            <div class="stat-card blue">
                <div class="stat-label">Total Surat</div>
                <div class="stat-value" x-text="stats.totalBulanIni">{{ $totalBulanIni }}</div>
                <div class="stat-sub">{{ \Carbon\Carbon::create()->month($bulanSelected)->translatedFormat('F') }} {{ $tahunSelected }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Selesai</div>
                <div class="stat-value" x-text="stats.totalSelesai">{{ $totalSelesai }}</div>
                <div class="stat-sub">Sudah diarsipkan</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-label">Sedang Proses</div>
                <div class="stat-value" x-text="stats.totalProses">{{ $totalProses }}</div>
                <div class="stat-sub">Menunggu tindak lanjut</div>
            </div>
            <div class="stat-card red">
                <div class="stat-label">Melewati SLA</div>
                <div class="stat-value" x-text="stats.totalTerlambat">{{ $totalTerlambat }}</div>
                <div class="stat-sub">Harus segera ditangani</div>
            </div>
        </div>

        <div class="dashboard-grid">

            {{-- ANTRIAN VERIFIKASI --}}
            <div class="card" style="grid-column:1/-1;">
                <div class="section-header">
                    <div>
                        <h2>📥 Antrian Menunggu Aksi <span x-show="antrian.count > 0"
                                style="font-size:14px; color:#ef4444; margin-left:8px;"
                                x-text="'(' + antrian.count + ')'"></span></h2>
                        <small style="color:var(--text-secondary);">Surat yang perlu diproses sekarang</small>
                    </div>
                    <a href="{{ route('admin.surat.index') }}" class="btn btn-sm">Lihat Semua →</a>
                </div>

                <template x-if="antrian.items && antrian.items.length === 0">
                    <div style="text-align:center; padding:32px; color:var(--text-secondary); font-size:13px;">
                        ✅ Tidak ada antrian saat ini
                    </div>
                </template>

                <template x-if="antrian.items && antrian.items.length > 0">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Judul Surat</th>
                                    <th>Pengusul</th>
                                    <th>Jenis</th>
                                    <th>Sifat</th>
                                    <th>Tahap Sekarang</th>
                                    <th>Status</th>
                                    <th>SLA</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="surat in antrian.items" :key="surat.id">
                                    <tr>
                                        <td>
                                            <div style="font-weight:500; color:var(--text-primary); max-width:220px;"
                                                x-text="surat.judul"></div>
                                            <div style="font-size:11px; color:var(--text-secondary); margin-top:2px;"
                                                x-text="formatDate(surat.created_at)"></div>
                                        </td>
                                        <td>
                                            <div style="font-size:13px; color:var(--text-primary);" x-text="surat.user ? surat.user.name : '—'"></div>
                                        </td>
                                        <td><span class="badge badge-purple" x-text="surat.jenis"></span></td>
                                        <td><span class="badge"
                                                :class="surat.sifat === 'segera' ? 'badge-red' : 'badge-gray'"
                                                x-text="surat.sifat || 'Biasa'"></span></td>

                                        <td>
                                            <div style="font-size:12px; font-weight:500; color:#3b82f6;"
                                                x-text="'Tahap ' + surat.tahap_sekarang + '/10'"></div>
                                        </td>
                                        <td>
                                            <span class="badge"
                                                :class="surat.status === 'revisi' ? 'badge-yellow' : 
                                                         surat.status === 'revisi_admin' ? 'badge-yellow' :
                                                         surat.status === 'ditolak' ? 'badge-red' :
                                                         surat.status === 'selesai' ? 'badge-green' :
                                                         surat.status === 'proses' ? 'badge-blue' : 'badge-gray'"
                                                x-text="surat.status === 'revisi' ? '📝 Revisi' :
                                                         surat.status === 'revisi_admin' ? 'Admin Revisi' :
                                                         surat.status === 'ditolak' ? '❌ Ditolak' : 
                                                         surat.status === 'selesai' ? '✅ Selesai' :
                                                         surat.status === 'proses' ? '⏳ Proses' : 'Draft'"></span>
                                        </td>
                                        <td><span :class="surat.sla_status === 'terlambat' ? 'badge-red' : 'badge-green'"
                                                class="badge"
                                                x-text="surat.sla_status === 'terlambat' ? '⚠ Terlambat' : '⏱ Aktif'"></span>
                                        </td>
                                        <td><a :href="'/Admin/Surat/' + surat.uuid" class="btn btn-sm btn-primary">Proses
                                                →</a></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>

            {{-- REKAP PER JENIS --}}
            <div class="card">
                <div class="section-header">
                    <h2>📊 Rekap Per Jenis</h2>
                    <small style="color:var(--text-secondary);">Periode {{ \Carbon\Carbon::create()->month($bulanSelected)->translatedFormat('M') }} {{ $tahunSelected }}</small>
                </div>
                @forelse($rekapJenis as $jenis => $jumlah)
                    <div
                        style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border-color);">
                        <span style="font-size:13px; color:var(--text-primary);">
                            {{ \App\Models\Surat::JENIS_LABEL[$jenis] ?? $jenis }}
                        </span>
                        <span class="badge badge-blue">{{ $jumlah }}</span>
                    </div>
                @empty
                    <div style="text-align:center; padding:24px; color:var(--text-secondary); font-size:13px;">
                        Belum ada surat bulan ini
                    </div>
                @endforelse
            </div>

            {{-- SURAT TERBARU --}}
            <div class="card">
                <div class="section-header">
                    <h2>🕐 Surat Terbaru</h2>
                </div>
                @forelse($suratTerbaru as $surat)
                    <div
                        style="display:flex; align-items:flex-start; gap:10px; padding:8px 0; border-bottom:1px solid var(--border-color);">
                        <div style="flex:1; min-width:0;">
                            <div
                                style="font-size:13px; font-weight:500; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $surat->judul }}
                            </div>
                            <div style="font-size:11px; color:var(--text-secondary); margin-top:2px;">
                                {{ $surat->user?->name ?? '—' }} · {{ $surat->created_at?->diffForHumans() ?? 'Tanpa tanggal' }}
                            </div>
                        </div>
                        @if($surat->status === 'selesai')
                            <span class="badge badge-green">Selesai</span>
                        @elseif($surat->status === 'ditolak')
                            <span class="badge badge-red">Ditolak</span>
                        @elseif($surat->status === 'revisi' || $surat->status === 'revisi_admin')
                            <span class="badge badge-yellow">Revisi</span>
                        @else
                            <span class="badge badge-amber">Proses</span>
                        @endif
                    </div>
                @empty
                    <div style="text-align:center; padding:24px; color:var(--text-secondary); font-size:13px;">
                        Belum ada data surat
                    </div>
                @endforelse
            </div>

            {{-- RIWAYAT PEMROSESAN SURAT (BULAN INI) --}}
            <div class="card" style="grid-column:1/-1;">
                <div class="section-header">
                    <div>
                        <h2>👥 Riwayat Pemrosesan Surat</h2>
                        <small style="color:var(--text-secondary);">Aktivitas pengolahan periode {{ \Carbon\Carbon::create()->month($bulanSelected)->translatedFormat('F') }} {{ $tahunSelected }}</small>
                    </div>
                </div>

                @if($suratDenganPengolah->isEmpty())
                    <div style="text-align:center; padding:32px; color:var(--text-secondary); font-size:13px;">
                        Belum ada data pemrosesan bulan ini
                    </div>
                @else
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Judul Surat</th>
                                    <th>Pengusul</th>
                                    <th>Status</th>
                                    <th>Admin Pengolah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suratDenganPengolah as $surat)
                                    <tr>
                                        <td>
                                            <div style="font-weight:500; color:var(--text-primary); max-width:200px;">
                                                {{ \Illuminate\Support\Str::limit($surat->judul, 40) }}
                                            </div>
                                            <div style="font-size:11px; color:var(--text-secondary); margin-top:2px;">
                                                {{ $surat->created_at?->format('d M Y') ?? 'Tanpa tanggal' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size:13px; color:var(--text-primary);">{{ $surat->user?->name ?? '—' }}</div>
                                        </td>
                                        <td>
                                            @if($surat->status === 'selesai')
                                                <span class="badge badge-green">✓ Selesai</span>
                                            @elseif($surat->status === 'ditolak')
                                                <span class="badge badge-red">✗ Ditolak</span>
                                            @elseif($surat->status === 'revisi' || $surat->status === 'revisi_admin')
                                                <span class="badge badge-yellow">📝 Revisi</span>
                                            @else
                                                <span class="badge badge-amber">● Proses</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                                @forelse($surat->tahapans as $tahapan)
                                                    <span class="badge badge-blue"
                                                        title="Tahap {{ $tahapan->tahap }}: {{ $tahapan->nama_tahap }}"
                                                        style="cursor:help; font-size:11px; padding:3px 6px;">
                                                        {{ $tahapan->diprosesByUser?->getRoleLabel() ?? '—' }}
                                                    </span>
                                                @empty
                                                    <span style="font-size:13px; color:var(--text-secondary);">Belum ada yang proses</span>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

    </div> {{-- End of Alpine x-data div --}}

@endsection

@push('scripts')
    <script>
        function dashboardData() {
            return {
                stats: {
                    totalBulanIni: {{ $totalBulanIni }},
                    totalSelesai: {{ $totalSelesai }},
                    totalProses: {{ $totalProses }},
                    totalTerlambat: {{ $totalTerlambat }},
                },
                antrian: {
                    items: {!! json_encode($antrian) !!},
                    count: {{ $antrianCount }},
                },
                connecting: false,
                dashboard: null,

                initDashboard() {
                    this.connecting = true;
                    this.dashboard = window.initRealtimeDashboard('admin');

                    if (this.dashboard) {
                        this.dashboard.on('statsUpdate', (data) => {
                            this.stats = {
                                totalBulanIni: data.totalBulanIni,
                                totalSelesai: data.totalSelesai,
                                totalProses: data.totalProses,
                                totalTerlambat: data.totalTerlambat,
                            };
                            this.connecting = false;
                        });

                        this.dashboard.on('antrianUpdate', (data) => {
                            this.antrian.items = data.items || [];
                            this.antrian.count = data.count || 0;
                        });

                        this.dashboard.on('error', (error) => {
                            console.error('Dashboard error:', error);
                        });
                    }
                },

                formatDate(date) {
                    if (!date) return '';
                    return new Date(date).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                }
            }
        }
    </script>
@endpush