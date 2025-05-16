<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\ValidEmailDomain;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;

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

        $user = User::where('gmail', $request->gmail)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'gmail' => ['Невірні облікові дані.'],
            ]);
        }

        if (!$user->gmail_verified_at) {
            throw ValidationException::withMessages([
                'gmail' => ['Будь ласка, підтвердіть вашу електронну пошту.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

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

        return response()->json(['message' => 'Лист для підтвердження надіслано.']);
    }
}
