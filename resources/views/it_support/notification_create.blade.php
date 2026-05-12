@extends('layouts.itsupport')

@section('content')
<div class="animate-in" style="animation: slideIn 0.5s ease-out;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="margin: 0; font-weight: 700; color: #111827;">Add Notification</h2>
            <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 14px;">Kirimkan notifikasi penting ke seluruh pengguna atau grup tertentu.</p>
        </div>
        <a href="{{ route('itsupport.dashboard') }}" class="btn btn-light" style="border-radius: 12px; font-weight: 600;">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <form action="{{ route('itsupport.notification.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Target Penerima</label>
                            <div class="d-flex gap-3">
                                <div class="form-check custom-option flex-grow-1">
                                    <input class="form-check-input d-none" type="radio" name="target" id="targetAll" value="all" checked>
                                    <label class="form-check-label w-100 p-3 border rounded-4 text-center cursor-pointer" for="targetAll">
                                        <i class="bi bi-people-fill d-block mb-2 fs-4 text-primary"></i>
                                        <strong>Semua</strong>
                                    </label>
                                </div>
                                <div class="form-check custom-option flex-grow-1">
                                    <input class="form-check-input d-none" type="radio" name="target" id="targetAdmin" value="admin">
                                    <label class="form-check-label w-100 p-3 border rounded-4 text-center cursor-pointer" for="targetAdmin">
                                        <i class="bi bi-shield-lock-fill d-block mb-2 fs-4 text-danger"></i>
                                        <strong>Admin</strong>
                                    </label>
                                </div>
                                <div class="form-check custom-option flex-grow-1">
                                    <input class="form-check-input d-none" type="radio" name="target" id="targetUser" value="user">
                                    <label class="form-check-label w-100 p-3 border rounded-4 text-center cursor-pointer" for="targetUser">
                                        <i class="bi bi-person-fill d-block mb-2 fs-4 text-success"></i>
                                        <strong>User</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Tipe Notifikasi</label>
                            <select name="type" class="form-select form-select-lg border-0 bg-light" style="border-radius: 12px; font-size: 15px;">
                                <option value="info">Info (Biru)</option>
                                <option value="warning">Peringatan (Kuning)</option>
                                <option value="success">Sukses (Hijau)</option>
                                <option value="danger">Bahaya (Merah)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Judul Notifikasi</label>
                            <input type="text" name="title" class="form-control form-control-lg border-0 bg-light" placeholder="Contoh: Pemeliharaan Sistem Mendatang" required style="border-radius: 12px; font-size: 15px;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Pesan Notifikasi</label>
                            <textarea name="message" class="form-control border-0 bg-light" rows="5" placeholder="Tuliskan pesan lengkap yang ingin dikirimkan..." required style="border-radius: 12px; font-size: 15px;"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 15px; font-size: 16px;">
                            <i class="bi bi-send-fill me-2"></i> Broadcast Notifikasi Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 bg-primary text-white" style="border-radius: 20px; background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%) !important;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i> Panduan IT Support</h5>
                    <ul class="list-unstyled small mb-0" style="opacity: 0.9; line-height: 1.8;">
                        <li><i class="bi bi-check2-circle me-2"></i> Gunakan judul yang singkat dan jelas.</li>
                        <li><i class="bi bi-check2-circle me-2"></i> Pilih target yang sesuai untuk menghindari spam.</li>
                        <li><i class="bi bi-check2-circle me-2"></i> Notifikasi akan muncul di dashboard penerima secara realtime.</li>
                        <li><i class="bi bi-check2-circle me-2"></i> Gunakan tipe <strong>Danger</strong> hanya untuk isu kritis atau pemeliharaan darurat.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-option input:checked + label {
        border-color: var(--bs-primary) !important;
        background-color: rgba(13, 110, 253, 0.05);
        color: var(--bs-primary);
        box-shadow: 0 0 0 1px var(--bs-primary);
    }
    .custom-option label {
        transition: all 0.2s ease;
    }
    .custom-option label:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1 !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endsection
