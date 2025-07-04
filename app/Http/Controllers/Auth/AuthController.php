<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;

use App\Helpers\ActivityLogger;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;



class AuthController extends Controller
{
    public function showManagementLogin()
    {
        return view('auth.login');
    }

    public function loginToManagement(Request $request)
    {
        $request->validate([
            'emailOrPhone' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->emailOrPhone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginField => $request->emailOrPhone,
            'password' => $request->password,
        ];

        $remember = $request->boolean('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            $request->session()->regenerate();

            ActivityLogger::log('Login', 'User logged in successfully');

            return redirect()->intended(route('management.dashboard'));
        }

        return back()->withErrors([
            'status' => 'Incorrect email, phone or password. Please try again.',
        ])->withInput();
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgotPassword');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login.management')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
    
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function logoutFromManagement(Request $request): RedirectResponse
    {
        ActivityLogger::log('Logout', 'User logged out successfully');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.resetPassword', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function submitResetForm(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.management')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
