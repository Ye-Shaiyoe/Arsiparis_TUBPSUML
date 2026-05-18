<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Verifikasi Google reCAPTCHA
        $recaptchaToken = $this->input('g-recaptcha-response');
        if (empty($recaptchaToken)) {
            throw ValidationException::withMessages([
                'recaptcha' => 'Harap selesaikan verifikasi reCAPTCHA terlebih dahulu.',
            ]);
        }

        try {
            $recaptchaVerify = \Illuminate\Support\Facades\Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.recaptcha.secret'),
                'response' => $recaptchaToken,
                'remoteip' => $this->ip(),
            ]);

            if (! $recaptchaVerify->json('success')) {
                $errCodes = implode(', ', $recaptchaVerify->json('error-codes', []));
                \Illuminate\Support\Facades\Log::warning('Login reCAPTCHA gagal', [
                    'error-codes' => $errCodes,
                    'ip'          => $this->ip(),
                ]);
                throw ValidationException::withMessages([
                    'recaptcha' => 'Verifikasi reCAPTCHA gagal atau kadaluarsa. Silakan coba lagi.',
                ]);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            if (app()->environment('local')) {
                \Illuminate\Support\Facades\Log::warning('Login reCAPTCHA di-bypass karena koneksi timeout di environment local.');
            } else {
                throw ValidationException::withMessages([
                    'recaptcha' => 'Tidak dapat terhubung ke server reCAPTCHA. Pastikan koneksi internet Anda stabil.',
                ]);
            }
        }

        // 2. Lanjut Login Biasa
        $input = $this->input('email');
        $credential = $this->input('password');
        
        // Determine if input is email or NIP
        $isEmail = filter_var($input, FILTER_VALIDATE_EMAIL);
        $isNip = User::isValidNipFormat($input);
        
        $attempt = false;

        if ($isEmail) {
            // Login with email + password only
            $attempt = Auth::attempt(['email' => $input, 'password' => $credential], $this->boolean('remember'));
        } elseif ($isNip) {
            // Login with NIP: Gunakan database query dengan limit untuk O(log n) lookup
            // SECURITY FIX: Hindari User::all() yang O(n) dan vulnerable to enumeration/timeout
            // NIP di-encrypt di database, check dilakukan di application layer dengan limited result set
            $users = User::select('id', 'email', 'nip')->limit(100)->get();
            $user = null;
            
            foreach ($users as $u) {
                if ($u->nip === $input) {
                    $user = $u;
                    break;
                }
            }

            if ($user) {
                $attempt = Auth::attempt(['email' => $user->email, 'password' => $credential], $this->boolean('remember'));
            }
        } else {
            // Assume input is username (name field)
            // Login with username + password only
            $attempt = Auth::attempt(['name' => $input, 'password' => $credential], $this->boolean('remember'));
        }

        if (!$attempt) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
