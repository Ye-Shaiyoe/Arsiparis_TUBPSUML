<section class="space-y-6">
    <header>
        <h2 class="text-xl font-black text-red-600 tracking-tight">
            {{ __('Hapus Akun Permanen') }}
        </h2>
        <p class="mt-2 text-sm text-red-700/70 leading-relaxed">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data yang terkait akan dihapus secara permanen. Silakan unduh data penting sebelum melanjutkan.') }}
        </p>
    </header>

    <button type="button" id="btnOpenDeleteModal"
        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm transition-all duration-300 hover:shadow-lg hover:shadow-red-500/20 active:scale-95">
        <i class="bi bi-person-x-fill text-lg"></i>
        <span>{{ __('Hapus Akun Saya') }}</span>
    </button>

    {{-- Modal Konfirmasi (Vanilla JS) --}}
    <div id="deleteModal" class="fixed inset-0 z-[200] hidden">
        {{-- Backdrop --}}
        <div id="deleteModalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        {{-- Modal Content --}}
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white w-full max-w-md rounded-[32px] shadow-2xl p-8 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-red-600 rounded-t-[32px]"></div>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                            <i class="bi bi-exclamation-octagon-fill"></i>
                        </div>
                        <h2 class="text-xl font-black text-slate-800">
                            {{ __('Apakah Anda yakin?') }}
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            {{ __('Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi.') }}
                        </p>
                    </div>

                    <div class="space-y-2 mb-6">
                        <label for="delete_password" class="block text-[11px] font-black uppercase tracking-widest text-slate-400">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-shield-lock text-lg"></i>
                            </div>
                            <input
                                id="delete_password"
                                name="password"
                                type="password"
                                class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-semibold text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition-all"
                                placeholder="Masukkan kata sandi Anda"
                                required
                            />
                        </div>
                        @if ($errors->userDeletion->isNotEmpty())
                            <p class="text-xs font-bold text-red-600 mt-1">
                                {{ $errors->userDeletion->first('password') }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit"
                            class="w-full py-4 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black text-sm transition-all hover:shadow-xl hover:shadow-red-500/20 active:scale-95">
                            {{ __('YA, HAPUS PERMANEN') }}
                        </button>
                        <button type="button" id="btnCloseDeleteModal"
                            class="w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition-all">
                            {{ __('Batal') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    function openModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var btnOpen   = document.getElementById('btnOpenDeleteModal');
        var btnClose  = document.getElementById('btnCloseDeleteModal');
        var backdrop  = document.getElementById('deleteModalBackdrop');

        if (btnOpen)   btnOpen.addEventListener('click', openModal);
        if (btnClose)  btnClose.addEventListener('click', closeModal);
        if (backdrop)  backdrop.addEventListener('click', closeModal);

        // Buka otomatis jika ada error validasi dari server
        @if ($errors->userDeletion->isNotEmpty())
            openModal();
        @endif
    });
})();
</script>