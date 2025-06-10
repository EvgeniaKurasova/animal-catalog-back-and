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
            'email' => 'required|email|max:255|unique:users,email,' . ($this->user ? $this->user->user_id : 'NULL') . ',user_id',
            'password' => $this->isMethod('POST') ? 'required|min:8' : 'nullable|min:8',
            'isAdmin' => 'required|boolean',

            // Необов'язкові поля
            'code' => 'nullable|string|max:255',
            'email_verified_at' => 'nullable|date'
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
                'email.required' => 'Please enter your email address',
                'email.email' => 'Please enter a valid email address',
                'gmail.unique' => 'This email address is already registered',
                'password.required' => 'Please enter a password',
                'password.min' => 'Password must be at least 8 characters long',
                'isAdmin.required' => 'Please specify if user is admin',
                'isAdmin.boolean' => 'Admin status must be true or false',
                'email_verified_at.date' => 'Invalid verification date format'
            ];
        }

        return [
            'name.required' => 'Будь ласка, введіть ваше ім\'я',
            'lastname.required' => 'Будь ласка, введіть ваше прізвище',
            'phone_number.required' => 'Будь ласка, введіть ваш номер телефону',
            'email.required' => 'Будь ласка, введіть вашу електронну адресу',
            'email.email' => 'Введіть коректну електронну адресу',
            'email.unique' => 'Ця електронна адреса вже зареєстрована',
            'password.required' => 'Будь ласка, введіть пароль',
            'password.min' => 'Пароль має містити мінімум 8 символів',
            'isAdmin.required' => 'Будь ласка, вкажіть чи користувач є адміністратором',
            'isAdmin.boolean' => 'Статус адміністратора має бути true або false',
            'email_verified_at.date' => 'Невірний формат дати верифікації'
        ];
    }
} 