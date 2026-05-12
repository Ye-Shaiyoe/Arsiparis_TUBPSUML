@extends('layouts.user')

@section('title', 'Kotak Aspirasi')

@section('content')
<div class="container-fluid animate-in">
    <div class="row g-4">
        {{-- FORM ASPIRASI --}}
        <div class="col-lg-5">
            <div class="card-custom sticky-top" style="top: 80px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3" style="color: var(--text-primary);">
                        <i class="bi bi-chat-right-heart-fill text-primary me-2"></i> Kirim Aspirasi
                    </h4>
                    <p class="text-muted small mb-4">
                        Saran, kritik, atau pertanyaan Anda sangat berharga untuk kemajuan Balai Pengelolaan SUML.
                    </p>

                    <form action="{{ route('user.aspirasi.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kategori</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="kategori" id="cat1" value="saran" checked>
                                <label class="btn btn-outline-primary btn-sm flex-grow-1" for="cat1">Saran</label>

                                <input type="radio" class="btn-check" name="kategori" id="cat2" value="keluhan">
                                <label class="btn btn-outline-danger btn-sm flex-grow-1" for="cat2">Keluhan</label>

                                <input type="radio" class="btn-check" name="kategori" id="cat3" value="pertanyaan">
                                <label class="btn btn-outline-info btn-sm flex-grow-1" for="cat3">Tanya</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tujuan Aspirasi</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="tujuan" id="to1" value="admin" {{ request('to') !== 'it_support' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary btn-sm flex-grow-1 d-flex align-items-center justify-content-center gap-2" for="to1">
                                    <i class="bi bi-person-badge"></i> Admin
                                </label>

                                <input type="radio" class="btn-check" name="tujuan" id="to2" value="it_support" {{ request('to') === 'it_support' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info btn-sm flex-grow-1 d-flex align-items-center justify-content-center gap-2" for="to2">
                                    <i class="bi bi-cpu"></i> IT Support
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Judul Aspirasi</label>
                            <input type="text" name="judul" class="form-control" placeholder="Contoh: Usulan Perbaikan AC Ruang Arsip" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Isi Aspirasi</label>
                            <textarea name="isi" class="form-control" rows="5" placeholder="Tuliskan aspirasi Anda secara lengkap di sini..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 12px;">
                            <i class="bi bi-send me-2"></i> Kirim Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- DAFTAR ASPIRASI SAYA --}}
        <div class="col-lg-7">
            <div class="card-custom">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h5 class="fw-bold mb-0" style="color: var(--text-primary);">
                            <i class="bi bi-journal-text text-primary me-2"></i> Riwayat Aspirasi Saya
                        </h5>
                        <form action="{{ url()->current() }}" method="GET">
                            <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 100px; border-radius: 8px; font-size: 12px; border-color: #e5e7eb;">
                                <option value="">Semua</option>
                                @php $startYear = 2024; $currentYear = date('Y'); @endphp
                                @for($y = $currentYear; $y >= $startYear; $y--)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </form>
                    </div>

                    @if($aspirasis->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-dots text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3">Belum ada aspirasi yang dikirimkan.</p>
                        </div>
                    @else
                        @foreach($aspirasis as $item)
                            <div class="aspirasi-item p-3 mb-3" style="border-radius: 16px; border: 1px solid var(--border-color); background: var(--bg-tertiary); transition: all 0.3s ease;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="d-flex gap-1">
                                            <span class="badge {{ 
                                                match($item->kategori) {
                                                    'bug', 'keluhan' => 'bg-danger',
                                                    'error' => 'bg-warning text-dark',
                                                    'fitur' => 'bg-success',
                                                    'saran' => 'bg-primary',
                                                    default => 'bg-info'
                                                } 
                                            }} mb-2" style="font-size: 10px;">
                                                {{ ucfirst($item->kategori) }}
                                            </span>
                                            <span class="badge {{ $item->tujuan === 'it_support' ? 'bg-info bg-opacity-75' : 'bg-secondary bg-opacity-75' }} mb-2" style="font-size: 10px;">
                                                Untuk: {{ $item->tujuan === 'it_support' ? 'IT Support' : 'Admin' }}
                                            </span>
                                        </div>
                                        <h6 class="fw-bold mb-0" style="color: var(--text-primary);">{{ $item->judul }}</h6>
                                    </div>
                                    <div class="text-end">
                                        <div class="small text-muted" style="font-size: 11px;">{{ $item->created_at->format('d M Y') }}</div>
                                        <div class="d-flex align-items-center justify-content-end gap-2 mt-1">
                                            <span class="badge rounded-pill {{ $item->status === 'pending' ? 'bg-secondary' : ($item->status === 'dibaca' ? 'bg-info' : 'bg-success') }}" style="font-size: 10px;">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                            @if(!$item->balasan)
                                                <form action="{{ route('user.aspirasi.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aspirasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger p-0 px-1" style="font-size: 10px; border-radius: 4px;" title="Hapus Aspirasi">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <p class="small text-secondary mb-3" style="line-height: 1.6;">{{ $item->isi }}</p>

                                @if($item->balasan)
                                    <div class="p-3 mt-2" style="background: rgba(255,255,255,0.6); border-radius: 12px; border-left: 4px solid #15803d;">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="bi bi-reply-fill text-success"></i>
                                            <span class="fw-bold small text-success">Balasan Admin:</span>
                                        </div>
                                        <p class="small mb-0 text-dark">{{ $item->balasan }}</p>
                                        <div class="text-end mt-2" style="font-size: 10px; color: #64748b;">
                                            Dibalas pada: {{ $item->dibalas_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $aspirasis->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .aspirasi-item:hover {
        transform: translateY(-3px);
        background: rgba(255,255,255,0.5) !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
</style>
@endsection
