<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\ValidEmailDomain;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRules;

class AuthController extends Controller
{
    /**
     * Реєстрація нового користувача
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'city' => $validated['city'] ?? null,
            'role' => 'user'
        ]);

        // Відправляємо лист для верифікації
        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        LoggingService::logError('User registered', [
            'userID' => $user->id,
            'email' => $user->email
        ]);

        return response()->json([
            'message' => 'Реєстрація успішна',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    /**
     * Автентифікація користувача
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Захист від брутфорсу - максимум 5 спроб
        $throttleKey = Str::transliterate(Str::lower($validated['email']).'|'.$request->ip());
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => ["Забагато спроб входу. Спробуйте через {$seconds} секунд."],
            ]);
        }

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            RateLimiter::hit($throttleKey);
            
            LoggingService::logError('Failed login attempt', [
                'email' => $validated['email'],
                'ip' => $request->ip()
            ]);
            
            throw ValidationException::withMessages([
                'email' => ['Невірні облікові дані.'],
            ]);
        }

        if (!$user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => ['Будь ласка, підтвердіть вашу електронну пошту.'],
            ]);
        }

        RateLimiter::clear($throttleKey);
        $token = $user->createToken('auth_token')->plainTextToken;

        LoggingService::logError('User logged in', [
            'userID' => $user->id,
            'email' => $user->email
        ]);

        return response()->json([
            'message' => 'Вхід успішний',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    /**
     * Вихід користувача
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        LoggingService::logError('User logged out', [
            'userID' => $request->user()->id
        ]);

        return response()->json(['message' => 'Вихід успішний']);
    }

    /**
     * Отримати поточного користувача
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Повторно надіслати лист для верифікації
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->email_verified_at) {
            return response()->json(['message' => 'Електронна пошта вже підтверджена.'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        LoggingService::logError('Verification email resent', [
            'userID' => $request->user()->id
        ]);

        return response()->json(['message' => 'Лист для підтвердження надіслано.']);
    }

    /**
     * Відправка листа для відновлення паролю
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        LoggingService::logError('Password reset email sent', [
            'email' => $request->email
        ]);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Лист для відновлення паролю надіслано'])
            : response()->json(['message' => 'Помилка при відправці листа'], 400);
    }

    /**
     * Відновлення паролю
     */
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
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        LoggingService::logError('Password reset', [
            'email' => $request->email
        ]);

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Пароль успішно змінено'])
            : response()->json(['message' => 'Помилка при зміні паролю'], 400);
    }
}
