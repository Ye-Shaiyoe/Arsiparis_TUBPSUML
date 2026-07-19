<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Rate limiting: max 5 percobaan registrasi per IP per 5 menit
        $throttleKey = 'register|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan registrasi. Coba lagi dalam {$seconds} detik.",
            ])->withInput();
        }
        RateLimiter::hit($throttleKey, 300); // decay 5 menit

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'nip'      => ['nullable', 'string', 'regex:/^\d{18}$/', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nip.regex' => 'NIP harus tepat 18 digit angka.',
        ]);

        // Verifikasi Google reCAPTCHA v2
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (empty($recaptchaToken)) {
            return back()->withErrors(['recaptcha' => 'Harap selesaikan verifikasi reCAPTCHA terlebih dahulu.'])->withInput();
        }

        $recaptchaHttp = app()->isLocal()
            ? Http::withoutVerifying()->asForm()
            : Http::asForm();

        $recaptchaVerify = $recaptchaHttp->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.v2_secret'),
            'response' => $recaptchaToken,
            'remoteip' => $request->ip(),
        ]);

        if (! $recaptchaVerify->json('success')) {
            $errCodes = implode(', ', $recaptchaVerify->json('error-codes', []));
            \Illuminate\Support\Facades\Log::warning('reCAPTCHA gagal', [
                'error-codes' => $errCodes,
                'ip'          => $request->ip(),
            ]);
            return back()->withErrors(['recaptcha' => 'Verifikasi reCAPTCHA gagal atau kadaluarsa. Silakan coba lagi.'])->withInput();
        }

        // Registrasi publik selalu menghasilkan role 'user'
        // Pembuatan akun admin dilakukan lewat panel Admin > Settings > Users
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'nip'      => $request->filled('nip') ? $request->nip : null,
            'nip_hash' => $request->filled('nip') ? User::hashNip($request->nip) : null,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }
}
