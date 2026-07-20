<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'g-recaptcha-response' => ['required', 'string'],
        ], [
            'g-recaptcha-response.required' => 'Verifikasi keamanan gagal. Silakan muat ulang halaman.',
        ]);

        $this->validateRecaptcha($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }

    /**
     * Validate reCAPTCHA v3 token against Google API.
     */
    protected function validateRecaptcha(Request $request): void
    {
        $token = $request->input('g-recaptcha-response');
        $secret = config('services.recaptcha.secret');

        // Skip in local/testing environment if no secret configured
        if (app()->environment('testing') || empty($secret)) {
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            $success  = $result['success'] ?? false;
            $score    = $result['score'] ?? 0;
            $minScore = (float) config('services.recaptcha.min_score', 0.5);

            Log::info('Forgot Password reCAPTCHA v3 result', [
                'success' => $success,
                'score'   => $score,
                'ip'      => $request->ip(),
            ]);

            if (!$success || $score < $minScore) {
                throw ValidationException::withMessages([
                    'email' => 'Verifikasi keamanan gagal (skor terlalu rendah). Coba lagi.',
                ]);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::warning('Forgot Password reCAPTCHA request failed, skipping validation: ' . $e->getMessage());
        }
    }
}
