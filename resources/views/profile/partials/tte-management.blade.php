<section class="space-y-8">
    <header>
        <h2 class="text-xl font-black text-slate-800 tracking-tight">Tanda Tangan Digital & TTE</h2>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
            Kelola tanda tangan elektronik (TTE) Anda untuk memverifikasi dokumen secara sah di dalam sistem.
        </p>
    </header>

    <form method="post" action="{{ route('profile.tte.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('patch')

        <input type="hidden" id="cropped_signature" name="cropped_signature">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-8 border-b border-slate-100/60">
            <!-- Left: Current Signature -->
            <div class="space-y-4">
                <x-input-label value="Tanda Tangan Aktif" class="text-[11px] font-black uppercase tracking-widest text-slate-400" />
                <div class="p-6 rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center justify-center min-h-[200px] relative overflow-hidden group">
                    @if($user->signature_path)
                        <img id="signature-preview" src="{{ Storage::url($user->signature_path) }}" class="max-h-[140px] object-contain relative z-10 filter drop-shadow-md" alt="Tanda Tangan">
                    @else
                        <div id="no-signature-placeholder" class="text-center py-6 text-slate-400">
                            <i class="bi bi-pencil-square text-4xl block mb-2 opacity-50"></i>
                            <span class="text-xs font-semibold">Belum ada tanda tangan</span>
                        </div>
                    @endif
                    <img id="new-signature-preview" class="max-h-[140px] object-contain hidden relative z-10" alt="Pratinjau Baru">
                </div>
            </div>

            <!-- Right: Interactive Canvas / Upload Signature -->
            <div class="space-y-4">
                <x-input-label value="Buat / Unggah Baru" class="text-[11px] font-black uppercase tracking-widest text-slate-400" />
                
                <!-- Nav Tabs for Draw vs Upload -->
                <div class="flex gap-2 p-1 bg-slate-100/50 rounded-xl">
                    <button type="button" onclick="switchTteTab('draw')" id="btn-tab-draw" class="flex-1 py-2 text-xs font-bold rounded-lg text-slate-700 bg-white shadow-sm transition-all">Gambarkan</button>
                    <button type="button" onclick="switchTteTab('upload')" id="btn-tab-upload" class="flex-1 py-2 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-700 transition-all">Unggah PNG</button>
                </div>

                <!-- Tab content: Draw -->
                <div id="tte-tab-draw" class="space-y-3">
                    <div class="relative bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-inner">
                        <canvas id="signature-canvas" class="w-full h-[180px] bg-slate-50/50 cursor-crosshair" style="touch-action: none;"></canvas>
                        <div class="absolute bottom-2 right-2 flex gap-1">
                            <button type="button" id="btn-clear-canvas" class="btn btn-sm bg-white border shadow-sm px-2.5 py-1 text-[11px] font-bold rounded-lg hover:bg-slate-50 text-slate-600">Hapus Kanvas</button>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400"><i class="bi bi-info-circle me-1"></i>Gunakan mouse atau layar sentuh untuk menandatangani di area kanvas.</p>
                </div>

                <!-- Tab content: Upload -->
                <div id="tte-tab-upload" class="space-y-3 hidden">
                    <div class="p-6 bg-white border border-slate-200 rounded-2xl text-center cursor-pointer hover:border-blue-500 transition-all" onclick="document.getElementById('signature_file').click()">
                        <i class="bi bi-cloud-arrow-up text-3xl text-slate-400 block mb-2"></i>
                        <span class="text-xs font-bold text-slate-600 block">Pilih File Tanda Tangan</span>
                        <span class="text-[10px] text-slate-400">PNG Transparan disarankan (Maks. 2MB)</span>
                        <input type="file" id="signature_file" name="signature_file" accept="image/png" class="hidden">
                    </div>
                </div>
            </div>
        </div>

        <!-- PIN TTE Form Section -->
        <div class="space-y-6">
            <header>
                <h4 class="text-md font-bold text-slate-700">Keamanan PIN TTE</h4>
                <p class="text-xs text-slate-400">PIN TTE digunakan untuk memverifikasi tanda tangan digital Anda ketika melakukan persetujuan surat resmi.</p>
            </header>

            @if($user->signature_pin)
                <div class="space-y-3">
                    <x-input-label for="current_signature_pin" value="PIN TTE Saat Ini" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
                    <div class="relative group max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="bi bi-shield-lock text-lg"></i>
                        </div>
                        <input id="current_signature_pin" name="current_signature_pin" type="password" maxlength="20"
                            class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all font-semibold text-slate-700"
                            placeholder="••••••" autocomplete="current-password" />
                    </div>
                    <x-input-error :messages="$errors->get('current_signature_pin')" class="text-xs font-bold" />
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <x-input-label for="signature_pin" value="{{ $user->signature_pin ? 'PIN TTE Baru' : 'Buat PIN TTE' }}" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="bi bi-key-fill text-lg"></i>
                        </div>
                        <input id="signature_pin" name="signature_pin" type="password" maxlength="20"
                            class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all font-semibold text-slate-700"
                            placeholder="Min. 6 digit" autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('signature_pin')" class="text-xs font-bold" />
                </div>

                <div class="space-y-3">
                    <x-input-label for="signature_pin_confirmation" value="Konfirmasi PIN TTE" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="bi bi-key-fill text-lg"></i>
                        </div>
                        <input id="signature_pin_confirmation" name="signature_pin_confirmation" type="password" maxlength="20"
                            class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all font-semibold text-slate-700"
                            placeholder="Ulangi PIN" autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('signature_pin_confirmation')" class="text-xs font-bold" />
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" id="btn-save-tte"
                class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-sm transition-all hover:shadow-xl hover:shadow-blue-500/20 active:scale-95">
                <i class="bi bi-cloud-arrow-up-fill text-lg"></i>
                <span>Simpan Tanda Tangan & PIN</span>
            </button>

            @if (session('status') === 'tte-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i> TTE Berhasil disimpan
                </p>
            @endif
        </div>
    </form>
</section>

<script>
    function switchTteTab(tab) {
        const drawTab = document.getElementById('tte-tab-draw');
        const uploadTab = document.getElementById('tte-tab-upload');
        const btnDraw = document.getElementById('btn-tab-draw');
        const btnUpload = document.getElementById('btn-tab-upload');

        if (tab === 'draw') {
            drawTab.classList.remove('hidden');
            uploadTab.classList.add('hidden');
            btnDraw.classList.add('bg-white', 'shadow-sm', 'text-slate-700');
            btnDraw.classList.remove('text-slate-500');
            btnUpload.classList.remove('bg-white', 'shadow-sm', 'text-slate-700');
            btnUpload.classList.add('text-slate-500');
        } else {
            drawTab.classList.add('hidden');
            uploadTab.classList.remove('hidden');
            btnUpload.classList.add('bg-white', 'shadow-sm', 'text-slate-700');
            btnUpload.classList.remove('text-slate-500');
            btnDraw.classList.remove('bg-white', 'shadow-sm', 'text-slate-700');
            btnDraw.classList.add('text-slate-500');
        }
    }

    document.addEventListener('turbo:load', function() {
        const canvas = document.getElementById('signature-canvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let drawing = false;

        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;
            ctx.strokeStyle = '#0f172a'; // slate-900
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
        }
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        function getMousePos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        function startDrawing(e) {
            drawing = true;
            const pos = getMousePos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }

        // Drawing inside the canvas boundary
        function draw(e) {
            if (!drawing) return;
            const pos = getMousePos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            e.preventDefault();
        }

        function stopDrawing() {
            if (!drawing) return;
            drawing = false;
            ctx.closePath();
            document.getElementById('cropped_signature').value = canvas.toDataURL('image/png');
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDrawing);

        document.getElementById('btn-clear-canvas').addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('cropped_signature').value = '';
            const newPreview = document.getElementById('new-signature-preview');
            if (newPreview) newPreview.classList.add('hidden');
            const signaturePreview = document.getElementById('signature-preview');
            if (signaturePreview) signaturePreview.classList.remove('opacity-25');
        });

        const sigFile = document.getElementById('signature_file');
        if (sigFile) {
            sigFile.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const newPreview = document.getElementById('new-signature-preview');
                        if (newPreview) {
                            newPreview.src = e.target.result;
                            newPreview.classList.remove('hidden');
                        }
                        const placeholder = document.getElementById('no-signature-placeholder');
                        if (placeholder) placeholder.classList.add('hidden');
                        const signaturePreview = document.getElementById('signature-preview');
                        if (signaturePreview) signaturePreview.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
