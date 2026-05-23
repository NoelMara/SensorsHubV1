<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerifyEmailWithCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login', ['loginMode' => 'default']);
    }

    public function showSuperAdminLoginForm()
    {
        return view('auth.login', ['loginMode' => 'super_admin']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'login:' . $request->ip();

        // Check if IP is locked out
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Block super admin from using normal login
            if ($user->isSuperAdmin()) {
                return back()->withErrors([
                    'email' => 'These credentials do not match our records.',
                ])->onlyInput('email');
            }

            // Clear login rate limit on success
            RateLimiter::clear($key);

            // If email not verified, send verification code
            if (!$user->email_verified_at) {
                $code = $user->generateVerificationCode();
                $user->notify(new VerifyEmailWithCode($code));

                return redirect()->back()->with([
                    'require_verification' => true,
                    'user_email' => $user->email,
                ]);
            }

            return $this->completeLogin($request, $user);
        }

        // Failed — increment login attempt counter
        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function superAdminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'login:' . $request->ip();

        // Check if IP is locked out
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->isSuperAdmin() || !Hash::check($credentials['password'], $user->password)) {
            // Failed — increment counter
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'email' => 'The provided super admin credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Clear login rate limit on success
        RateLimiter::clear($key);

        if (!$user->email_verified_at) {
            $code = $user->generateVerificationCode();
            $user->notify(new VerifyEmailWithCode($code));

            return redirect()->back()->with([
                'require_verification' => true,
                'user_email' => $user->email,
            ]);
        }

        return $this->completeLogin($request, $user);
    }

    /**
     * Verify the code and complete login
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'verification_code' => ['required', 'string', 'size:6'],
        ]);

        $key = 'verify-code:' . $request->ip();

        // Check if IP is locked out
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'verification_code' => "Too many attempts. Please try again in {$seconds} seconds.",
            ])->with([
                'require_verification' => true,
                'user_email' => $request->email,
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'User not found.',
            ])->withInput();
        }

        if ($user->verifyCode($request->verification_code)) {
            // Clear verify rate limit on success
            RateLimiter::clear($key);

            $user->clearVerificationCode();

            return $this->completeLogin($request, $user);
        }

        // Failed — increment verify attempt counter
        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'verification_code' => 'Invalid or expired verification code.',
        ])->with([
            'require_verification' => true,
            'user_email' => $request->email,
        ]);
    }

    /**
     * Resend verification code during login
     */
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $key = 'resend-code:' . $request->ip() . ':' . $request->email;

        // Check if IP is locked out
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many resend attempts. Please try again in {$seconds} seconds.",
            ])->with([
                'require_verification' => true,
                'user_email' => $request->email,
            ]);
        }

        // Every resend counts regardless of success or fail
        RateLimiter::hit($key, 60);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $code = $user->generateVerificationCode();
            $user->notify(new VerifyEmailWithCode($code));
        }

        return back()->with([
            'require_verification' => true,
            'user_email' => $request->email,
            'message' => 'A new verification code has been sent to your email!',
        ]);
    }

    public function logout(Request $request)
    {
        $redirectRoute = Auth::user()?->isSuperAdmin() ? 'super-admin.login' : 'login';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute);
    }

    private function completeLogin(Request $request, User $user)
    {
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard.index');
    }
}