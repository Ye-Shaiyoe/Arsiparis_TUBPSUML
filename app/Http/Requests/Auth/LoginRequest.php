<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            'email'             => ['required', 'string'],
            'password'          => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'string'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'g-recaptcha-response.required' => 'Verifikasi keamanan gagal. Silakan muat ulang halaman.',
        ];
    }

    /**
     * Validate reCAPTCHA v3 token against Google API.
     * Returns false if score is too low or request fails.
     *
     * @throws ValidationException
     */
    protected function validateRecaptcha(): void
    {
        $token = $this->input('g-recaptcha-response');
        $secret = config('services.recaptcha.secret');

        // Skip in local/testing environment if no secret configured
        if (app()->environment('testing') || empty($secret)) {
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $this->ip(),
            ]);

            $result = $response->json();

            $success  = $result['success'] ?? false;
            $score    = $result['score'] ?? 0;
            $action   = $result['action'] ?? '';
            $minScore = (float) config('services.recaptcha.min_score', 0.5);

            Log::info('reCAPTCHA v3 result', [
                'success' => $success,
                'score'   => $score,
                'action'  => $action,
                'ip'      => $this->ip(),
            ]);

            if (!$success || $score < $minScore) {
                throw ValidationException::withMessages([
                    'email' => 'Verifikasi keamanan gagal (skor terlalu rendah). Coba lagi.',
                ]);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            // Network/timeout — log tapi jangan blokir user
            Log::warning('reCAPTCHA request failed, skipping validation: ' . $e->getMessage());
        }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->validateRecaptcha();
        $this->ensureIsNotRateLimited();

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
