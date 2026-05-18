<section class="space-y-8">
    <header>
        <h2 class="text-xl font-black text-slate-800 tracking-tight">
            Keamanan Akun
        </h2>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
            Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk menjaga keamanan identitas digital Anda.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-8"
        x-data="{ 
            loading: false,
            errors: {},
            successMsg: false,
            submitForm(e) {
                this.loading = true;
                this.errors = {};
                this.successMsg = false;
                let formData = new FormData(e.target);
                
                fetch(e.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    this.loading = false;
                    
                    if (response.status === 422) {
                        const data = await response.json();
                        this.errors = data.errors || {};
                    } else if (response.ok) {
                        this.successMsg = true;
                        e.target.reset();
                        setTimeout(() => this.successMsg = false, 3000);
                    } else {
                        alert('Terjadi kesalahan pada server. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    this.loading = false;
                    alert('Gagal terhubung ke server.');
                });
            }
        }"
        @submit.prevent="submitForm"
    >
        @csrf
        @method('put')

        {{-- Password Saat Ini --}}
        <div class="space-y-3">
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600 text-slate-400">
                    <i class="bi bi-shield-lock text-lg"></i>
                </div>
                <x-text-input id="update_password_current_password" name="current_password" type="password" 
                    class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all duration-300 font-semibold text-slate-700" 
                    autocomplete="current-password" placeholder="••••••••" />
            </div>
            
            {{-- Error Backend (Traditional fallback) --}}
            @if($errors->updatePassword->get('current_password'))
                <ul x-show="!errors.current_password" class="mt-2 text-xs font-bold text-red-600 space-y-1">
                    @foreach ((array) $errors->updatePassword->get('current_password') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
            {{-- Error AJAX --}}
            <template x-if="errors.current_password">
                <ul class="mt-2 text-xs font-bold text-red-600 space-y-1">
                    <template x-for="err in errors.current_password">
                        <li x-text="err"></li>
                    </template>
                </ul>
            </template>
        </div>

        {{-- Password Baru & Konfirmasi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- New Password --}}
            <div class="space-y-3">
                <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600 text-slate-400">
                        <i class="bi bi-key-fill text-lg"></i>
                    </div>
                    <x-text-input id="update_password_password" name="password" type="password" 
                        class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all duration-300 font-semibold text-slate-700" 
                        autocomplete="new-password" placeholder="Min. 8 karakter" />
                </div>
                
                {{-- Error Backend --}}
                @if($errors->updatePassword->get('password'))
                    <ul x-show="!errors.password" class="mt-2 text-xs font-bold text-red-600 space-y-1">
                        @foreach ((array) $errors->updatePassword->get('password') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
                {{-- Error AJAX --}}
                <template x-if="errors.password">
                    <ul class="mt-2 text-xs font-bold text-red-600 space-y-1">
                        <template x-for="err in errors.password">
                            <li x-text="err"></li>
                        </template>
                    </ul>
                </template>
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-3">
                <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600 text-slate-400">
                        <i class="bi bi-shield-check text-lg"></i>
                    </div>
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                        class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all duration-300 font-semibold text-slate-700" 
                        autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
                </div>
                
                {{-- Error Backend --}}
                @if($errors->updatePassword->get('password_confirmation'))
                    <ul x-show="!errors.password_confirmation" class="mt-2 text-xs font-bold text-red-600 space-y-1">
                        @foreach ((array) $errors->updatePassword->get('password_confirmation') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
                {{-- Error AJAX --}}
                <template x-if="errors.password_confirmation">
                    <ul class="mt-2 text-xs font-bold text-red-600 space-y-1">
                        <template x-for="err in errors.password_confirmation">
                            <li x-text="err"></li>
                        </template>
                    </ul>
                </template>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" :disabled="loading" class="inline-flex items-center gap-2 px-8 py-4 bg-slate-900 hover:bg-black text-white rounded-2xl font-black text-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-500/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                <i x-show="!loading" class="bi bi-shield-check text-lg"></i>
                <i x-show="loading" class="bi bi-arrow-repeat text-lg animate-spin" style="display: none;"></i>
                <span x-text="loading ? 'Memeriksa...' : 'Perbarui Keamanan'"></span>
            </button>

            {{-- Success Message --}}
            <p x-show="successMsg" x-transition.opacity 
               class="text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-lg" style="display: none;">
                <i class="bi bi-check-circle-fill mr-1"></i> Sandi berhasil diubah
            </p>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show && !successMsg" x-transition x-init="setTimeout(() => show = false, 2000)" 
                   class="text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-lg">
                    <i class="bi bi-check-circle-fill mr-1"></i> Sandi berhasil diubah
                </p>
            @endif
        </div>
    </form>
</section>