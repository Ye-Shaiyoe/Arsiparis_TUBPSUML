@extends('layouts.user')
@section('title', 'Ajukan Surat')

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">

        {{-- Header --}}
        <div class="d-flex align-items-center gap-2 mb-4 animate-in">
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" style="border-radius:8px;background:#f9fafb;color:#111827;border-color:#e5e7eb;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="fw-bold mb-0" style="color:#111827;">📝 Pengajuan Surat Baru</h5>
                <small class="text-muted">Isi form berikut dengan lengkap dan benar</small>
            </div>
        </div>

        @if($isLibur)
            <div class="card shadow-sm border-0 animate-in" style="border-radius:16px; background: white; overflow: hidden; animation-delay: 0.1s;">
                <div style="height: 10px; background: #f59e0b;"></div>
                <div class="card-body p-5 text-center">
                    <div style="font-size:64px; margin-bottom:20px;">⏰</div>
                    <h4 class="fw-bold mb-3" style="color:#1e293b;">Oopss! Layanan Tutup</h4>
                    <p class="text-muted mb-4" style="font-size:15px; line-height:1.6;">
                        Mohon maaf, layanan pengajuan surat dinonaktifkan sementara.<br>
                        Saat ini pukul <strong>{{ now()->format('H:i') }} WIB</strong>.<br>
                        Silakan kembali lagi pada jam operasional layanan.<br>
                        <span class="badge bg-light text-dark mt-2 p-2">Senin–Kamis: 07.30–16.00 WIB | Jumat: 07.30–16.30 WIB | Sabtu–Minggu: <span class="text-danger">Libur</span></span>
                    </p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary-modern px-4">
                        <i class="bi bi-house-door-fill me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        @else
            <div class="card card-custom animate-in" style="animation-delay: 0.1s;">
                <div class="card-body p-4">
                    <form action="{{ route('user.surat.store') }}" method="POST" enctype="multipart/form-data" id="formAjukan">
                        @csrf

                        {{-- STEP 1: Info Surat --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div style="width:24px;height:24px;border-radius:50%;background:#1e3a5f;color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;">1</div>
                                <span class="fw-semibold" style="font-size:14px;color:#111827;">Informasi Surat</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-size:13px;font-weight:500;color:#111827;">
                                    Judul Surat <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="judul" value="{{ old('judul') }}"
                                    class="form-control @error('judul') is-invalid @enderror"
                                    placeholder="Contoh: Permohonan Kalibrasi Alat Ukur Timbangan"
                                    style="font-size:13px; border-radius:8px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" style="font-size:13px;font-weight:500;color:#111827;">
                                        Jenis Surat <span class="text-danger">*</span>
                                    </label>
                                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror"
                                            style="font-size:13px; border-radius:8px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach(\App\Models\Surat::JENIS_LABEL as $val => $label)
                                            <option value="{{ $val }}" {{ old('jenis') === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-size:13px;font-weight:500;color:#111827;">
                                        Sifat Surat <span class="text-danger">*</span>
                                    </label>
                                    <select name="sifat" class="form-select @error('sifat') is-invalid @enderror"
                                            style="font-size:13px; border-radius:8px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                                        <option value="biasa"   {{ old('sifat','biasa') === 'biasa'   ? 'selected' : '' }}>Biasa</option>
                                        <option value="segera"  {{ old('sifat') === 'segera'  ? 'selected' : '' }}>Segera</option>
                                        <option value="rahasia" {{ old('sifat') === 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                                    </select>
                                    @error('sifat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label" style="font-size:13px;font-weight:500;color:#111827;">
                                    Tujuan Surat <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="tujuan" value="{{ old('tujuan') }}"
                                    class="form-control @error('tujuan') is-invalid @enderror"
                                    placeholder="Contoh: Kepala Dinas Perdagangan Provinsi Jawa Barat"
                                    style="font-size:13px; border-radius:8px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                                @error('tujuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0" style="font-size:13px;font-weight:500;color:#111827;">
                                        Catatan (Opsional)
                                    </label>
                                    <span id="charCount" class="text-muted" style="font-size: 11px;">0 / 100</span>
                                </div>
                                <textarea name="catatan_pengusul" id="catatan_pengusul" rows="3" maxlength="100"
                                    class="form-control @error('catatan_pengusul') is-invalid @enderror"
                                    placeholder="Tambahkan catatan singkat (maks 100 karakter)"
                                    style="font-size:13px; border-radius:8px;background:#ffffff;color:#111827;border-color:#e5e7eb;">{{ old('catatan_pengusul') }}</textarea>
                                @error('catatan_pengusul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr style="border-color:#e5e7eb;">

                        {{-- STEP 2: Upload --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div style="width:24px;height:24px;border-radius:50%;background:#1e3a5f;color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;">2</div>
                                <span class="fw-semibold" style="font-size:14px;color:#111827;">Upload Dokumen</span>
                            </div>

                            {{-- Template hint --}}
                            @if($templates->isNotEmpty())
                                <div class="alert alert-info py-2 px-3 mb-3" style="font-size:12px; border-radius:8px; border:none; background:#eff6ff; color:#1d4ed8;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Belum punya template? Download dulu:
                                    @foreach($templates as $tpl)
                                        <a href="{{ $tpl['url'] }}" target="_blank"
                                        class="fw-semibold text-decoration-none ms-1">{{ $tpl['nama'] }}</a>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Upload file word --}}
                            <div class="mb-3">
                                <label class="form-label" style="font-size:13px;font-weight:500;color:#111827;">
                                    File Surat (.docx) <span class="text-danger">*</span>
                                </label>
                                <label class="upload-area d-block" for="file_word">
                                    <input type="file" id="file_word" name="file_word"
                                        accept=".docx,.doc"
                                        class="@error('file_word') is-invalid @enderror"
                                        onchange="showFileName(this, 'nama_word')">
                                    <i class="bi bi-file-earmark-word" style="font-size:28px; color:#2563eb; display:block; margin-bottom:6px;"></i>
                                    <span id="nama_word" style="font-size:12px;color:#6b7280;">
                                        Klik atau drag file .docx ke sini<br>
                                        <span style="font-size:11px; color:#6b7280;">Maks. 10MB</span>
                                    </span>
                                </label>
                                @error('file_word')
                                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Upload lampiran --}}
                            <div>
                                <label class="form-label" style="font-size:13px;font-weight:500;color:#111827;">
                                    Lampiran (opsional)
                                    <span class="text-muted fw-normal" style="font-size:11px;">PDF, JPG, PNG, DOCX, XLSX</span>
                                </label>
                                <label class="upload-area d-block" for="file_lampiran">
                                    <input type="file" id="file_lampiran" name="file_lampiran"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                                    class="@error('file_lampiran') is-invalid @enderror"
                                        onchange="showFileName(this, 'nama_lampiran')">
                                    <i class="bi bi-paperclip" style="font-size:24px; display:block; margin-bottom:6px;"></i>
                                    <span id="nama_lampiran" style="font-size:12px;color:#6b7280;">
                                        Klik untuk upload lampiran<br>
                                        <span style="font-size:11px; color:#6b7280;">Maks. 20MB</span>
                                    </span>
                                </label>
                                @error('file_lampiran')
                                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr style="border-color:#e5e7eb;">

                        {{-- INFO SLA --}}
                        <div class="alert py-2 px-3 mb-4" style="background:#eff6ff;border:none;border-radius:8px;">
                            <div style="font-size:12px; color:#1d4ed8;">
                                <i class="bi bi-clock me-1"></i>
                                <strong>SLA 24 Jam Kerja</strong> — surat akan diproses maksimal 24 jam kerja (Senin-Jumat, skip weekend) setelah diajukan.
                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-light" style="border-radius:8px; font-size:13px;">
                                Batal
                            </a>
                            <button type="submit" name="action" value="draft" class="btn btn-outline-secondary d-flex align-items-center gap-2"
                                    style="border-radius:8px; font-size:13px; font-weight:600;">
                                <i class="bi bi-save-fill"></i> Simpan Draf
                            </button>
                            <button type="submit" name="action" value="submit" id="btnSubmit" class="btn btn-primary d-flex align-items-center gap-2"
                                    style="background:#1e3a5f; border-color:#1e3a5f; border-radius:8px; font-size:13px; font-weight:600;">
                                <i class="bi bi-send-fill" id="btnIcon"></i> <span id="btnText">Submit Pengajuan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('turbo:load', function() {
    function showFileName(input, targetId) {
        const el = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            const name = input.files[0].name;
            const size = (input.files[0].size / 1024).toFixed(0);
            el.innerHTML = `<strong style="color:#111827;">${name}</strong><br><span style="font-size:11px;color:#6b7280;">${size} KB · Siap diupload</span>`;
            input.closest('.upload-area').style.borderColor = '#22c55e';
            input.closest('.upload-area').style.background = '#f0fdf4';
        }
    }

    // Attach to window so onclick works
    window.showFileName = showFileName;

    const form = document.getElementById('formAjukan');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) return;

            const action = e.submitter ? e.submitter.value : 'submit';
            if (action !== 'submit') return;

            const btn = document.getElementById('btnSubmit');
            const icon = document.getElementById('btnIcon');
            const text = document.getElementById('btnText');

            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.style.cursor = 'not-allowed';
            }
            if (icon && text) {
                icon.className = 'spinner-border spinner-border-sm';
                text.innerText = 'Mengirim...';
            }
        });
    }

    // Character Counter logic
    const catatanInput = document.getElementById('catatan_pengusul');
    const charCount = document.getElementById('charCount');

    if (catatanInput && charCount) {
        const updateCount = () => {
            const len = catatanInput.value.length;
            charCount.innerText = `${len} / 100`;
            if (len >= 100) {
                charCount.classList.add('text-danger');
                charCount.classList.remove('text-muted');
            } else {
                charCount.classList.remove('text-danger');
                charCount.classList.add('text-muted');
            }
        };
        
        catatanInput.addEventListener('input', updateCount);
        updateCount(); // Initial count
    }
});
</script>
@endpush

@endsection