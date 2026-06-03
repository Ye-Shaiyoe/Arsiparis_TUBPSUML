@if (session('status') === 'session-revoked')
    <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-xs font-bold flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span>Sesi perangkat berhasil dinonaktifkan.</span>
    </div>
@endif

@if (session('status') === 'all-other-sessions-revoked')
    <div class="p-4 mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-xs font-bold flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span>Semua perangkat lain berhasil dikeluarkan (logged out).</span>
    </div>
@endif

<section class="space-y-6">
    <header>
        <h2 class="text-xl font-black text-slate-800 tracking-tight">
            {{ __('Sesi & Riwayat Perangkat') }}
        </h2>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
            {{ __('Kelola dan pantau sesi aktif Anda pada berbagai perangkat. Anda dapat keluar dari sesi tertentu atau mengeluarkan semua sesi perangkat lain secara aman.') }}
        </p>
    </header>

    @if (config('session.driver') === 'redis')
        <div class="p-5 rounded-2xl border border-blue-100 bg-blue-50/50 text-blue-800 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center text-lg shrink-0">
                <i class="bi bi-cpu-fill"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm">Mode Performa Tinggi Aktif (Redis)</h4>
                <p class="text-xs text-blue-600/80 mt-1 leading-relaxed">
                    Sistem menggunakan <strong>Redis</strong> untuk penyimpanan sesi super cepat. Dalam mode ini, pemantauan riwayat detail perangkat individu dan opsi untuk mengeluarkan sesi lain secara terpisah dinonaktifkan demi performa optimal server Anda.
                </p>
            </div>
        </div>
    @elseif (config('session.driver') !== 'database')
        <div class="p-4 bg-amber-50 border border-amber-100 text-amber-800 rounded-2xl text-xs font-bold flex items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill text-lg"></i>
            <span>Driver sesi database harus diaktifkan untuk melihat sesi aktif.</span>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($sessions as $session)
                <div class="p-5 rounded-2xl border transition-all duration-300 flex items-center justify-between gap-4 {{ $session->is_current_device ? 'bg-blue-50/40 border-blue-100 shadow-sm shadow-blue-500/5' : 'bg-slate-50 border-slate-100 hover:bg-slate-100/50' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl {{ $session->is_current_device ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                            <i class="bi {{ $session->agent['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm text-slate-800">{{ $session->agent['browser'] }} on {{ $session->agent['platform'] }}</span>
                                @if ($session->is_current_device)
                                    <span class="px-2.5 py-0.5 bg-blue-600 text-white text-[9px] font-black uppercase tracking-wider rounded-full">Perangkat Ini</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 text-xs text-slate-500 mt-1">
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-globe"></i> {{ $session->ip_address }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-clock"></i> {{ $session->last_active }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if (!$session->is_current_device)
                        <form method="POST" action="{{ route('profile.sessions.revoke', $session->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan perangkat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 rounded-xl bg-slate-200 hover:bg-red-50 hover:text-red-600 text-slate-500 flex items-center justify-center transition-all duration-300 active:scale-95" title="Keluarkan Perangkat">
                                <i class="bi bi-box-arrow-right text-lg"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        @if (count($sessions) > 1)
            <div class="pt-4 border-t border-slate-100/50">
                <button type="button" id="btnOpenRevokeOthersModal" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-sm transition-all duration-300 active:scale-95">
                    <i class="bi bi-shield-x text-lg"></i>
                    <span>{{ __('Keluarkan Semua Perangkat Lain') }}</span>
                </button>
            </div>

            {{-- Revoke Others Modal --}}
            <div id="revokeOthersModal" class="fixed inset-0 z-[200] hidden">
                <div id="revokeOthersModalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div class="relative bg-white w-full max-w-md rounded-[32px] shadow-2xl p-8 overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-2 bg-slate-900 rounded-t-[32px]"></div>

                        <form method="POST" action="{{ route('profile.sessions.revoke-others') }}">
                            @csrf
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-slate-100 text-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                                <h2 class="text-xl font-black text-slate-800">
                                    {{ __('Konfirmasi Keamanan') }}
                                </h2>
                                <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                                    {{ __('Silakan masukkan kata sandi akun Anda untuk mengonfirmasi pengeluaran sesi aktif di seluruh perangkat lainnya.') }}
                                </p>
                            </div>

                            <div class="space-y-2 mb-6">
                                <label for="revoke_password" class="block text-[11px] font-black uppercase tracking-widest text-slate-400">
                                    Kata Sandi Anda
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-shield-lock text-lg"></i>
                                    </div>
                                    <input
                                        id="revoke_password"
                                        name="current_password"
                                        type="password"
                                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-semibold text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-500/30 focus:border-slate-800 transition-all"
                                        placeholder="Masukkan kata sandi Anda"
                                        required
                                    />
                                </div>
                                @error('current_password', 'revokeOthers')
                                    <p class="text-xs font-bold text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-3">
                                <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black text-sm transition-all hover:shadow-xl hover:shadow-slate-500/10 active:scale-95">
                                    {{ __('YA, KELUARKAN SEMUA PERANGKAT') }}
                                </button>
                                <button type="button" id="btnCloseRevokeOthersModal" class="w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition-all">
                                    {{ __('Batal') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endif
</section>

<script>
(function() {
    function openModal() {
        document.getElementById('revokeOthersModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('revokeOthersModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var btnOpen = document.getElementById('btnOpenRevokeOthersModal');
        var btnClose = document.getElementById('btnCloseRevokeOthersModal');
        var backdrop = document.getElementById('revokeOthersModalBackdrop');

        if (btnOpen) btnOpen.addEventListener('click', openModal);
        if (btnClose) btnClose.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);

        @if ($errors->hasBag('revokeOthers'))
            openModal();
        @endif
    });
})();
</script>
