@extends('layouts.admin')
@section('title', 'Laporan Rekap Bulanan')

@section('content')
<style>
    /* Premium Stat Cards (Same as Dashboard) */
    .stat-card-new {
        position: relative;
        padding: 24px;
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
    }
    .stat-card-new:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15); }
    .stat-card-new.blue { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white; }
    .stat-card-new.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .stat-card-new.amber { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .stat-card-new.red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
    
    .stat-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-value-new { font-size: 28px; font-weight: 900; line-height: 1; letter-spacing: -1px; }
    .stat-label-new { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; margin-bottom: 4px; }
    .stat-sub-new { font-size: 10px; font-weight: 600; opacity: 0.6; }

    .wave-bg {
        position: absolute;
        right: -20px;
        bottom: -20px;
        opacity: 0.15;
        width: 150px;
        pointer-events: none;
    }

    /* Table Styles */
    .table-modern thead th {
        background: #f8fafc;
        padding: 16px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        border-bottom: 2px solid #f1f5f9;
    }
    .dark .table-modern thead th {
        background: #1e293b;
        color: #94a3b8;
        border-bottom: 2px solid #334155;
    }
    .table-modern tbody td {
        padding: 16px;
        vertical-align: middle;
    }
</style>

<div class="dashboard-header flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Laporan Rekapitulasi</h1>
            <div class="flex items-center gap-2 px-3 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-full text-[10px] font-black tracking-widest uppercase">
                <i class="bi bi-file-earmark-bar-graph"></i> Monthly Report
            </div>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-semibold opacity-80">
            Arsip data surat bulan 
            <span class="text-blue-600 dark:text-blue-400 font-bold">{{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}</span>
        </p>
    </div>
    
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.laporan.exportExcel', request()->query()) }}"
           class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black flex items-center gap-2 transition-all shadow-lg shadow-emerald-600/20">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
        <a href="{{ route('admin.laporan.export', request()->query()) }}"
           class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-black flex items-center gap-2 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
            <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
        </a>
    </div>
</div>

{{-- FILTER SECTION --}}
<div class="card !p-4 mb-8 border-slate-200 dark:border-slate-800 shadow-sm">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[150px]">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block ml-1">Periode Bulan</label>
            <div class="relative">
                <i class="bi bi-calendar-event absolute left-3 top-1/2 -translate-y-1/2 text-blue-500 text-sm"></i>
                <select name="bulan" class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(now()->year, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex-1 min-w-[120px]">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block ml-1">Tahun</label>
            <div class="relative">
                <i class="bi bi-calendar-check absolute left-3 top-1/2 -translate-y-1/2 text-blue-500 text-sm"></i>
                <select name="tahun" class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                    @foreach(range(now()->year, now()->year - 3) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex-1 min-w-[180px]">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block ml-1">Kategori Surat</label>
            <div class="relative">
                <i class="bi bi-tags absolute left-3 top-1/2 -translate-y-1/2 text-blue-500 text-sm"></i>
                <select name="jenis" class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Surat::JENIS_LABEL as $val => $label)
                        <option value="{{ $val }}" {{ $jenis === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black flex items-center gap-2 transition-all shadow-lg shadow-blue-600/20">
            <i class="bi bi-funnel"></i> Terapkan Filter
        </button>
    </form>
</div>

{{-- RINGKASAN STATS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card-new blue">
        <svg class="wave-bg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M47.7,-62.5C61.3,-54.3,71.4,-39.8,76.1,-24.1C80.8,-8.4,80.1,8.4,74.5,23.5C68.9,38.6,58.4,52,44.9,61C31.4,70,14.9,74.6,-1.7,77C-18.4,79.4,-36.8,79.6,-50.8,71C-64.8,62.4,-74.5,45,-77.9,27.7C-81.4,10.4,-78.7,-6.7,-72.1,-21.5C-65.4,-36.3,-54.9,-48.8,-42.2,-57.4C-29.5,-66.1,-14.8,-70.8,1.4,-72.8C17.6,-74.7,34.1,-70.7,47.7,-62.5Z" transform="translate(100 100)" /></svg>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-files"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Total Surat</div>
            <div class="stat-value-new">{{ $ringkasan['total'] }}</div>
            <div class="stat-sub-new">Volume pengajuan</div>
        </div>
    </div>
    <div class="stat-card-new green">
        <svg class="wave-bg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M47.7,-62.5C61.3,-54.3,71.4,-39.8,76.1,-24.1C80.8,-8.4,80.1,8.4,74.5,23.5C68.9,38.6,58.4,52,44.9,61C31.4,70,14.9,74.6,-1.7,77C-18.4,79.4,-36.8,79.6,-50.8,71C-64.8,62.4,-74.5,45,-77.9,27.7C-81.4,10.4,-78.7,-6.7,-72.1,-21.5C-65.4,-36.3,-54.9,-48.8,-42.2,-57.4C-29.5,-66.1,-14.8,-70.8,1.4,-72.8C17.6,-74.7,34.1,-70.7,47.7,-62.5Z" transform="translate(100 100)" /></svg>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-check2-circle"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Selesai</div>
            <div class="stat-value-new">{{ $ringkasan['selesai'] }}</div>
            <div class="stat-sub-new">Terarsipkan sistem</div>
        </div>
    </div>
    <div class="stat-card-new amber">
        <svg class="wave-bg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M47.7,-62.5C61.3,-54.3,71.4,-39.8,76.1,-24.1C80.8,-8.4,80.1,8.4,74.5,23.5C68.9,38.6,58.4,52,44.9,61C31.4,70,14.9,74.6,-1.7,77C-18.4,79.4,-36.8,79.6,-50.8,71C-64.8,62.4,-74.5,45,-77.9,27.7C-81.4,10.4,-78.7,-6.7,-72.1,-21.5C-65.4,-36.3,-54.9,-48.8,-42.2,-57.4C-29.5,-66.1,-14.8,-70.8,1.4,-72.8C17.6,-74.7,34.1,-70.7,47.7,-62.5Z" transform="translate(100 100)" /></svg>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-arrow-repeat"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Sedang Proses</div>
            <div class="stat-value-new">{{ $ringkasan['proses'] }}</div>
            <div class="stat-sub-new">Menunggu verifikasi</div>
        </div>
    </div>
    <div class="stat-card-new red">
        <svg class="wave-bg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M47.7,-62.5C61.3,-54.3,71.4,-39.8,76.1,-24.1C80.8,-8.4,80.1,8.4,74.5,23.5C68.9,38.6,58.4,52,44.9,61C31.4,70,14.9,74.6,-1.7,77C-18.4,79.4,-36.8,79.6,-50.8,71C-64.8,62.4,-74.5,45,-77.9,27.7C-81.4,10.4,-78.7,-6.7,-72.1,-21.5C-65.4,-36.3,-54.9,-48.8,-42.2,-57.4C-29.5,-66.1,-14.8,-70.8,1.4,-72.8C17.6,-74.7,34.1,-70.7,47.7,-62.5Z" transform="translate(100 100)" /></svg>
        <div class="stat-icon-box shadow-sm"><i class="bi bi-clock-history"></i></div>
        <div class="stat-info">
            <div class="stat-label-new">Overdue SLA</div>
            <div class="stat-value-new">{{ $ringkasan['sla_telat'] }}</div>
            <div class="stat-sub-new">Melebihi target waktu</div>
        </div>
    </div>
</div>

{{-- DATA TABLE SECTION --}}
<div class="card !p-0 overflow-hidden border-slate-200 dark:border-slate-800 shadow-sm">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-blue-500 rounded-full"></div>
            <div>
                <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Daftar Rekapitulasi Surat</h2>
                <p class="text-[11px] text-slate-500 font-semibold opacity-70">Menampilkan {{ $surats->count() }} dokumen ditemukan.</p>
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table-modern w-full">
            <thead>
                <tr>
                    <th>Info Surat</th>
                    <th>Timeline Pemrosesan</th>
                    <th>Nomor & Tujuan</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($surats as $surat)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 shrink-0">
                                    <i class="bi bi-file-earmark-text text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-black text-slate-800 dark:text-white truncate max-w-[200px]">{{ $surat->judul }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="badge badge-purple !text-[9px] px-1.5 py-0.5">{{ $surat->jenis_label }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $surat->user?->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 text-[10px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    <span class="text-slate-400 uppercase w-12">Ajukan:</span>
                                    <span class="text-slate-700 dark:text-slate-300">{{ $surat->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-[10px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-slate-400 uppercase w-12">Selesai:</span>
                                    <span class="text-slate-700 dark:text-slate-300">{{ $surat->disetujui_pada ? $surat->disetujui_pada->format('d/m/Y H:i') : '—' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-[10px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    <span class="text-slate-400 uppercase w-12">Deadline:</span>
                                    <span class="text-slate-700 dark:text-slate-300">{{ $surat->deadline_sla ? $surat->deadline_sla->format('d/m/Y H:i') : '—' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-[11px] font-black text-slate-700 dark:text-slate-200 truncate max-w-[150px]">{{ $surat->nomor_surat ?? 'BELUM ADA NOMOR' }}</div>
                            <div class="text-[10px] text-slate-400 font-semibold mt-1 truncate max-w-[150px]"><i class="bi bi-geo-alt mr-1"></i>{{ $surat->tujuan }}</div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase">{{ $surat->tahap_sekarang }}/10</span>
                                <div class="flex-1 h-1 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $surat->proses_persen }}%"></div>
                                </div>
                            </div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $surat->nama_tahap }}</div>
                        </td>
                        <td>
                            <div class="flex flex-col gap-1.5">
                                @if($surat->status === 'selesai')
                                    <span class="badge badge-green !text-[9px] font-black uppercase text-center">Selesai</span>
                                @elseif($surat->status === 'ditolak')
                                    <span class="badge badge-red !text-[9px] font-black uppercase text-center">Ditolak</span>
                                @elseif($surat->status === 'revisi')
                                    <span class="badge badge-amber !text-[9px] font-black uppercase text-center">Revisi</span>
                                @elseif($surat->status === 'revisi_admin')
                                    <span class="badge badge-purple !text-[9px] font-black uppercase text-center">Revisi Admin</span>
                                @else
                                    <span class="badge badge-blue !text-[9px] font-black uppercase text-center">Proses</span>
                                @endif

                                @if($surat->status === 'selesai')
                                    <span class="text-[9px] font-black text-emerald-500 text-center flex items-center justify-center gap-1"><i class="bi bi-check-circle-fill"></i> SLA OK</span>
                                @elseif($surat->sla_status === 'terlambat')
                                    <span class="text-[9px] font-black text-red-500 text-center flex items-center justify-center gap-1"><i class="bi bi-exclamation-circle-fill"></i> TERLAMBAT</span>
                                @else
                                    <span class="text-[9px] font-black text-blue-500 text-center flex items-center justify-center gap-1"><i class="bi bi-clock-fill"></i> ON TIME</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.surat.show', $surat) }}"
                               class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i class="bi bi-inbox text-5xl mb-4"></i>
                                <p class="text-sm font-black uppercase tracking-widest">Tidak ada data surat ditemukan</p>
                                <p class="text-[11px] font-bold mt-1">Coba sesuaikan filter pencarian Anda</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection