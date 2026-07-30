@extends('layouts.admin')
@section('title', 'Manajemen File Fisik Surat')

@section('content')

<div class="card" style="margin-bottom:16px;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <div>
            <h2 style="margin:0;">📁 Manajemen File Fisik Surat</h2>
            <small style="color:var(--text-secondary);">Hapus file fisik untuk menghemat ruang penyimpanan setelah proses selesai.</small>
        </div>
        <form action="{{ route('admin.file.massDelete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA file fisik dari surat yang sudah Selesai/Ditolak?')">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Bersihkan File Selesai/Ditolak
            </button>
        </form>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="card" style="margin-bottom:16px;">
    <form method="GET" action="{{ route('admin.file.index') }}" data-turbo="false"
          style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">

        <div style="flex:2; min-width:180px;">
            <label style="font-size:11px; color:var(--text-secondary); display:block; margin-bottom:4px;">Cari Judul</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul surat..."
                   style="width:100%; padding:7px 10px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); border-radius:7px; font-size:13px;">
        </div>

        <div style="flex:1; min-width:120px;">
            <label style="font-size:11px; color:var(--text-secondary); display:block; margin-bottom:4px;">Status</label>
            <select name="status" style="width:100%; padding:7px 10px; border:1px solid var(--border-color); background:var(--bg-tertiary); color:var(--text-primary); border-radius:7px; font-size:13px;">
                <option value="">Semua</option>
                <option value="proses"  {{ request('status') === 'proses'  ? 'selected' : '' }}>Proses</option>
                <option value="revisi"  {{ request('status') === 'revisi'  ? 'selected' : '' }}>Revisi</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div style="display:flex; gap:6px;">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('admin.file.index') }}" class="btn">Reset</a>
        </div>
    </form>
</div>

{{-- TABEL --}}
<div class="card">
    @if($surats->isEmpty())
        <div style="text-align:center; padding:40px; color:var(--text-secondary); font-size:13px;">
            📭 Tidak ada surat dengan file fisik yang ditemukan
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul Surat</th>
                        <th>Pengusul</th>
                        <th>Status</th>
                        <th>File Tersedia</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($surats as $surat)
                    <tr>
                        <td style="color:var(--text-secondary); font-size:12px;">{{ $loop->iteration + ($surats->currentPage()-1)*$surats->perPage() }}</td>
                        <td>
                            <div style="font-weight:500; color:var(--text-primary);">{{ $surat->judul }}</div>
                            <div style="font-size:11px; color:var(--text-secondary);">Diajukan: {{ $surat->created_at->format('d M Y') }}</div>
                        </td>
                        <td style="font-size:13px;">{{ $surat->user?->name ?? '—' }}</td>
                        <td>
                            @if($surat->status === 'selesai')
                                <span class="badge badge-green">Selesai</span>
                            @elseif($surat->status === 'ditolak')
                                <span class="badge badge-red">Ditolak</span>
                            @elseif($surat->status === 'revisi')
                                <span class="badge badge-amber">Revisi</span>
                            @else
                                <span class="badge badge-blue">Proses</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:5px; flex-direction:column;">
                                @if($surat->file_word)
                                    <span style="font-size:11px;"><i class="bi bi-file-earmark-word text-primary"></i> Dokumen Word</span>
                                @endif
                                @if($surat->file_lampiran)
                                    <span style="font-size:11px;"><i class="bi bi-file-earmark-pdf text-danger"></i> Lampiran</span>
                                @endif
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex; gap:5px; justify-content:center;">
                                <a href="{{ route('admin.surat.show', $surat) }}" class="btn btn-sm" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(in_array($surat->status, ['selesai', 'ditolak']))
                                    <form action="{{ route('admin.file.destroy', $surat) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus FILE FISIK surat ini? Data tracking akan tetap ada.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus File Fisik">
                                            <i class="bi bi-file-earmark-x"></i> Hapus File
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled title="Surat masih diproses">
                                        <i class="bi bi-lock-fill"></i> Terkunci
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">
            {{ $surats->links() }}
        </div>
    @endif
</div>

@endsection
