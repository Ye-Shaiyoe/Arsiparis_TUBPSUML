@php
    $totalSurat = Auth::user()->surats()->count();
    $suratSelesai = Auth::user()->surats()->where('status', 'selesai')->count();
    $suratProses = Auth::user()->surats()->where('status', 'proses')->count();
@endphp

<div class="mb-10">
    <div class="relative overflow-hidden glass-card-profile p-1 shadow-2xl">
        {{-- Decorative Background --}}
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-[#4361ee] to-[#3a0ca3] opacity-10"></div>
        <div class="absolute -right-10 -top-10 opacity-[0.03] text-slate-900 pointer-events-none">
            <i class="bi bi-person-bounding-box text-[240px]"></i>
        </div>

        <div class="relative px-6 py-10 sm:px-10">
            <div class="flex flex-col lg:flex-row items-center lg:items-end gap-10">
                
                {{-- Avatar with Glow --}}
                <div class="relative group shrink-0">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[28px] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative w-36 h-36 sm:w-40 sm:h-40 rounded-[24px] overflow-hidden border-4 border-white dark:border-slate-800 bg-slate-50 shadow-2xl">
                        <img id="avatar-preview-stats"
                            src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4361ee&color=fff&size=128' }}"
                            alt="{{ Auth::user()->name }}"
                            class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:rotate-1">
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white w-10 h-10 rounded-full border-4 border-white dark:border-slate-800 flex items-center justify-center shadow-lg" title="Akun Aktif">
                        <i class="bi bi-check-lg text-lg"></i>
                    </div>
                </div>

                {{-- User Info --}}
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-black uppercase tracking-widest mb-3">
                        <i class="bi bi-shield-check"></i> Verified Profile
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight leading-tight mb-2">{{ Auth::user()->name }}</h1>
                    
                    <div class="flex flex-wrap justify-center lg:justify-start items-center gap-y-3 gap-x-6 text-slate-500">
                        <div class="flex items-center gap-2 font-semibold">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <span class="text-sm">{{ Auth::user()->getRoleLabel() }}</span>
                        </div>
                        <div class="flex items-center gap-2 font-semibold">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="bi bi-envelope-paper-fill"></i>
                            </div>
                            <span class="text-sm">{{ Auth::user()->email }}</span>
                        </div>
                        @if(Auth::user()->nip)
                        <div class="flex items-center gap-2 font-semibold">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="bi bi-upc-scan"></i>
                            </div>
                            <span class="text-sm">NIP: {{ Auth::user()->nip }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Stats Grid --}}
                <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                    <div class="bg-white/50 backdrop-blur-md border border-white/50 rounded-2xl p-4 sm:p-5 w-24 sm:w-28 text-center shadow-xl shadow-slate-200/50">
                        <div class="text-2xl sm:text-3xl font-black text-blue-600 mb-1">{{ $totalSurat }}</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total</div>
                    </div>
                    <div class="bg-white/50 backdrop-blur-md border border-white/50 rounded-2xl p-4 sm:p-5 w-24 sm:w-28 text-center shadow-xl shadow-slate-200/50">
                        <div class="text-2xl sm:text-3xl font-black text-emerald-500 mb-1">{{ $suratSelesai }}</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Selesai</div>
                    </div>
                    <div class="bg-white/50 backdrop-blur-md border border-white/50 rounded-2xl p-4 sm:p-5 w-24 sm:w-28 text-center shadow-xl shadow-slate-200/50">
                        <div class="text-2xl sm:text-3xl font-black text-amber-500 mb-1">{{ $suratProses }}</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Proses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>