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
    
        public function store(Request $request): RedirectResponse {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'nip'           => ['nullable', 'string', 'max:50', 'unique:'.User::class],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            'admin_code'    => ['nullable', 'string'],
        ]);

        // Cek secret code admin (sebelum hit Google API)
        $role = 'user';
        if ($request->filled('admin_code')) {
            if ($request->admin_code !== config('app.admin_secret_code')) {
                return back()->withErrors(['admin_code' => 'Kode admin tidak valid.']);
            }
            $role = 'admin';
        }

        // Verifikasi Google reCAPTCHA (paling akhir, setelah semua validasi lolos)
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

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'nip'      => $request->nip,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect berdasarkan role
        if ($user->isAdmin()) {
            // Jika admin tapi belum pilih role → redirect ke halaman pilih role
            if (!$user->hasSelectedRole()) {
                return redirect()->intended(route('admin.role.select'));
            }
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
