{{-- Cropper.js CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
<style>
    #cropModal { display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; background: rgba(15,23,42,0.7); backdrop-filter: blur(4px); }
    #cropModal.show { display: flex; }
    .cropper-container-wrap { max-height: 400px; background: #0f172a; border-radius: 16px; overflow: hidden; }
    .cropper-container { max-height: 400px !important; }
</style>


<section class="space-y-8">
    <header>
        <h2 class="text-xl font-black text-slate-800 tracking-tight">Informasi Pribadi</h2>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
            Perbarui foto profil dan detail identitas akun Anda. Foto profil yang jelas memudahkan rekan kerja mengenali Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('patch')

        {{-- Input file tersembunyi + base64 dari cropper --}}
        <input type="file" id="profile_photo_raw" name="profile_photo_raw" class="hidden" accept="image/*">
        <input type="hidden" id="cropped_photo" name="cropped_photo">

        {{-- Section: Foto Profil --}}
        <div class="flex flex-col items-center sm:flex-row gap-8 pb-8 border-b border-slate-100/60">
            {{-- Avatar Preview --}}
            <div class="relative group flex-shrink-0">
                <div class="w-36 h-36 rounded-[2rem] overflow-hidden ring-4 ring-white shadow-2xl shadow-slate-200 relative cursor-pointer"
                     onclick="document.getElementById('profile_photo_raw').click()">
                    @if($user->profile_photo)
                        <img id="avatar-preview"
                             src="{{ Storage::url($user->profile_photo) }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             alt="Avatar">
                    @else
                        <div id="avatar-initials"
                             class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-700 flex items-center justify-center text-white text-5xl font-black">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <img id="avatar-preview" class="w-full h-full object-cover hidden absolute inset-0" alt="Avatar">
                    @endif

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <i class="bi bi-camera-fill text-white text-3xl"></i>
                        <span class="text-white text-[10px] font-bold mt-1">Ubah Foto</span>
                    </div>
                </div>

                {{-- Badge online --}}
                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-emerald-500 border-4 border-white rounded-full flex items-center justify-center shadow-lg">
                    <i class="bi bi-check-lg text-white text-xs font-black"></i>
                </div>
            </div>

            {{-- Info & Upload Button --}}
            <div class="space-y-3 text-center sm:text-left">
                <p class="text-sm font-bold text-slate-700">{{ $user->name }}</p>
                <p class="text-xs text-slate-400">Format: JPG, PNG, WebP · Maks. 2MB</p>
                <button type="button"
                        onclick="document.getElementById('profile_photo_raw').click()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all hover:shadow-lg hover:shadow-blue-500/20 active:scale-95">
                    <i class="bi bi-upload"></i> Pilih & Crop Foto
                </button>
                <p class="text-[10px] text-slate-400">Klik avatar atau tombol di atas untuk mengunggah</p>
                <x-input-error :messages="$errors->get('profile_photo')" class="mt-1" />
            </div>
        </div>

        {{-- Row: Nama & NIP --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <x-input-label for="name" value="Nama Lengkap" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                        <i class="bi bi-person text-lg"></i>
                    </div>
                    <x-text-input id="name" name="name" type="text"
                        class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all font-semibold text-slate-700"
                        :value="old('name', $user->name)" required autofocus autocomplete="name" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="text-xs font-bold" />
            </div>

            <div class="space-y-3">
                <x-input-label for="nip" value="NIP (Opsional)" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                        <i class="bi bi-card-text text-lg"></i>
                    </div>
                    <x-text-input id="nip" name="nip" type="text"
                        class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all font-semibold text-slate-700"
                        :value="old('nip', $user->nip)" autocomplete="username" placeholder="19xxxxxxxxxxxxxxx" />
                </div>
                <x-input-error :messages="$errors->get('nip')" class="text-xs font-bold" />
            </div>
        </div>

        {{-- Email --}}
        <div class="space-y-3">
            <x-input-label for="email" value="Alamat Email" class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="bi bi-envelope text-lg"></i>
                </div>
                <x-text-input id="email" name="email" type="email"
                    class="block w-full pl-12 pr-5 py-4 bg-slate-50/50 border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all font-semibold text-slate-700"
                    :value="old('email', $user->email)" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="text-xs font-bold" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-4 rounded-xl bg-amber-50 border border-amber-100 flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-amber-500"></i>
                    <p class="text-sm text-amber-700 font-semibold">
                        {{ __('Email belum terverifikasi.') }}
                        <button form="send-verification" class="ml-1 underline hover:text-amber-900 transition-colors">Kirim ulang email verifikasi.</button>
                    </p>
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-sm transition-all hover:shadow-xl hover:shadow-blue-500/20 active:scale-95">
                <i class="bi bi-cloud-arrow-up-fill text-lg"></i>
                <span>Simpan Perubahan</span>
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i> Berhasil disimpan
                </p>
            @endif
        </div>
    </form>
</section>

{{-- ===== CROP MODAL ===== --}}
<div id="cropModal" class="items-center justify-center inset-0 bg-slate-900/70 backdrop-blur-sm">
    <div class="relative bg-white w-full max-w-xl rounded-[28px] shadow-2xl overflow-hidden mx-4">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-crop text-white font-bold"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 text-base">Atur Foto Profil</h3>
                    <p class="text-[11px] text-slate-400">Geser & zoom untuk mengatur posisi foto</p>
                </div>
            </div>
            <button type="button" id="btnCancelCrop" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                <i class="bi bi-x-lg text-slate-500 text-sm"></i>
            </button>
        </div>

        {{-- Cropper Area --}}
        <div class="p-6">
            <div class="cropper-container-wrap">
                <img id="cropImage" src="" alt="Crop" style="max-width:100%; display:block;">
            </div>

            {{-- Crop Controls --}}
            <div class="flex items-center justify-center gap-2 mt-4">
                <button type="button" onclick="cropperInstance.zoom(0.1)" title="Zoom In"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                    <i class="bi bi-zoom-in"></i>
                </button>
                <button type="button" onclick="cropperInstance.zoom(-0.1)" title="Zoom Out"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                    <i class="bi bi-zoom-out"></i>
                </button>
                <button type="button" onclick="cropperInstance.rotate(-90)" title="Putar Kiri"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
                <button type="button" onclick="cropperInstance.rotate(90)" title="Putar Kanan"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button type="button" onclick="cropperInstance.reset()" title="Reset"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="flex gap-3 px-6 pb-6">
            <button type="button" id="btnApplyCrop"
                class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-sm transition-all hover:shadow-lg hover:shadow-blue-500/20 active:scale-95 flex items-center justify-center gap-2">
                <i class="bi bi-check2-circle text-lg"></i>
                Gunakan Foto Ini
            </button>
            <button type="button" id="btnCancelCrop2"
                class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition-all">
                Batal
            </button>
        </div>
    </div>
</div>

{{-- Cropper.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
(function () {
    var cropModal      = document.getElementById('cropModal');
    var cropImage      = document.getElementById('cropImage');
    var fileInput      = document.getElementById('profile_photo_raw');
    var croppedInput   = document.getElementById('cropped_photo');
    var avatarPreview  = document.getElementById('avatar-preview');
    var avatarInitials = document.getElementById('avatar-initials');
    var cropperInstance = null;

    // Expose cropperInstance to window so buttons (onclick="cropperInstance.zoom...") can access it
    window.cropperInstance = null;

    function openModal() { cropModal.classList.add('show'); }
    function closeModal() {
        cropModal.classList.remove('show');
        if (window.cropperInstance) {
            window.cropperInstance.destroy();
            window.cropperInstance = null;
        }
        fileInput.value = '';
    }

    fileInput.addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;

        var reader = new FileReader();
        reader.onload = function (e) {
            cropImage.src = e.target.result;
            openModal();

            // Destroy existing cropper if any
            if (window.cropperInstance) window.cropperInstance.destroy();

            window.cropperInstance = new Cropper(cropImage, {
                aspectRatio: 1,           // Square crop (cocok untuk foto profil lingkaran)
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.85,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        };
        reader.readAsDataURL(file);
    });

    // Apply Crop
    document.getElementById('btnApplyCrop').addEventListener('click', function () {
        if (!window.cropperInstance) return;

        var canvas = window.cropperInstance.getCroppedCanvas({ width: 400, height: 400 });
        var base64 = canvas.toDataURL('image/png');

        // Set hidden input
        croppedInput.value = base64;

        // Update preview
        avatarPreview.src = base64;
        avatarPreview.classList.remove('hidden');
        if (avatarInitials) avatarInitials.classList.add('hidden');

        closeModal();
    });

    // Cancel buttons
    document.getElementById('btnCancelCrop').addEventListener('click', closeModal);
    document.getElementById('btnCancelCrop2').addEventListener('click', closeModal);
})();
</script>
