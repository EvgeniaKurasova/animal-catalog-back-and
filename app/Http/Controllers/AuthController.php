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

class AuthController extends Controller
{
    /**
     * Реєстрація нового користувача
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'gmail' => ['required', 'string', 'email', 'max:255', 'unique:users', new ValidEmailDomain],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'gmail' => $request->gmail,
            'password' => Hash::make($request->password),
        ]);

        // Відправляємо лист для верифікації
        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        LoggingService::logError('User registered', [
            'userID' => $user->id,
            'email' => $user->gmail
        ]);

        return response()->json([
            'message' => 'Реєстрація успішна. Будь ласка, перевірте вашу електронну пошту для підтвердження.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Автентифікація користувача
     */
    public function login(Request $request)
    {
        $request->validate([
            'gmail' => 'required|email',
            'password' => 'required',
        ]);

        // Перевірка обмеження спроб входу
        $throttleKey = Str::transliterate(Str::lower($request->gmail).'|'.$request->ip());
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'gmail' => ["Забагато спроб входу. Спробуйте через {$seconds} секунд."],
            ]);
        }

        $user = User::where('gmail', $request->gmail)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey);
            
            LoggingService::logError('Failed login attempt', [
                'email' => $request->gmail,
                'ip' => $request->ip()
            ]);
            
            throw ValidationException::withMessages([
                'gmail' => ['Невірні облікові дані.'],
            ]);
        }

        if (!$user->gmail_verified_at) {
            throw ValidationException::withMessages([
                'gmail' => ['Будь ласка, підтвердіть вашу електронну пошту.'],
            ]);
        }

        RateLimiter::clear($throttleKey);
        $token = $user->createToken('auth_token')->plainTextToken;

        LoggingService::logError('User logged in', [
            'userID' => $user->id,
            'email' => $user->gmail
        ]);

        return response()->json([
            'user' => $user,
            'token' => $token,
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

        return response()->json(['message' => 'Успішний вихід']);
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
        if ($request->user()->gmail_verified_at) {
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
            'gmail' => 'required|email|exists:users,gmail'
        ]);

        $status = Password::sendResetLink(
            $request->only('gmail')
        );

        LoggingService::logError('Password reset email sent', [
            'email' => $request->gmail
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
            'gmail' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('gmail', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        LoggingService::logError('Password reset', [
            'email' => $request->gmail
        ]);

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Пароль успішно змінено'])
            : response()->json(['message' => 'Помилка при зміні паролю'], 400);
    }
}
