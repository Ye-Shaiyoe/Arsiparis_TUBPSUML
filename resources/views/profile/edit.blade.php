<x-app-layout>
    <x-slot name="title">Pengaturan Profil — BP SUML</x-slot>

    @push('head')
        <meta name="turbo-visit-control" content="reload">
    @endpush

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.6);
            --accent-blue: #4361ee;
        }

        .profile-page-container {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 0.03) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 0.03) 0, transparent 50%),
                linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
            padding: 2.5rem 1rem 6rem;
        }

        .profile-header-title {
            font-family: 'Sora', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.03em;
        }

        .glass-card-profile {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            box-shadow: 0 12px 40px -12px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card-profile:hover {
            box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.12);
            border-color: rgba(67, 97, 238, 0.2);
        }

        .nav-item-profile {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .nav-item-profile.active {
            background: linear-gradient(90deg, #4361ee1a 0%, transparent 100%);
            color: #4361ee !important;
            border-color: rgba(67, 97, 238, 0.15);
            font-weight: 700;
        }

        .nav-item-profile:not(.active):hover {
            background: rgba(255, 255, 255, 0.5);
            transform: translateX(4px);
        }

        .section-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #475569;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-label::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.08), transparent);
        }

        /* Form Customizations */
        input:focus,
        select:focus,
        textarea:focus {
            ring: 4px solid rgba(67, 97, 238, 0.1) !important;
            border-color: #4361ee !important;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>

    <div class="profile-page-container">
        <div class="max-w-6xl mx-auto space-y-12">

            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 px-4">
                <div class="space-y-3">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-2">
                            <li class="flex items-center">
                                <a href="{{ route('dashboard') }}"
                                    class="text-[10px] font-black text-slate-400 hover:text-blue-600 transition-colors uppercase tracking-[0.2em]">Dashboard</a>
                            </li>
                            <li class="flex items-center">
                                <i class="bi bi-chevron-right text-[10px] text-slate-300 mx-1"></i>
                                <span
                                    class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]">Pengaturan
                                    Profil</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-5xl font-black profile-header-title">Profil Saya</h1>
                    <p class="text-slate-500 font-medium max-w-lg">Kelola identitas digital, verifikasi data, dan
                        tingkatkan keamanan akun Anda dalam satu panel kendali.</p>
                </div>
                <div class="hidden lg:block pb-1">
                    <div class="flex -space-x-3 overflow-hidden">
                        <div
                            class="inline-block h-12 w-12 rounded-2xl ring-4 ring-white bg-blue-600 flex items-center justify-center text-white shadow-xl">
                            <i class="bi bi-person-fill-gear text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats & Profile Summary (Injected Partials) --}}
            <div class="transform transition-all duration-500 hover:scale-[1.01]">
                @include('profile.partials.user-statistics')
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                {{-- Left Sidebar Navigation --}}
                <div class="lg:col-span-4 space-y-8">
                    <div class="glass-card-profile p-8 sticky top-10">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Menu Pengaturan
                        </h3>
                        <div class="space-y-2">
                            <a href="#info-umum"
                                class="nav-item-profile active flex items-center gap-4 p-4 rounded-2xl text-sm transition-all group">
                                <div
                                    class="w-10 h-10 rounded-xl bg-blue-600/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="bi bi-person-bounding-box text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold">Informasi Umum</span>
                                    <span class="text-[10px] opacity-60">Nama, NIP & Email</span>
                                </div>
                            </a>
                            <a href="#keamanan"
                                class="nav-item-profile flex items-center gap-4 p-4 rounded-2xl text-slate-500 text-sm transition-all group">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="bi bi-shield-lock-fill text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold">Keamanan</span>
                                    <span class="text-[10px] opacity-60">Kata Sandi</span>
                                </div>
                            </a>
                            <a href="#sesi-aktif"
                                class="nav-item-profile flex items-center gap-4 p-4 rounded-2xl text-slate-500 text-sm transition-all group">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="bi bi-laptop text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold">Sesi Aktif</span>
                                    <span class="text-[10px] opacity-60">Riwayat Perangkat</span>
                                </div>
                            </a>
                            <a href="#tte-section"
                                class="nav-item-profile flex items-center gap-4 p-4 rounded-2xl text-slate-500 text-sm transition-all group">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="bi bi-pencil-fill text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold">Tanda Tangan & TTE</span>
                                    <span class="text-[10px] opacity-60">Digital Signature & PIN</span>
                                </div>
                            </a>
                            <a href="#akun-lain"
                                class="nav-item-profile flex items-center gap-4 p-4 rounded-2xl text-slate-500 text-sm transition-all group">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="bi bi-person-plus-fill text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold">Multi-Akun</span>
                                    <span class="text-[10px] opacity-60">Hubungkan Akun Lain</span>
                                </div>
                            </a>
                            <a href="#hapus-akun"
                                class="nav-item-profile flex items-center gap-4 p-4 rounded-2xl text-red-500 text-sm transition-all group hover:bg-red-50">
                                <div
                                    class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="bi bi-trash3-fill text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold">Hapus Akun</span>
                                    <span class="text-[10px] opacity-60">Tindakan Irreversibel</span>
                                </div>
                            </a>
                        </div>

                        <div class="mt-10 pt-10 border-t border-slate-100/50">
                            <div class="p-5 rounded-[22px] bg-slate-900 text-white relative overflow-hidden group">
                                <div class="relative z-10">
                                    <div class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-2">
                                        Security Engine</div>
                                    <div class="text-sm font-bold flex items-center gap-3">
                                        <div class="flex space-x-1">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"
                                                style="animation-delay: 0.2s"></div>
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"
                                                style="animation-delay: 0.4s"></div>
                                        </div>
                                        Sistem Terproteksi
                                    </div>
                                </div>
                                <i
                                    class="bi bi-shield-shaded absolute -right-4 -bottom-4 text-7xl text-white/5 group-hover:scale-125 transition-transform duration-700"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Forms Area --}}
                <div class="lg:col-span-8 space-y-10">

                    {{-- Profile Information --}}
                    <div id="info-umum" class="glass-card-profile p-8 sm:p-12 scroll-mt-10 relative overflow-hidden">
                        <div class="section-label">Identitas Pegawai</div>
                        <div class="max-w-2xl relative z-10">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                        <i class="bi bi-person absolute -right-10 -top-10 text-[15rem] text-slate-400/5 -rotate-12"></i>
                    </div>

                    {{-- Update Password --}}
                    <div id="keamanan" class="glass-card-profile p-8 sm:p-12 scroll-mt-10 relative overflow-hidden">
                        <div class="section-label">Otentikasi & Keamanan</div>
                        <div class="max-w-2xl relative z-10">
                            @include('profile.partials.update-password-form')
                        </div>
                        <i class="bi bi-key absolute -right-10 -top-10 text-[15rem] text-slate-400/5 -rotate-12"></i>
                    </div>

                    {{-- Active Sessions --}}
                    <div id="sesi-aktif" class="glass-card-profile p-8 sm:p-12 scroll-mt-10 relative overflow-hidden">
                        <div class="section-label">Sesi & Riwayat Perangkat</div>
                        <div class="max-w-2xl relative z-10">
                            @include('profile.partials.active-sessions-management')
                        </div>
                        <i
                            class="bi bi-display absolute -right-10 -top-10 text-[15rem] text-slate-400/5 -rotate-12"></i>
                    </div>

                    {{-- TTE & Tanda Tangan Digital --}}
                    <div id="tte-section" class="glass-card-profile p-8 sm:p-12 scroll-mt-10 relative overflow-hidden">
                        <div class="section-label">Tanda Tangan Digital</div>
                        <div class="max-w-2xl relative z-10">
                            @include('profile.partials.tte-management')
                        </div>
                        <i
                            class="bi bi-pencil-fill absolute -right-10 -top-10 text-[15rem] text-slate-400/5 -rotate-12"></i>
                    </div>

                    {{-- Manage Switchable Accounts --}}
                    <div id="akun-lain" class="glass-card-profile p-8 sm:p-12 scroll-mt-10 relative overflow-hidden">
                        <div class="section-label">Manajemen Sesi Akun</div>
                        <div class="max-w-2xl relative z-10">
                            @include('profile.partials.switch-accounts-management')
                        </div>
                        <i class="bi bi-people absolute -right-10 -top-10 text-[15rem] text-slate-400/5 -rotate-12"></i>
                    </div>

                    {{-- Delete User --}}
                    <div id="hapus-akun"
                        class="p-8 sm:p-12 bg-red-50/30 border border-red-100 rounded-[32px] shadow-2xl shadow-red-500/5 relative overflow-hidden group scroll-mt-10">
                        <div class="section-label text-red-600">Area Kritis</div>
                        <div class="max-w-2xl relative z-10">
                            <h4 class="text-red-600 font-black text-xl mb-3">Tutup Akun Permanen</h4>
                            <p class="text-red-700/70 text-sm mb-8 leading-relaxed">Menghapus akun akan menghilangkan
                                semua data akses dan riwayat aktivitas Anda dari sistem ini selamanya.</p>
                            @include('profile.partials.delete-user-form')
                        </div>
                        <i
                            class="bi bi-exclamation-triangle absolute -right-10 -bottom-10 text-[12rem] text-red-600/5 group-hover:rotate-12 transition-transform duration-700"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });

                // Update active state in nav
                document.querySelectorAll('.nav-item-profile').forEach(n => n.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Intersection Observer to update nav active state on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    document.querySelectorAll('.nav-item-profile').forEach(nav => {
                        nav.classList.toggle('active', nav.getAttribute('href') === `#${id}`);
                    });
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('#info-umum, #keamanan, #sesi-aktif, #tte-section, #akun-lain, #hapus-akun').forEach(section => {
            observer.observe(section);
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</x-app-layout>