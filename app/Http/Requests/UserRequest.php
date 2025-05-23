<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Визначає, чи користувач має право на цей запит
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Отримати правила валідації для запиту
     */
    public function rules(): array
    {
        return [
            // Обов'язкові поля
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'gmail' => 'required|email|max:255|unique:users,gmail,' . ($this->user ? $this->user->userID : 'NULL') . ',userID',
            'password' => $this->isMethod('POST') ? 'required|min:8' : 'nullable|min:8',
            'isAdmin' => 'required|boolean',

            // Необов'язкові поля
            'code' => 'nullable|string|max:255',
            'gmail_verified_at' => 'nullable|date'
        ];
    }

    /**
     * Отримати повідомлення про помилки валідації
     */
    public function messages(): array
    {
        $locale = app()->getLocale();
        
        if ($locale === 'en') {
            return [
                'name.required' => 'Please enter your name',
                'lastname.required' => 'Please enter your last name',
                'phone_number.required' => 'Please enter your phone number',
                'gmail.required' => 'Please enter your email address',
                'gmail.email' => 'Please enter a valid email address',
                'gmail.unique' => 'This email address is already registered',
                'password.required' => 'Please enter a password',
                'password.min' => 'Password must be at least 8 characters long',
                'isAdmin.required' => 'Please specify if user is admin',
                'isAdmin.boolean' => 'Admin status must be true or false',
                'gmail_verified_at.date' => 'Invalid verification date format'
            ];
        }

        return [
            'name.required' => 'Будь ласка, введіть ваше ім\'я',
            'lastname.required' => 'Будь ласка, введіть ваше прізвище',
            'phone_number.required' => 'Будь ласка, введіть ваш номер телефону',
            'gmail.required' => 'Будь ласка, введіть вашу електронну адресу',
            'gmail.email' => 'Введіть коректну електронну адресу',
            'gmail.unique' => 'Ця електронна адреса вже зареєстрована',
            'password.required' => 'Будь ласка, введіть пароль',
            'password.min' => 'Пароль має містити мінімум 8 символів',
            'isAdmin.required' => 'Будь ласка, вкажіть чи користувач є адміністратором',
            'isAdmin.boolean' => 'Статус адміністратора має бути true або false',
            'gmail_verified_at.date' => 'Невірний формат дати верифікації'
        ];
    }
} 