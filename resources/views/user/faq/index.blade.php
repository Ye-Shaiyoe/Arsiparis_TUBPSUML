@extends('layouts.user')
@section('title', 'FAQ - Pertanyaan Umum')

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="text-center mb-5 animate-in">
        <h3 class="fw-bold" style="color: #1e3a5f;">Pusat Bantuan & FAQ</h3>
        <p class="text-muted">Tempat Dokumentsi & Temukan jawaban untuk pertanyaan yang paling sering diajukan</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="accordion animate-in" id="faqAccordion" style="animation-delay: 0.1s;">
                
                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Bagaimana cara mengajukan surat baru?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Klik tombol <strong>"Ajukan Surat Baru"</strong> di dashboard. Unggah file dokumen dalam format <strong>.docx</strong> (Word) dan lampiran pendukung jika ada (PDF/Gambar). Isi judul, jenis, dan sifat surat, lalu klik submit. Pastikan pengajuan dilakukan di jam operasional (08.00 - 16.00 WIB).
                        </div>
                    </div>
                </div>
                
                {{-- ... and so on ... --}}
                {{-- (Actually, I should just apply animate-in to the container of the accordion items) --}}
                
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

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Apa yang terjadi jika pemrosesan surat sudah selesai?
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Layanan administrasi akan mengurus surat surat anda dan mengirimkannya ke pihak yang bersangkutan. setelah mencapai tahap 10(selesai) anda dapat menghapus surat fisik anda tanpa menghapus tracking nya. atau jika status udah lebih dari 3 hari(jika udah selesai) tidak dihapus surat nya akan terhapus secara otomatis dan tidak bisa di download lagi, untuk keamanan.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Kenapa saya tidak menerima notifikasi dari sistem?
                        </button>
                    </h2>
                    <div id="collapseNine" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Pastikan Anda sudah mengizinkan notifikasi di browser. Klik ikon <strong>🔔 lonceng</strong> di pojok kanan atas untuk melihat notifikasi terbaru. Jika notifikasi tidak muncul otomatis, coba <strong>refresh halaman</strong> atau periksa pengaturan izin notifikasi di browser Anda (biasanya di bagian ikon gembok/kunci di address bar).
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Format dan ukuran file apa saja yang diterima sistem?
                        </button>
                    </h2>
                    <div id="collapseTen" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Untuk <strong>file surat utama</strong>, sistem hanya menerima format <strong>.docx</strong> (Microsoft Word). Untuk <strong>lampiran pendukung</strong>, Anda dapat mengunggah file berformat <strong>PDF, JPG, atau PNG</strong>. Ukuran maksimal per file adalah <strong>10 MB</strong>. Pastikan file tidak terpassword/terenkripsi agar dapat diproses oleh sistem.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Apa arti masing-masing tahapan status surat (Tahap 1 - 10)?
                        </button>
                    </h2>
                    <div id="collapseEleven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Berikut penjelasan setiap tahap pemrosesan surat:
                            <ul class="mt-2 mb-0" style="padding-left: 20px; line-height: 2;">
                                <li><strong>Tahap 1</strong> — Surat baru diajukan, menunggu verifikasi awal.</li>
                                <li><strong>Tahap 2</strong> — Diterima oleh Bagian Persuratan, sedang diperiksa kelengkapan berkas.</li>
                                <li><strong>Tahap 3</strong> — Diteruskan ke Arsiparis untuk pencatatan dan penomoran.</li>
                                <li><strong>Tahap 4</strong> — Dalam proses review oleh Kasubbag TU.</li>
                                <li><strong>Tahap 5</strong> — Menunggu persetujuan Kepala Balai.</li>
                                <li><strong>Tahap 6</strong> — Disetujui, sedang dalam proses penandatanganan.</li>
                                <li><strong>Tahap 7</strong> — Surat telah ditandatangani, menunggu penomoran resmi.</li>
                                <li><strong>Tahap 8</strong> — Surat bernomor resmi, siap didistribusikan.</li>
                                <li><strong>Tahap 9</strong> — Sedang dalam proses pengiriman/distribusi.</li>
                                <li><strong>Tahap 10</strong> — <span class="text-success fw-bold">Selesai</span>. Surat telah terkirim ke pihak yang bersangkutan.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Bagaimana jika saya lupa password atau akun tidak bisa login?
                        </button>
                    </h2>
                    <div id="collapseTwelve" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Silakan hubungi <strong>Admin IT / Bagian Persuratan</strong> secara langsung untuk mereset password akun Anda. Saat ini fitur reset password mandiri belum tersedia. Siapkan <strong>NIP/identitas pegawai</strong> Anda untuk mempercepat proses verifikasi oleh admin.
                        </div>
                    </div>
                </div>

                
                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Apakah data dan dokumen surat saya aman di sistem ini?
                        </button>
                    </h2>
                    <div id="collapseThirteen" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            Ya, sistem ini dirancang dengan keamanan berlapis. Setiap akun hanya dapat mengakses surat miliknya sendiri. Seluruh aksi tercatat dalam riwayat untuk keperluan audit. Dokumen yang Anda unggah hanya dapat diakses oleh pihak yang terlibat dalam alur persetujuan surat tersebut. Disarankan untuk tidak berbagi akun Anda dengan siapapun.
                        </div>
                    </div>
                </div>
                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Jika anda sudah bisa login dengan memasukan NIP(tanpa email) dan password itu artinya..
                        </button>
                    </h2>
                    <div id="collapseFourteen" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                           Anda sudah di daftar sama admin dan <strong>segera ubah password dan email anda</strong>, dengan password awal <strong>12345678</strong> anda bisa mengubah password. lalu email juga bisa di ubah yang awalnya <strong>user{id}@gmail.com </strong> jadi email asli anda(saran email yang direkomendasikan adalah gmail.com). ubah lewat menu profile anda lihat pojok kanan atas, 
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifteen" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Ini Yang terjadi saat ditolak dan anda revisi di tracking nya seperti ini!
                        </button>
                    </h2>
                    <div id="collapseFifteen" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                           <img src="{{ asset('images/tracking.png') }}" alt="FAQ 15">
                           <br>
                           ini yang terjadi saat anda di tolak dan melakukan revisi, tampilan di tracking nya seperti ini. saat di revisi akan otomatis kembali ke Aspirasi. anda fokus aja ke garis hijau yang di sebelah centang itu(yang usulan di ajukan)? itu berarti revisi berhasil dan sudah kembali ke aspirasi, hiraukan centang yang di atas asprasi itu.  
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border" style="border-radius: 12px !important;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSixteen" 
                                style="font-size: 14px; background: #fff; color: #1e3a5f; border-radius: 12px !important;">
                            Apa Perbedaan "Draft" dan "Terkirim" pada Menu Aspirasi?
                        </button>
                    </h2>
                    <div id="collapseSixteen" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body border-top" style="font-size: 14px; line-height: 1.7; color: #111827 !important; background: #ffffff !important;">
                            <strong>Draft</strong>: Aspirasi yang masih disimpan sementara di akun Anda tapi belum diajukan resmi ke Admin. Anda bisa mengedit, menambahkan lampiran, atau menghapusnya kapan saja sebelum dikirim.
                            <br><br>
                            <strong>Terkirim</strong>: Aspirasi yang sudah Anda klik tombol "Kirim" atau "Ajukan". Status ini berarti data sudah masuk ke sistem dan dalam antrian untuk diverifikasi oleh Admin. Aspirasi yang sudah "Terkirim" tidak bisa diedit lagi, namun Anda tetap bisa melampirkan revisi jika ditolak.
                        </div>
                    </div>
                </div>

            </div>

             {{-- Contact Support Section --}}
             <div class="card mt-5 p-4 text-center border-0 shadow-sm animate-in" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border-radius: 20px; animation-delay: 0.2s;">
                <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Masih punya pertanyaan lain?</h5>
                <p class="text-muted mb-4" style="font-size: 14px;">Jika Anda tidak menemukan jawaban yang Anda cari, jangan ragu untuk menghubungi tim IT/Admin kami.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="https://wa.me/{{ config('services.whatsapp.number') }}" class="btn btn-success rounded-pill px-4 d-flex align-items-center gap-2" style="font-size: 13px;">
                        <i class="bi bi-whatsapp"></i> WhatsApp Helpdesk
                    </a>
                    <a href="mailto:metrologi@kemendag.go.id" class="btn btn-outline-primary rounded-pill px-4 d-flex align-items-center gap-2" style="font-size: 13px;">
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
