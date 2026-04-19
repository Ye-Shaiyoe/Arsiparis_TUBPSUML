@extends('layouts.user')
@section('title', 'FAQ - Pertanyaan Umum')

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="text-center mb-5">
        <h3 class="fw-bold" style="color: #1e3a5f;">Pusat Bantuan & FAQ</h3>
        <p class="text-muted">Temukan jawaban untuk pertanyaan yang paling sering diajukan</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="accordion" id="faqAccordion">
                
                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Bagaimana cara mengajukan surat baru?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Klik tombol <strong>"Ajukan Surat Baru"</strong> di dashboard. Unggah file dokumen dalam format <strong>.docx</strong> (Word) dan lampiran pendukung jika ada (PDF/Gambar). Isi judul, jenis, dan sifat surat, lalu klik submit. Pastikan pengajuan dilakukan di jam operasional (07:00 - 17:00 WIB).
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Berapa lama waktu proses surat (SLA)?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Waktu proses maksimal adalah <strong>24 jam kerja</strong> (tidak termasuk hari Sabtu, Minggu, dan hari libur nasional). Anda dapat memantau status sisa waktu melalui bar SLA yang ada di dashboard pada bagian "Status SLA Surat Aktif".
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Kenapa saya tidak bisa mengajukan surat di hari Sabtu/Minggu?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Layanan administrasi kami hanya beroperasi pada hari kerja (Senin - Jumat) pukul 07:00 - 17:00 WIB untuk memastikan setiap surat mendapatkan perhatian dan validasi yang tepat dari tim terkait.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Bagaimana jika surat saya ditolak (Revisi)?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Jika surat ditolak, Anda akan menerima notifikasi. Silakan buka detail surat tersebut, baca catatan revisi dari admin di bagian riwayat, perbaiki dokumen Anda, lalu unggah kembali melalui tombol <strong>"Upload Ulang File Perbaikan"</strong>.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Di mana saya bisa mendapatkan template surat?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Kami menyediakan berbagai template surat standar di sidebar menu <strong>"Template Surat"</strong> atau langsung dari widget <strong>"Template Surat"</strong> yang ada di dashboard Anda. Silakan unduh dan sesuaikan isinya.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Tips, ada QR code di bagian surat anda ?
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Ya, ada QR code di bagian samping kanan atas surat anda. Anda bisa scan QR code tersebut untuk verifikasi surat anda. Scan lewat hp ini memudahkan anda untuk Lihat kondisi surat anda tanpa login dahulu dan memverifikasi bahwa surat itu asli.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Bagaimana jika surat saya sudah selesai tapi saya ingin menghapus file fisik surat tersebut?
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Anda bisa menghapus file fisik surat anda dengan cara klik tombol "Bersihkan File Fisik" pada halaman detail surat. Namun, perlu diingat bahwa tindakan ini akan menghapus file fisik surat dari server secara permanen untuk menjaga privasi/storage. Seluruh riwayat pemrosesan (tracking), catatan admin, dan status surat tetap akan tersimpan di dashboard ini. Hanya file dokumennya saja yang tidak akan bisa didownload lagi.
                        </div>
                    </div>
                </div>

            </div>

             {{-- Contact Support Section --}}
             <div class="card mt-5 p-4 text-center border-0 shadow-sm" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border-radius: 20px;">
                <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Masih punya pertanyaan lain?</h5>
                <p class="text-muted mb-4" style="font-size: 14px;">Jika Anda tidak menemukan jawaban yang Anda cari, jangan ragu untuk menghubungi tim IT/Admin kami.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="#" class="btn btn-success rounded-pill px-4 d-flex align-items-center gap-2" style="font-size: 13px;">
                        <i class="bi bi-whatsapp"></i> WhatsApp Helpdesk
                    </a>
                    <a href="#" class="btn btn-outline-primary rounded-pill px-4 d-flex align-items-center gap-2" style="font-size: 13px;">
                        <i class="bi bi-envelope"></i> Email Support
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .accordion-button:not(.collapsed) {
        background-color: #eff6ff !important;
        color: #1e3a5f !important;
        box-shadow: none !important;
    }
    .accordion-button:focus {
        box-shadow: none !important;
    }
    .accordion-body {
        word-break: break-word;
    }
    /* Ensure the collapse transition doesn't hide text */
    .collapse.show {
        visibility: visible !important;
        display: block !important;
    }
</style>
@endsection
