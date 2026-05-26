@extends('layouts.admin')
@section('title', $title ?? 'Antrian Surat')

@section('content')

{{-- FILTER BAR --}}
<div class="card" style="margin-bottom:16px;">
    <form method="GET" action="{{ url()->current() }}" data-turbo="false"
          style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">

        <div style="flex:2; min-width:180px;">
            <label style="font-size:11px; color:var(--text-secondary); display:block; margin-bottom:4px;">Cari Judul</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul surat..."
                   style="width:100%; padding:7px 10px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); border-radius:7px; font-size:13px;">
        </div>

        <div style="flex:1; min-width:140px;">
            <label style="font-size:11px; color:var(--text-secondary); display:block; margin-bottom:4px;">Jenis Surat</label>
            <select name="jenis" style="width:100%; padding:7px 10px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); border-radius:7px; font-size:13px;">
                <option value="">Semua Jenis</option>
                @foreach(\App\Models\Surat::JENIS_LABEL as $val => $label)
                    <option value="{{ $val }}" {{ request('jenis') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex:1; min-width:100px;">
            <label style="font-size:11px; color:var(--text-secondary); display:block; margin-bottom:4px;">Tahun</label>
            <select name="tahun" style="width:100%; padding:7px 10px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); border-radius:7px; font-size:13px;">
                <option value="">Semua</option>
                @php $startYear = 2024; $currentYear = date('Y'); @endphp
                @for($y = $currentYear; $y >= $startYear; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div style="flex:1; min-width:120px;">
            <label style="font-size:11px; color:var(--text-secondary); display:block; margin-bottom:4px;">Bulan</label>
            <select name="bulan" style="width:100%; padding:7px 10px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); border-radius:7px; font-size:13px;">
                <option value="">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        @if(!isset($title) || $title === 'Antrian Surat')
        <div style="flex:1; min-width:120px;">
            <label style="font-size:11px; color:var(--text-secondary); display:block; margin-bottom:4px;">Status</label>
            <select name="status" style="width:100%; padding:7px 10px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); border-radius:7px; font-size:13px;">
                <option value="">Semua</option>
                <option value="proses"       {{ request('status') === 'proses'       ? 'selected' : '' }}>Proses</option>
                <option value="revisi"       {{ request('status') === 'revisi'       ? 'selected' : '' }}>Revisi (User)</option>
                <option value="revisi_admin" {{ request('status') === 'revisi_admin' ? 'selected' : '' }}>Admin Revisi</option>
                <option value="selesai"      {{ request('status') === 'selesai'      ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak"      {{ request('status') === 'ditolak'      ? 'selected' : '' }}>Ditolak</option>
                <option value="draft"        {{ request('status') === 'draft'        ? 'selected' : '' }}>Draf</option>
            </select>
        </div>

        <div style="flex:1; min-width:120px;">
            <label style="font-size:11px; color:var(--text-secondary); display:block; margin-bottom:4px;">Tahap</label>
            <select name="tahap" style="width:100%; padding:7px 10px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); border-radius:7px; font-size:13px;">
                <option value="">Semua Tahap</option>
                @foreach(\App\Models\Surat::NAMA_TAHAP as $no => $nama)
                    <option value="{{ $no }}" {{ request('tahap') == $no ? 'selected' : '' }}>{{ $no }}. {{ $nama }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div style="display:flex; gap:6px;">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ url()->current() }}" class="btn">Reset</a>
        </div>
    </form>
</div>

{{-- TABEL --}}
<div class="card">
    <div class="section-header">
        <div>
            <h2>📬 {{ $title ?? 'Semua Antrian Surat' }}</h2>
            <small>Total {{ $surats->total() }} surat ditemukan</small>
        </div>
    </div>

    @if($surats->isEmpty())
        <div style="text-align:center; padding:40px; color:var(--text-secondary); font-size:13px;">
            📭 Tidak ada surat yang ditemukan
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="width: 250px;">Informasi Surat</th>
                        <th style="width: 150px;">Pengusul</th>
                        <th style="width: 140px;">Detail Klasifikasi</th>
                        <th style="width: 150px;">Tujuan</th>
                        <th style="width: 160px;">Proses Tracking</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 140px;">SLA</th>
                        <th style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($surats as $surat)
                    <tr>
                        <td style="color:var(--text-secondary); font-size:12px;">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:700; color:var(--text-primary); line-height: 1.4; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: flex; align-items: center; gap: 6px;" title="{{ $surat->judul }}">
                                {{ $surat->judul }}
                                @if($surat->pendingDeleteRequest)
                                    <span class="badge" style="background:#fee2e2; color:#ef4444; border:1px solid #fca5a5; font-size:9px; padding: 2px 6px;">
                                        <i class="bi bi-trash-fill"></i> HAPUS?
                                    </span>
                                @endif
                            </div>
                            <div style="font-size:11px; color:#1e3a5f; margin-top:4px; font-weight: 600;">
                                <i class="bi bi-hash"></i> {{ $surat->nomor_surat ?? 'Belum ada nomor' }}
                            </div>
                            <div style="font-size:11px; color:var(--text-secondary); margin-top:2px; display: flex; align-items: center; gap: 4px;">
                                <i class="bi bi-calendar-event"></i> {{ $surat->created_at?->format('d/m/Y') ?? '—' }} 
                                <span style="opacity: 0.5;">|</span>
                                <i class="bi bi-clock"></i> {{ $surat->created_at?->format('H:i') ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:13px; font-weight: 600; color: var(--text-primary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $surat->user?->name }}">
                                {{ $surat->user?->name ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="badge badge-purple" style="font-size: 10px;">{{ $surat->jenis_label }}</span>
                            </div>
                            @if($surat->sifat === 'segera')
                                <span class="badge badge-red" style="font-size: 10px;">Segera</span>
                            @elseif($surat->sifat === 'rahasia')
                                <span class="badge badge-amber" style="font-size: 10px;">Rahasia</span>
                            @else
                                <span class="badge badge-gray" style="font-size: 10px;">Biasa</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 12px; color: var(--text-primary); line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $surat->tujuan }}">
                                {{ $surat->tujuan ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:12px; font-weight:700; color:#3b82f6; display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                <span>Tahap {{ $surat->tahap_sekarang }}/10</span>
                                <span style="font-size: 10px;">{{ $surat->proses_persen }}%</span>
                            </div>
                            <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
                                <div style="width: {{ $surat->proses_persen }}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #2563eb); border-radius: 10px;"></div>
                            </div>
                            <div style="font-size:10px; color:var(--text-secondary); margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $surat->nama_tahap }}">
                                {{ $surat->nama_tahap }}
                            </div>
                        </td>
                        <td>
                            @if($surat->status === 'selesai')
                                <span class="badge badge-green">Selesai</span>
                                @if(!is_null($surat->rating))
                                    <div style="font-size:10px; color:#d97706; margin-top:4px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:2px; background:rgba(251,191,36,0.1); padding:2px 4px; border-radius:4px; border:1px solid rgba(251,191,36,0.2);">
                                        <i class="bi bi-star-fill text-warning" style="color:#f59e0b !important;"></i> {{ $surat->rating }}/5
                                    </div>
                                @endif
                            @elseif($surat->status === 'ditolak')
                                <span class="badge badge-red">Ditolak</span>
                            @elseif($surat->status === 'revisi')
                                <div class="badge badge-amber" style="text-align: left; padding: 4px 8px;">
                                    <div>📝 Revisi</div>
                                    <div style="font-size: 8px; opacity: 0.8; margin-top: 1px;">{{ $surat->revisi_uploaded_at?->format('d/m H:i') ?? '-' }}</div>
                                </div>
                            @elseif($surat->status === 'revisi_admin')
                                <span class="badge" style="background:#fef3c7;color:#92400e;border:1.5px solid #fbbf24;font-size:10px;">Admin Revisi</span>
                            @elseif($surat->status === 'draft')
                                <span class="badge badge-gray">📄 Draf</span>
                            @else
                                <span class="badge badge-blue">Proses</span>
                            @endif
                        </td>
                        <td>
                            @if($surat->status === 'selesai')
                                <div class="text-success" style="font-size: 11px; font-weight: 600;">
                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                </div>
                            @elseif($surat->sla_status === 'terlambat')
                                <div class="text-danger" style="font-size: 11px; font-weight: 700;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $surat->sisa_jam }}
                                </div>
                            @else
                                <div class="text-primary" style="font-size: 11px; font-weight: 600;">
                                    <i class="bi bi-clock-history"></i> {{ $surat->sisa_jam }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.surat.show', $surat) }}"
                               class="btn btn-sm btn-primary" style="padding: 5px 10px; border-radius: 7px; font-weight: 600; font-size: 11px;">Detail <i class="bi bi-arrow-right"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div style="margin-top:16px;">
            {{ $surats->links() }}
        </div>
    @endif
</div>

@endsection