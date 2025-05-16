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
            'email' => 'required|email|max:255|unique:users,email,' . ($this->user ? $this->user->id : 'NULL') . ',id',
            'password' => $this->isMethod('POST') ? 'required|min:8' : 'nullable|min:8',
            'role' => 'required|in:admin,user',

            // Необов'язкові поля
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255'
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
                'email.required' => 'Please enter your email address',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email address is already registered',
                'password.required' => 'Please enter a password',
                'password.min' => 'Password must be at least 8 characters long',
                'role.required' => 'Please select a role',
                'role.in' => 'Invalid role selected'
            ];
        }

        return [
            'name.required' => 'Будь ласка, введіть ваше ім\'я',
            'email.required' => 'Будь ласка, введіть вашу електронну адресу',
            'email.email' => 'Введіть коректну електронну адресу',
            'email.unique' => 'Ця електронна адреса вже зареєстрована',
            'password.required' => 'Будь ласка, введіть пароль',
            'password.min' => 'Пароль має містити мінімум 8 символів',
            'role.required' => 'Будь ласка, виберіть роль',
            'role.in' => 'Невірна роль'
        ];
    }
} 