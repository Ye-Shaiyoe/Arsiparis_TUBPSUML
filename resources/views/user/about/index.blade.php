@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            {{-- Header Instansi --}}
            <div class="card card-custom mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-2 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                            style="width: 52px; height: 52px; font-size: 24px; flex-shrink: 0;">
                            <img src="{{ asset('images/metrologi.png') }}" alt="">
                        </div>
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-1" style="font-size: 11px;">
                                Kementerian Perdagangan RI
                            </span>
                            <h5 class="fw-bold mb-0" style="color:#111827;">Balai Pengelolaanan SUML</h5>
                            <small class="text-muted">Direktorat Metrologi</small>
                        </div>
                    </div>

                    <p class="text-muted" style="font-size: 14px; line-height: 1.7;">
                        Direktorat Metrologi merupakan unit pelaksana teknis di bawah Kementerian Perdagangan RI yang
                        bertugas melaksanakan standardisasi dan pengawasan di bidang BP SUML legal — memastikan
                        kebenaran pengukuran dalam transaksi perdagangan demi perlindungan konsumen dan kepastian usaha.
                    </p>

                    <p class="text-muted" style="font-size: 14px; line-height: 1.7;">
                        Aplikasi <strong>Surat Balai Pengelolaan SUML</strong> hadir sebagai solusi digital untuk pengelolaan
                        korespondensi kedinasan di lingkungan BP SUML Legal, mempermudah proses administrasi
                        surat-menyurat secara transparan dan efisien.
                    </p>
                </div>
            </div>

            <div class="row g-3">
                {{-- Informasi Kontak --}}
                <div class="col-lg-6">
                    <div class="card card-custom h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold mb-3" style="color:#111827;">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Kontak & Informasi
                            </h6>

                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important;">
                                        <small class="text-muted d-block mb-1">Alamat</small>
                                        <span style="font-size: 13px; font-weight: 500;color:#111827;">
                                            Jl. Pasteur No.27, RT.02, Pasir Kaliki,<br>
                                            Kec. Cicendo, Kota Bandung, Jawa Barat 40171
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important;">
                                        <small class="text-muted d-block mb-1">Telepon</small>
                                        <span style="font-size: 13px; font-weight: 500;color:#111827;">(022) 6032720</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important;">
                                        <small class="text-muted d-block mb-1">Jam Operasional</small>
                                        <span style="font-size: 13px; font-weight: 500;color:#111827;">Senin – Jumat<br>08.00 – 16.00 WIB</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important;">
                                        <small class="text-muted d-block mb-1">Email</small>
                                        <span style="font-size: 13px; font-weight: 500;color:#111827;">metrologi@kemendag.go.id</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-2 bg-light p-3" style="background:#f9fafb!important;">
                                        <small class="text-muted d-block mb-1">Website</small>
                                        <a href="https://metrologi.kemendag.go.id" target="_blank"
                                           style="font-size: 13px; font-weight: 500;color:#111827;">
                                            metrologi.kemendag.go.id
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fitur Utama --}}
                <div class="col-lg-6">
                    <div class="card card-custom h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold mb-3" style="color:#111827;">
                                <i class="bi bi-stars text-warning me-1"></i> Fitur Utama Aplikasi
                            </h6>
                            <ul class="list-unstyled mb-0">
                                @foreach([
                                    ['icon' => 'bi-send', 'text' => 'Pengajuan surat secara daring tanpa perlu datang langsung'],
                                    ['icon' => 'bi-search', 'text' => 'Pelacakan status surat secara real-time'],
                                    ['icon' => 'bi-file-earmark-text', 'text' => 'Manajemen template surat kedinasan'],
                                    ['icon' => 'bi-download', 'text' => 'Download berkas dalam format Word & PDF'],
                                    ['icon' => 'bi-bell', 'text' => 'Notifikasi otomatis perkembangan surat'],
                                ] as $fitur)
                                <li class="d-flex align-items-start gap-2 py-2 border-bottom" style="font-size: 13px;border-color:#e5e7eb;">
                                    <i class="bi {{ $fitur['icon'] }} text-primary mt-1" style="flex-shrink:0;"></i>
                                    <span class="text-muted">{{ $fitur['text'] }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Google Maps --}}
            <div class="card card-custom mt-3">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3" style="color:#111827;">
                        <i class="bi bi-map text-success me-1"></i> Lokasi Kami
                    </h6>
                    <div class="rounded-3 overflow-hidden border" style="height: 320px;border-color:#e5e7eb;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.4!2d107.5897!3d-6.8985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6e3f63d8df9%3A0x3c1a0ef3aea8df32!2sJl.%20Pasteur%20No.27%2C%20Pasir%20Kaliki%2C%20Kec.%20Cicendo%2C%20Kota%20Bandung%2C%20Jawa%20Barat%2040171!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
                            width="100%"
                            height="320"
                            style="border: 0; display: block;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <p class="text-muted mt-2 mb-0" style="font-size: 12px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Jl. Pasteur No.27, RT.02, Pasir Kaliki, Kec. Cicendo, Kota Bandung, Jawa Barat 40171
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-3 pb-2 text-center">
                <small class="text-muted">
                    &copy; {{ date('Y') }} Balai Pengelolaanan SUML — RI. All rights reserved.
                </small>
            </div>

        </div>
    </div>
</div>
@endsection