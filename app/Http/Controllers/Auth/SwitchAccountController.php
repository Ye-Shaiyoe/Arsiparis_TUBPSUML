<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SwitchAccountController extends Controller
{
    /**
     * Generate dan simpan switch token untuk user yang sedang login.
     * Mengembalikan raw token (untuk disimpan di localStorage frontend).
     */
    public static function generateToken(User $user): string
    {
        $rawToken = Str::random(64);

        $user->forceFill([
            'switch_token'            => Hash::make($rawToken),
            'switch_token_expires_at' => now()->addDays(30),
        ])->save();

        return $rawToken;
    }

    /**
     * Endpoint untuk instant account switching.
     * POST /auth/switch-account
     */
    public function switch(Request $request)
    {
        $request->validate([
            'user_id'      => ['required', 'integer'],
            'switch_token' => ['required', 'string', 'size:64'],
        ]);

        $targetUser = User::find($request->integer('user_id'));

        if (
            ! $targetUser ||
            ! $targetUser->switch_token ||
            ! $targetUser->switch_token_expires_at ||
            $targetUser->switch_token_expires_at->isPast() ||
            ! Hash::check($request->switch_token, $targetUser->switch_token)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau kadaluarsa. Silakan login manual.',
            ], 401);
        }

        // Logout akun saat ini
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Login ke akun target
        Auth::login($targetUser, true);
        $request->session()->regenerate();

        // Perbarui token agar tetap fresh (30 hari rolling)
        $newToken = self::generateToken($targetUser);
        
        // Simpan ke session agar layout bisa baca CURRENT_USER.switch_token
        $request->session()->put('switch_token_raw', $newToken);

        // Tentukan redirect berdasarkan role
        if ($targetUser->isAdmin()) {
            if (! $targetUser->hasSelectedRole()) {
                $redirect = route('admin.role.select');
            } else {
                $redirect = route('admin.dashboard');
            }
        } elseif ($targetUser->isITSupport()) {
            $redirect = route('itsupport.dashboard');
        } else {
            $redirect = route('dashboard');
        }

        return response()->json([
            'success'   => true,
            'redirect'  => $redirect,
            'new_token' => $newToken,
            'user_id'   => $targetUser->id,
            'name'      => $targetUser->name,
            'email'     => $targetUser->email,
            'initials'  => strtoupper(substr($targetUser->name, 0, 2)),
            'role'      => $targetUser->getRoleLabel(),
            'photo'     => $targetUser->profile_photo ? \Illuminate\Support\Facades\Storage::url($targetUser->profile_photo) : null,
        ]);
    }
}
