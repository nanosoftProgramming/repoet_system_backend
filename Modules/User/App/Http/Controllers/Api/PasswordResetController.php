<?php

namespace Modules\User\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Modules\User\App\Notifications\UserResetPassword;
use Modules\User\App\Http\Requests\ResetPasswordRequest;
use Modules\User\App\Http\Requests\ForgotPasswordRequest;

class PasswordResetController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $key = 'password-reset:' . $request->email;
            if (RateLimiter::tooManyAttempts($key, 5)) {
                return returnMessage(false, 'Too many password reset attempts. Please try again later.', null, 'too_many_requests');
            }
            $status = Password::broker('users')->sendResetLink(
                $request->only('email'),
                function ($user, $token) {
                    $user->notify(new UserResetPassword($token));
                }
            );

            RateLimiter::hit($key, 3600);

            if ($status === Password::RESET_LINK_SENT) {
                return returnMessage(true, 'Password reset link sent to your email', [
                    'email' => $request->email,
                ]);
            }

            return returnMessage(false, 'Unable to send reset link. Please try again.', null, 'server_error');

        } catch (\Exception $e) {
            return returnMessage(false, 'An error occurred. Please try again later.', null, 'server_error');
        }
    }

    public function validateResetToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email'
        ]);

        $tokenRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            return returnMessage(false, 'No reset token found for this email', null, 'unprocessable_entity');
        }

        $isExpired = now()->gt(\Carbon\Carbon::parse($tokenRecord->created_at)->addHour());

        if ($isExpired) {
            return returnMessage(false, 'Reset token has expired', null, 'unprocessable_entity');
        }

        $tokenMatches = \Hash::check($request->token, $tokenRecord->token);

        return returnMessage(true, $tokenMatches ? 'Valid reset token' : 'Invalid reset token', [
            'valid' => $tokenMatches,
            'email' => $request->email
        ], $tokenMatches ? 'ok' : 'unprocessable_entity');
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $status = Password::broker('users')->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password)
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return returnMessage(true, 'Password has been reset successfully', [
                    'email' => $request->email,
                    'redirect' => '/login'
                ]);

            }

            $message = match ($status) {
                Password::INVALID_TOKEN => 'This password reset token is invalid or has expired',
                Password::INVALID_USER => 'We can\'t find a user with that email address',
                default => 'Unable to reset password. Please try again'
            };

            return returnMessage(false, $message, null, 'unprocessable_entity');

        } catch (\Exception $e) {
            return returnMessage(false, 'An error occurred while resetting password. Please try again.', null, 'server_error');
        }
    }
}
