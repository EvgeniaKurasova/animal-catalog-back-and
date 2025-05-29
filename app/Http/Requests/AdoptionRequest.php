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
            'animal_id' => 'required|exists:animals,animal_id',
            'animal_name' => 'required|exists:animals,name',
            'user_id' => 'required|exists:users, user_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'message' => 'nullable|text',
            'city' => 'nullable|string|max:255',
            'is_processed' => 'required|boolean',
            'is_archived' => 'required|boolean',
            'comment' => 'nullable|text',
            'is_viewed' => 'required|boolean',
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
                'animal_id.required' => 'Please select an animal',
                'animal_id.exists' => 'Selected animal does not exist',
                'first_name.required' => 'Please enter your first name',
                'last_name.required' => 'Please enter your last name',
                'phone.required' => 'Please enter your phone number',
                'email.required' => 'Please enter your email address',
                'email.email' => 'Please enter a valid email address',
                'city.required' => 'Please enter your city',
            ];
        }

        return [
            // Повідомлення про помилки для обов'язкових полів
            'animal_id.required' => 'Будь ласка, виберіть тварину',
            'animal_id.exists' => 'Вибрана тварина не існує',
            'first_name.required' => 'Будь ласка, введіть ваше ім\'я',
            'last_name.required' => 'Будь ласка, введіть ваше прізвище',
            'phone.required' => 'Будь ласка, введіть ваш номер телефону',
            'email.required' => 'Будь ласка, введіть вашу електронну адресу',
            'email.email' => 'Введіть коректну електронну адресу',
            'city.required' => 'Будь ласка, введіть ваше місто',
        ];
    }

    public function attributes()
    {
        return [
            'animal_id' => 'Тварина',
            'animal_name' => 'Ім\'я тварини',
            'user_id' => 'Користувач',
            'first_name' => 'Ім\'я',
            'last_name' => 'Прізвище',
            'phone' => 'Номер телефону',
            'email' => 'Електронна адреса',
            'message' => 'повідомлення',
            'city' => 'Місто',
            'is_processed' => 'Оброблено',
            'is_archived' => 'Архівовано',
            'comment' => 'Коментар',
            'is_viewed' => 'Переглянуто',
        ];
    }
} 