<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $sessions = [];
        if (config('session.driver') === 'database') {
            $sessions = DB::table('sessions')
                ->where('user_id', $request->user()->id)
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) use ($request) {
                    $agent = $this->parseUserAgent($session->user_agent);
                    return (object) [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'is_current_device' => $session->id === $request->session()->getId(),
                        'agent' => $agent,
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    ];
                });
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'sessions' => $sessions,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Fill name & email (NIP handled separately due to encrypted cast)
        $user->fill($request->safe()->only(['name', 'email']));

        // Set NIP explicitly (encrypted cast handles encryption automatically)
        if ($request->has('nip')) {
            $nipValue = $request->input('nip') ?: null;
            $user->nip = $nipValue;
            // Selalu sync nip_hash agar lookup via NIP tetap akurat
            $user->nip_hash = $nipValue ? \App\Models\User::hashNip($nipValue) : null;
        }

        // Handle base64 encoded profile photo from cropper
        if ($request->filled('cropped_photo')) {
            $base64Image = $request->input('cropped_photo');
            $imageParts = explode(';base64,', $base64Image);
            
            if (count($imageParts) === 2) {
                $imageTypeAux = explode('image/', $imageParts[0]);
                $imageType = isset($imageTypeAux[1]) ? explode(';', $imageTypeAux[1])[0] : 'png';
                $imageBase64 = base64_decode($imageParts[1]);
                
                // Validate magic bytes untuk mencegah RCE (validasi konten file, bukan extension)
                if (!$this->isValidImageMagicBytes($imageBase64)) {
                    return Redirect::route('profile.edit')->withErrors(['profile_photo' => 'File bukan merupakan gambar yang valid.']);
                }
                
                $fileName = 'profile_photos/' . uniqid() . '.' . $imageType;

                if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
                }

                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);
                $user->profile_photo = $fileName;
            }
        } elseif ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }
            
            // Validasi magic bytes sebelum menyimpan file
            $filePath = $request->file('profile_photo')->path();
            if (!$this->isValidImageMagicBytes(file_get_contents($filePath))) {
                return Redirect::route('profile.edit')->withErrors(['profile_photo' => 'File bukan merupakan gambar yang valid.']);
            }
            
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Validasi magic bytes untuk image files.
     * Mencegah upload file berbahaya yang di-disguise sebagai image.
     * Fix untuk RCE prevention - validasi konten file, bukan hanya extension.
     */
    private function isValidImageMagicBytes(string $fileContent): bool
    {
        if (empty($fileContent)) {
            return false;
        }

        // Magic bytes untuk image formats yang diizinkan
        $validSignatures = [
            'JPEG' => [0xFF, 0xD8, 0xFF],
            'PNG'  => [0x89, 0x50, 0x4E, 0x47],
            'GIF87' => [0x47, 0x49, 0x46, 0x38, 0x37],
            'GIF89' => [0x47, 0x49, 0x46, 0x38, 0x39],
            'WEBP' => [0x52, 0x49, 0x46, 0x46], // RIFF (WebP uses RIFF container)
        ];

        $fileBytes = array_values(unpack('C*', substr($fileContent, 0, 12)));

        foreach ($validSignatures as $format => $signature) {
            if (count($fileBytes) < count($signature)) {
                continue;
            }

            $match = true;
            for ($i = 0; $i < count($signature); $i++) {
                if ($fileBytes[$i] !== $signature[$i]) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                // Extra check untuk WebP
                if ($format === 'WEBP' && strpos($fileContent, 'WEBP') === false) {
                    continue;
                }
                return true;
            }
        }

        // Fallback: gunakan getimagesize untuk extra validation
        $tmpFile = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmpFile, $fileContent);
        $imageInfo = @getimagesize($tmpFile);
        @unlink($tmpFile);

        return $imageInfo !== false && in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP]);
    }

    /**
     * Revoke a specific active session.
     */
    public function revokeSession(Request $request, string $sessionId): RedirectResponse
    {
        if (config('session.driver') === 'database') {
            // Ensure the session belongs to the authenticated user and is not the current session
            if ($sessionId !== $request->session()->getId()) {
                DB::table('sessions')
                    ->where('user_id', $request->user()->id)
                    ->where('id', $sessionId)
                    ->delete();
            }
        }

        return Redirect::route('profile.edit')->with('status', 'session-revoked');
    }

    /**
     * Revoke all other active sessions (logout other devices).
     */
    public function revokeAllOtherSessions(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        if (config('session.driver') === 'database') {
            DB::table('sessions')
                ->where('user_id', $request->user()->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        // Laravel standard logout other devices
        Auth::logoutOtherDevices($request->input('current_password'));

        return Redirect::route('profile.edit')->with('status', 'all-other-sessions-revoked');
    }

    /**
     * Parse User Agent helper.
     */
    private function parseUserAgent(string $userAgent): array
    {
        $platform = 'Unknown OS';
        $browser = 'Unknown Browser';
        $isMobile = false;

        // Platform detection
        if (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
            $isMobile = true;
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
            $isMobile = true;
        }

        // Browser detection
        if (preg_match('/chrome/i', $userAgent) && !preg_match('/edge|edg/i', $userAgent) && !preg_match('/opr/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/edge|edg/i', $userAgent)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/msie|trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/vivaldi/i', $userAgent)) {
            $browser = 'Vivaldi';
        } elseif (preg_match('/yabrowser/i', $userAgent)) {
            $browser = 'Yandex Browser';
        } elseif (preg_match('/samsungbrowser/i', $userAgent)) {
            $browser = 'Samsung Internet';
        } elseif (preg_match('/ucbrowser/i', $userAgent)) {
            $browser = 'UC Browser';
        } else {
            $browser = 'Unknown';
        }

        return [
            'platform' => $platform,
            'browser' => $browser,
            'is_mobile' => $isMobile,
            'icon' => $isMobile ? 'bi-phone' : 'bi-laptop'
        ];
    }

    /**
     * Update the user's digital signature and TTE PIN.
     */
    public function updateTte(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Validasi PIN lama jika sudah ada
        if ($user->signature_pin && ($request->filled('signature_pin') || $request->filled('cropped_signature') || $request->hasFile('signature_file'))) {
            $request->validate([
                'current_signature_pin' => ['required', 'string'],
            ]);

            if (!\Illuminate\Support\Facades\Hash::check($request->current_signature_pin, $user->signature_pin)) {
                return Redirect::to(route('profile.edit') . '#tte-section')->withErrors(['current_signature_pin' => 'PIN TTE saat ini tidak valid.']);
            }
        }

        // 2. Validasi input PIN baru & file
        $request->validate([
            'signature_pin' => ['nullable', 'string', 'min:6', 'max:20', 'confirmed'],
            'signature_file' => ['nullable', 'image', 'mimes:png', 'max:2048'], // png disarankan karena transparansi
            'cropped_signature' => ['nullable', 'string'],
        ]);

        // 3. Proses Upload/Crop Tanda Tangan
        if ($request->filled('cropped_signature')) {
            $base64Image = $request->input('cropped_signature');
            $imageParts = explode(';base64,', $base64Image);
            
            if (count($imageParts) === 2) {
                $imageTypeAux = explode('image/', $imageParts[0]);
                $imageType = isset($imageTypeAux[1]) ? explode(';', $imageTypeAux[1])[0] : 'png';
                $imageBase64 = base64_decode($imageParts[1]);
                
                if (!$this->isValidImageMagicBytes($imageBase64)) {
                    return Redirect::to(route('profile.edit') . '#tte-section')->withErrors(['signature_file' => 'File tanda tangan bukan gambar PNG/transparan yang valid.']);
                }
                
                $fileName = 'signatures/' . uniqid() . '.' . $imageType;

                if ($user->signature_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->signature_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->signature_path);
                }

                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);
                $user->signature_path = $fileName;
            }
        } elseif ($request->hasFile('signature_file')) {
            if ($user->signature_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->signature_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->signature_path);
            }
            
            $filePath = $request->file('signature_file')->path();
            if (!$this->isValidImageMagicBytes(file_get_contents($filePath))) {
                return Redirect::to(route('profile.edit') . '#tte-section')->withErrors(['signature_file' => 'File tanda tangan bukan gambar yang valid.']);
            }
            
            $path = $request->file('signature_file')->store('signatures', 'public');
            $user->signature_path = $path;
        }

        // 4. Proses PIN Baru
        if ($request->filled('signature_pin')) {
            $user->signature_pin = $request->signature_pin;
        }

        $user->save();

        return Redirect::to(route('profile.edit') . '#tte-section')->with('status', 'tte-updated');
    }
}
