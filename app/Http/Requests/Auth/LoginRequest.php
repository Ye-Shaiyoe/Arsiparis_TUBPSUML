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
            // Login with NIP: Since NIP is encrypted, we can't query it directly with Auth::attempt.
            // We search for the user manually.
            $user = User::all()->filter(function($u) use ($input) {
                return $u->nip === $input;
            })->first();

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
