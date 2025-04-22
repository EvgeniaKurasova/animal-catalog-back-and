<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdoptionRequest extends FormRequest
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
            'animal_id' => 'required|integer', // ID тварини, яку хочуть усиновити
            'name' => 'required|string|max:255', // Ім'я людини, яка хоче усиновити
            'email' => 'required|email|max:255', // Email для зв'язку
            'phone' => 'required|string|max:20', // Телефон для зв'язку
            'address' => 'required|string|max:255', // Адреса проживання

            // Необов'язкові поля
            'message' => 'nullable|string', // Додаткове повідомлення

            // Службові поля
            'is_processed' => 'boolean', // Чи оброблено заявку
            'is_archived' => 'boolean' // Чи в архіві заявка
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
                // Error messages for required fields
                'name.required' => 'Please enter your name',
                'email.required' => 'Please enter your email address',
                'email.email' => 'Please enter a valid email address',
                'phone.required' => 'Please enter your phone number',
                'address.required' => 'Please enter your address'
            ];
        }

        return [
            // Повідомлення про помилки для обов'язкових полів
            'name.required' => 'Будь ласка, введіть ваше ім\'я',
            'email.required' => 'Будь ласка, введіть вашу електронну адресу',
            'email.email' => 'Введіть коректну електронну адресу',
            'phone.required' => 'Будь ласка, введіть ваш номер телефону',
            'address.required' => 'Будь ласка, введіть вашу адресу'
        ];
    }
} 