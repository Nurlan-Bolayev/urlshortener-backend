<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4'
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email already exists.',
            'password.min' => 'The password should be at least 4 characters long.',
        ]);

        $user = User::query()->forceCreate([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => \Hash::make($attrs['password'])
        ]);

        /** @var User $user */
        \Auth::login($user, true);
        event(new Registered($user));
        return $user;
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent'])
            : response()->json(['message' => 'Reset link could not be sent']);
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
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json([
                'message' => 'Password reset'
            ])
            : response()->json([
                'message' => 'Password could not be reset'
            ]);
    }

    public function showResetPasswordForm()
    {
        return redirect('http://localhost:8080/reset/password/');
    }

    public function login(Request $request)
    {
        $attrs = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:4'
        ], [
            'email.exists' => 'This email already exists.',
            'password.required' => 'The password field is required.'
        ]);

        if (\Auth::attempt($attrs)) {
            return \Auth::user();
        }

        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records']
        ]);
    }

    public function logout()
    {
        \Auth::logout();
        return 'Logged out';
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('http://localhost:8080/email/verified/');

    }
}
