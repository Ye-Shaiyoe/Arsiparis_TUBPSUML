@extends('layouts.admin')

@section('title', 'Detail Pegawai')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- BACK BUTTON & HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 20px;">
            @if ($user->profile_photo)
                <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" 
                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
            @else
                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); display: flex; align-items: center; justify-content: center; font-size: 32px; color: white; font-weight: bold; border: 4px solid white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h1 style="margin: 0; font-size: 32px; letter-spacing: -0.025em;">{{ $user->name }}</h1>
                <p style="color: #6b7280; margin-top: 4px; font-size: 14px;">
                    <strong>Email:</strong> {{ $user->email }} | 
                    <strong>Role:</strong> <span class="badge {{ $user->role === 'admin' ? 'badge-blue' : 'badge-gray' }}" style="font-size: 11px;">{{ ucfirst($user->role) }}</span> |
                    <strong>Daftar:</strong> {{ $user->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="border-radius: 12px; padding: 10px 20px;">
            ← Kembali
        </a>
    </div>

    {{-- USER STATISTICS --}}
    <div class="stat-grid" style="grid-template-columns: repeat(5, 1fr) !important;">
        <div class="stat-card blue">
            <div class="stat-label">Total Surat</div>
            <div class="stat-value">{{ $stats['total_surats'] }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $stats['surats_selesai'] }}</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-label">Dalam Proses</div>
            <div class="stat-value">{{ $stats['surats_proses'] }}</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value">{{ $stats['surats_ditolak'] }}</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Rata-rata Hari Proses</div>
            <div class="stat-value">{{ $stats['avg_processing_days'] }}</div>
            <div class="stat-sub">Untuk surat selesai</div>
        </div>
    </div>

    {{-- DIGITAL SIGNATURE (TTE) SECTION --}}
    @if($user->signature_path)
        <div class="card" style="background: linear-gradient(135deg, #f0f4ff 0%, #f9f5ff 100%); border: 1px solid #e0e7ff;">
            <div class="section-header" style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 24px;">✍️</span>
                <h2 style="margin: 0;">Tanda Tangan Elektronik (TTE)</h2>
                <span class="badge badge-green" style="font-size: 11px; padding: 4px 8px;">Terverifikasi</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                {{-- Signature Image --}}
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label style="font-weight: 600; font-size: 13px; color: #4b5563;">Pratinjau Tanda Tangan</label>
                    <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 16px; background: white; display: flex; align-items: center; justify-content: center; min-height: 180px;">
                        <img src="{{ Storage::url($user->signature_path) }}" alt="Tanda Tangan {{ $user->name }}" 
                             style="max-width: 280px; max-height: 160px; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                    </div>
                    <p style="font-size: 12px; color: #6b7280; margin: 0;">
                        <strong>Pemilik:</strong> {{ $user->name }}<br>
                        <strong>Tanggal Daftar:</strong> {{ $user->created_at->format('d M Y H:i') }}<br>
                        <strong>Terakhir Diperbarui:</strong> {{ $user->updated_at->format('d M Y H:i') }}
                    </p>
                </div>

                {{-- Signature Info & Security Status --}}
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="background: white; border-radius: 8px; padding: 12px; border-left: 4px solid #4361ee;">
                        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #1f2937;">🔒 Status Keamanan</h4>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: pulse 2s infinite;"></span>
                            <span style="font-size: 12px; color: #059669;"><strong>TTD Terverifikasi</strong></span>
                        </div>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            Tanda tangan digital telah tersimpan dan siap digunakan untuk penandatanganan dokumen resmi.
                        </p>
                    </div>

                    <div style="background: white; border-radius: 8px; padding: 12px; border-left: 4px solid #f59e0b;">
                        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #1f2937;">🔑 PIN Perlindungan</h4>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            TTD ini dilindungi dengan PIN terenkripsi. Admin tidak dapat melihat PIN pengguna untuk keamanan maksimal.
                        </p>
                        <div style="margin-top: 8px; padding: 8px; background: #fef3c7; border-radius: 4px; border-left: 2px solid #f59e0b;">
                            <span style="font-size: 11px; color: #92400e;">⚠️ PIN hanya diketahui oleh pengguna untuk autentikasi dokumen</span>
                        </div>
                    </div>

                    <div style="background: white; border-radius: 8px; padding: 12px; border-left: 4px solid #6366f1;">
                        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #1f2937;">📋 Informasi File</h4>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            <strong>Format:</strong> PNG (Transparent)<br>
                            <strong>Path:</strong> <code style="background: #f3f4f6; padding: 2px 4px; border-radius: 3px; font-size: 11px;">{{ $user->signature_path }}</code><br>
                            <strong>Ukuran:</strong> Variable (disesuaikan pengguna)<br>
                            <strong>Keamanan:</strong> Private Storage (tidak accessible publik)
                        </p>
                    </div>
                </div>
            </div>

            {{-- Usage Info --}}
            <div style="margin-top: 16px; padding: 12px; background: white; border-radius: 8px; border-left: 4px solid #8b5cf6;">
                <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #1f2937;">📝 Penggunaan TTD</h4>
                <p style="font-size: 12px; color: #6b7280; margin: 0;">
                    Tanda tangan ini akan ditampilkan pada:
                </p>
                <ul style="margin: 8px 0 0 0; padding-left: 20px; font-size: 12px; color: #6b7280;">
                    <li>Dokumen yang ditandatangani di Tahap 6 (Tanda Tangan DS)</li>
                    <li>Laporan dan sertifikat resmi dari BP Suml</li>
                    <li>Dokumen publik yang diverifikasi</li>
                </ul>
            </div>
        </div>
    @else
        <div class="card" style="border: 1px solid #fee2e2; background: #fef2f2;">
            <div style="display: flex; align-items: center; gap: 12px; padding: 16px; text-align: center;">
                <span style="font-size: 32px;">✍️</span>
                <div>
                    <h3 style="margin: 0 0 4px 0; color: #991b1b; font-size: 14px;"><strong>Tanda Tangan Belum Ditambahkan</strong></h3>
                    <p style="margin: 0; font-size: 12px; color: #7f1d1d;">
                        Pengguna belum mendaftarkan tanda tangan elektronik (TTE). TTD dapat ditambahkan melalui halaman profil pengguna.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- SURAT HISTORY --}}
    <div class="card">
        <div class="section-header" style="margin-bottom: 16px;">
            <h2>📄 Riwayat Surat</h2>
            <small>Total: {{ $user->surats->count() }} surat</small>
        </div>

        @if($user->surats->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">Judul</th>
                            <th style="width: 15%;">Jenis</th>
                            <th style="width: 12%;">Status</th>
                            <th style="width: 12%; text-align: center;">Tahap</th>
                            <th style="width: 12%; text-align: center;">Deadline</th>
                            <th style="width: 10%; text-align: center;">Dibuat</th>
                            <th style="width: 9%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->surats as $surat)
                            <tr>
                                <td>
                                    <strong>{{ Str::limit($surat->judul, 30) }}</strong>
                                </td>
                                <td>
                                    <span style="font-size: 11px; color: #6b7280;">
                                        {{ str_replace('_', ' ', $surat->jenis) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ match($surat->status) {
                                        'selesai' => 'badge-green',
                                        'proses' => 'badge-blue',
                                        'ditolak' => 'badge-red',
                                        default => 'badge-gray'
                                    } }}">
                                        {{ ucfirst($surat->status) }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-size: 12px; color: #6b7280;">
                                        {{ $surat->tahap_sekarang }}/10
                                    </span>
                                </td>
                                <td style="text-align: center; font-size: 12px;">
                                    @if($surat->deadline_sla)
                                        <span style="color: {{ now()->diffInDays($surat->deadline_sla) <= 3 ? '#b91c1c' : '#6b7280' }};">
                                            {{ $surat->deadline_sla->format('d M Y') }}
                                        </span>
                                    @else
                                        <span style="color: #9ca3af;">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center; font-size: 12px; color: #6b7280;">
                                    {{ $surat->created_at->format('d M Y') }}
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.surat.show', $surat) }}" class="btn btn-sm" title="Lihat detail">
                                        👁️
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px; color: #9ca3af;">
                <p style="font-size: 14px;">Pengguna ini belum mengajukan surat apapun</p>
            </div>
        @endif
    </div>

</div>

<style>
    @media (max-width: 768px) {
        div[style*="display: flex"] {
            flex-direction: column !important;
            gap: 16px !important;
        }
        
        table { font-size: 11px; }
        thead th { padding: 8px 6px; }
        tbody td { padding: 8px 6px; }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    @media (max-width: 1024px) {
        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
