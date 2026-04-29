<section>
    <header>
        <div class="flex items-center gap-2 mb-2">
            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                {{ __('Profile Information') }}
            </h2>
        </div>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Update your account's profile information and email address. Keep it up to date to ensure everything runs smoothly.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Include Cropper.js CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
        
        <style>
            /* Circular Crop Mask */
            .cropper-view-box,
            .cropper-face {
                border-radius: 50%;
            }
            /* The cropper image container */
            .img-container img {
                max-width: 100%;
                display: block;
            }
            
            #cropperModal {
                display: none;
                position: fixed;
                z-index: 50;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                align-items: center;
                justify-content: center;
            }
        </style>

        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
            
            <div class="mt-2 mb-4 flex items-center gap-4">
                <img id="avatar-preview" src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : asset('images/default-avatar.png') }}" 
                     alt="Profile Photo" class="h-20 w-20 rounded-full object-cover border-2 border-indigo-200"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random';">
                
                <label for="profile_photo" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Change Picture
                </label>
                <!-- Actual file input is hidden -->
                <input id="profile_photo" type="file" accept="image/*" class="hidden" />
            </div>
            
            <!-- Hidden input to store cropped base64 data -->
            <input type="hidden" name="cropped_photo" id="cropped_photo">
            
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <!-- Cropper Modal -->
        <div id="cropperModal" style="display: none; position: fixed; z-index: 50; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center; padding: 1rem;">
            <div style="background: white; border-radius: 0.5rem; width: 100%; max-width: 500px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center" style="flex-shrink: 0;">
                    <h3 class="text-lg font-medium text-gray-900" style="margin:0;">Crop your new profile picture</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeCropperModal()" style="background:transparent;border:none;cursor:pointer;">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:24px;height:24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4" style="flex: 1; overflow-y: auto;">
                    <div class="img-container" style="max-height: 50vh; display: flex; justify-content: center; background: #eee;">
                        <img id="cropper-image" src="" alt="Picture to crop" style="max-width: 100%;">
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 flex justify-end gap-2 border-t border-gray-200" style="flex-shrink: 0; display:flex; gap:10px; justify-content:flex-end; background:#f9fafb;">
                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50" onclick="closeCropperModal()">Cancel</button>
                    <button type="button" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="btn-crop" style="background-color:#4f46e5;color:white;border:none;cursor:pointer;">
                        Set new profile picture
                    </button>
                </div>
            </div>
        </div>

        <!-- Include Cropper.js Script -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
        <script>
            let cropper;
            const inputImage = document.getElementById('profile_photo');
            const cropperModal = document.getElementById('cropperModal');
            const cropperImage = document.getElementById('cropper-image');
            const avatarPreview = document.getElementById('avatar-preview');
            const croppedPhotoInput = document.getElementById('cropped_photo');
            const btnCrop = document.getElementById('btn-crop');

            function closeCropperModal() {
                cropperModal.style.display = 'none';
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                inputImage.value = ''; // Reset input so same file can be selected again
            }

            inputImage.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const file = files[0];
                    if (/^image\/\w+/.test(file.type)) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            cropperImage.src = event.target.result;
                            cropperModal.style.display = 'flex';
                            
                            if (cropper) {
                                cropper.destroy();
                            }
                            
                            cropper = new Cropper(cropperImage, {
                                aspectRatio: 1,
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 1,
                                restore: false,
                                guides: false,
                                center: false,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        };
                        reader.readAsDataURL(file);
                    } else {
                        alert('Please select an image file.');
                    }
                }
            });

            btnCrop.addEventListener('click', function() {
                if (!cropper) return;
                
                // Get cropped canvas
                const canvas = cropper.getCroppedCanvas({
                    width: 256,
                    height: 256,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });
                
                // Set preview and hidden input
                const dataUrl = canvas.toDataURL('image/png');
                avatarPreview.src = dataUrl;
                croppedPhotoInput.value = dataUrl;
                
                closeCropperModal();
            });
        </script>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="nip" :value="__('NIP (Nomor Induk Pegawai)')" />
            <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" :value="old('nip', $user->nip)" autocomplete="off" placeholder="Masukkan NIP Anda (jika ada)" />
            <x-input-error class="mt-2" :messages="$errors->get('nip')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
