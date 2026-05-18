<section class="space-y-6">
    <header class="mb-8">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">
            {{ __('Manajemen Multi-Akun') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ __('Kelola akun yang tersimpan di perangkat ini untuk beralih secara instan tanpa login ulang.') }}
        </p>
    </header>

    <div id="switch-accounts-profile-list" class="mt-6 space-y-4">
        <!-- Will be populated by JS -->
        <div class="animate-pulse space-y-4">
            <div class="h-24 bg-slate-100 rounded-2xl"></div>
            <div class="h-24 bg-slate-100 rounded-2xl"></div>
        </div>
    </div>

    <script>
        (function() {
            const STORAGE_KEY = 'bpsuml_saved_accounts';
            const currentUserId = {{ Auth::id() }};
            const SWITCH_URL = '{{ route("auth.switch") }}';
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function getAccounts() {
                try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); } catch(e) { return []; }
            }

            function removeAccount(id) {
                if(!confirm('Hapus akun ini dari daftar beralih instan?')) return;
                let accounts = getAccounts().filter(a => a.id !== id);
                localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
                renderAccounts();
            }

            function doSwitch(e, userId, token) {
                e.preventDefault();
                if (!token) {
                    alert('Sesi telah habis. Silakan login manual.');
                    return;
                }

                const btn = e.currentTarget;
                const originalContent = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = `<i class="bi bi-arrow-repeat animate-spin"></i> Beralih...`;

                fetch(SWITCH_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ user_id: userId, switch_token: token }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        let accounts = getAccounts();
                        const idx = accounts.findIndex(a => a.id === data.user_id);
                        if (idx >= 0) {
                            accounts[idx].switch_token = data.new_token;
                            accounts[idx].photo = data.photo;
                        }
                        localStorage.setItem(STORAGE_KEY, JSON.stringify(accounts));
                        window.location.href = data.redirect;
                    } else {
                        alert('Gagal beralih: ' + data.message);
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                })
                .catch(() => {
                    alert('Kesalahan jaringan.');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                });
            }

            function renderAccounts() {
                const accounts = getAccounts();
                const container = document.getElementById('switch-accounts-profile-list');
                
                if (!container) return;

                if (accounts.length === 0 || (accounts.length === 1 && accounts[0].id === currentUserId)) {
                    container.innerHTML = `
                        <div class="p-10 bg-slate-50 rounded-[24px] border border-dashed border-slate-200 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-slate-300 mx-auto mb-4 border border-slate-100 shadow-sm">
                                <i class="bi bi-person-plus text-3xl"></i>
                            </div>
                            <p class="text-slate-500 font-bold text-sm">Tidak ada akun lain tersimpan.</p>
                            <p class="text-slate-400 text-xs mt-1">Akun lain akan muncul di sini setelah Anda login di perangkat ini.</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = accounts.map(acc => {
                    const isCurrent = acc.id === currentUserId;
                    return `
                        <div class="flex flex-col sm:flex-row items-center justify-between p-6 bg-white border border-slate-100 rounded-[24px] shadow-sm hover:border-blue-200 transition-all ${isCurrent ? 'opacity-60 bg-slate-50' : 'hover:shadow-md hover:shadow-blue-100/50'}">
                            <div class="flex items-center gap-4 mb-4 sm:mb-0">
                                <div class="relative">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center text-slate-600 font-bold border-2 border-white shadow-md">
                                        ${acc.photo ? `<img src="${acc.photo}" class="w-full h-full object-cover">` : acc.initials}
                                    </div>
                                    ${isCurrent ? `<div class="absolute -top-2 -right-2 bg-blue-600 text-white w-6 h-6 rounded-full border-2 border-white flex items-center justify-center shadow-lg"><i class="bi bi-check-lg text-[10px]"></i></div>` : ''}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-black text-slate-800 truncate">${acc.name}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-bold text-blue-600 px-2 py-0.5 rounded-full bg-blue-50 border border-blue-100 uppercase tracking-tighter">${acc.role || 'User'}</span>
                                        <span class="text-xs text-slate-400 truncate">${acc.email}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                ${!isCurrent ? `
                                    <button onclick="window.switchAccProfile(${acc.id}, '${acc.switch_token}', event)" 
                                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                                        <i class="bi bi-arrow-left-right"></i> Beralih
                                    </button>
                                    <button onclick="window.removeAccProfile(${acc.id})" 
                                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-red-500 border border-red-100 rounded-xl text-xs font-bold hover:bg-red-50 transition-all">
                                        <i class="bi bi-x-circle"></i> Hapus
                                    </button>
                                ` : `
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-[2px]">Sedang Digunakan</span>
                                `}
                            </div>
                        </div>
                    `;
                }).join('');
            }

            window.removeAccProfile = removeAccount;
            window.switchAccProfile = (id, token, e) => doSwitch(e, id, token);

            renderAccounts();
            
            // Re-render on turbo load
            document.addEventListener('turbo:load', renderAccounts);
        })();
    </script>
</section>
