@extends('layouts.admin')
@section('title', 'SLA Persurat')

@section('content')
<div class="dashboard-header flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-6">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">SLA Persurat</h1>
            <div class="flex items-center gap-2 px-3 py-1 bg-amber-500/10 text-amber-500 border border-amber-500/20 rounded-full text-[10px] font-black tracking-widest uppercase">
                <i class="bi bi-speedometer2"></i> Bottleneck Analytics
            </div>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-semibold opacity-80">
            Monitoring durasi pemrosesan tiap tahap surat & mengidentifikasi petugas/pemroses yang mengendap paling lama.
        </p>
    </div>

    {{-- Export Button --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.sla.export', request()->all()) }}" data-turbo="false"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition-all">
            <i class="bi bi-file-earmark-excel-fill text-base"></i> Export Excel SLA
        </a>
    </div>
</div>

{{-- METRIC CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="stat-card border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between">
            <span class="stat-label">Total Surat</span>
            <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 flex items-center justify-center text-base font-bold">
                <i class="bi bi-files"></i>
            </div>
        </div>
        <div class="stat-value mt-2">{{ $totalSurat }}</div>
        <div class="stat-sub">Periode ini</div>
    </div>

    <div class="stat-card red border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between">
            <span class="stat-label">Terlambat SLA</span>
            <div class="w-8 h-8 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center text-base font-bold">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-value mt-2 text-red-500">{{ $suratTerlambat }}</div>
        <div class="stat-sub text-red-400 font-semibold">Memerlukan evaluasi</div>
    </div>

    <div class="stat-card green border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between">
            <span class="stat-label">Tepat Waktu</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-base font-bold">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="stat-value mt-2 text-emerald-500">{{ $suratTepat }}</div>
        <div class="stat-sub text-emerald-400 font-semibold">Selesai OK</div>
    </div>

    <div class="stat-card border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between">
            <span class="stat-label">Rata-rata Pemrosesan</span>
            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-base font-bold">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
        <div class="stat-value mt-2 text-indigo-500">{{ $avgDuration }} <span class="text-xs font-normal text-slate-400">jam</span></div>
        <div class="stat-sub">Durasi akumulasi</div>
    </div>

    <div class="stat-card amber border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between">
            <span class="stat-label">Pemroses Sering Lama</span>
            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center text-base font-bold">
                <i class="bi bi-person-exclamation"></i>
            </div>
        </div>
        <div class="stat-value mt-2 text-amber-500 text-sm truncate" title="{{ $topBottleneckPemroses }}">
            {{ Str::limit($topBottleneckPemroses, 16) }}
        </div>
        <div class="stat-sub text-amber-400 font-semibold">{{ $topBottleneckCount > 0 ? $topBottleneckCount . ' surat tersendat' : '—' }}</div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="card mb-6 border-slate-200 dark:border-slate-800 p-4">
    <form method="GET" action="{{ route('admin.sla.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3 align-items-end">
        {{-- Search --}}
        <div class="md:col-span-2">
            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Cari Surat / Pengusul</label>
            <div class="relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="No. surat, judul, pengusul..."
                       class="form-control text-xs pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-lg">
                <i class="bi bi-search absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
            </div>
        </div>

        {{-- Status SLA --}}
        <div>
            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Status SLA</label>
            <select name="status_sla" class="form-select text-xs py-2 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-lg">
                <option value="">Semua Status SLA</option>
                <option value="terlambat" {{ $statusSla === 'terlambat' ? 'selected' : '' }}>🔴 Terlambat SLA</option>
                <option value="ok" {{ $statusSla === 'ok' ? 'selected' : '' }}>🟢 Tepat Waktu</option>
                <option value="proses" {{ $statusSla === 'proses' ? 'selected' : '' }}>🔵 Dalam Proses</option>
            </select>
        </div>

        {{-- Jenis Surat --}}
        <div>
            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Jenis Surat</label>
            <select name="jenis" class="form-select text-xs py-2 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-lg">
                <option value="">Semua Jenis</option>
                @foreach(\App\Models\Surat::JENIS_LABEL as $key => $lbl)
                    <option value="{{ $key }}" {{ $jenis === $key ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>

        {{-- Bulan --}}
        <div>
            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Bulan</label>
            <select name="bulan" class="form-select text-xs py-2 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-lg">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ sprintf('%02d', $m) }}" {{ (int)$bulan === $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-sm btn-primary px-3 py-2 text-xs flex-1">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
            <a href="{{ route('admin.sla.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 text-xs">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

{{-- DATA TABLE --}}
<div class="card border-slate-200 dark:border-slate-800 p-0 overflow-hidden">
    <div class="table-wrap">
        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-[11px]">SURAT & PENGUSUL</th>
                    <th class="px-4 py-3 text-[11px]">TGL & DEADLINE SLA</th>
                    <th class="px-4 py-3 text-[11px]">TAHAP SEKARANG</th>
                    <th class="px-4 py-3 text-[11px]">PEMROSES AKTIF</th>
                    <th class="px-4 py-3 text-[11px]">TAHAP PALING LAMA (BOTTLENECK)</th>
                    <th class="px-4 py-3 text-[11px]">TOTAL DURASI</th>
                    <th class="px-4 py-3 text-[11px] text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($processedSurats as $surat)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        {{-- Surat & Pengusul --}}
                        <td class="px-4 py-3">
                            <div class="font-bold text-xs text-slate-800 dark:text-slate-200 line-clamp-1" title="{{ $surat->judul }}">
                                {{ $surat->judul }}
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] px-2 py-0.5 rounded bg-blue-500/10 text-blue-500 font-bold">
                                    {{ $surat->jenis_label }}
                                </span>
                                <span class="text-[11px] text-slate-400 font-medium">
                                    <i class="bi bi-person me-1"></i>{{ $surat->user?->name ?? '—' }}
                                </span>
                            </div>
                        </td>

                        {{-- Tgl & Deadline SLA --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $surat->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="text-[11px] font-bold mt-0.5" style="color: {{ $surat->sla_color }};">
                                {{ $surat->sla_icon }} {{ $surat->deadline_sla ? $surat->deadline_sla->format('d M Y, H:i') : '—' }}
                                @if($surat->sla_status === 'terlambat')
                                    <span class="text-[10px] text-red-500 font-black">({{ round($surat->sla_terlambat_jam, 1) }}j lewat)</span>
                                @endif
                            </div>
                        </td>

                        {{-- Tahap Sekarang --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                Tahap {{ $surat->tahap_sekarang }}/10
                            </div>
                            <div class="text-[11px] text-slate-400 font-medium line-clamp-1" title="{{ $surat->nama_tahap }}">
                                {{ $surat->nama_tahap }}
                            </div>
                        </td>

                        {{-- Pemroses Aktif --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                {{ $surat->current_pemroses_name }}
                            </div>
                            <div class="text-[10px] text-slate-400 font-semibold">
                                {{ $surat->current_role }}
                            </div>
                        </td>

                        {{-- Bottleneck (Tahap Paling Lama) --}}
                        <td class="px-4 py-3">
                            @if($surat->bottleneck_duration > 0)
                                <div class="p-2 rounded-lg border {{ $surat->bottleneck_duration >= 24 ? 'bg-red-500/5 border-red-500/20' : 'bg-amber-500/5 border-amber-500/20' }}">
                                    <div class="flex items-center justify-between text-xs font-black {{ $surat->bottleneck_duration >= 24 ? 'text-red-500' : 'text-amber-500' }}">
                                        <span><i class="bi bi-clock me-1"></i>{{ $surat->bottleneck_stage_name }}</span>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-white/10">
                                            {{ $surat->bottleneck_duration }} jam
                                        </span>
                                    </div>
                                    <div class="text-[11px] font-semibold text-slate-600 dark:text-slate-300 mt-1">
                                        <i class="bi bi-person-badge me-1"></i><strong>{{ $surat->bottleneck_pemroses_name }}</strong>
                                        <span class="text-[10px] text-slate-400">({{ $surat->bottleneck_role }})</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">Belum terdeteksi</span>
                            @endif
                        </td>

                        {{-- Total Durasi --}}
                        <td class="px-4 py-3 whitespace-nowrap font-bold text-xs text-slate-800 dark:text-slate-200">
                            {{ $surat->calculated_total_hours }} jam
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <a href="{{ route('admin.surat.show', $surat) }}"
                               class="btn btn-sm btn-outline-primary px-3 py-1.5 text-xs font-semibold rounded-lg inline-flex items-center gap-1">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 text-xs">
                            <i class="bi bi-inbox text-3xl block mb-2 opacity-50"></i>
                            Tidak ada data SLA surat yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
