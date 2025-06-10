<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShelterInfoRequest extends FormRequest
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
            'name' => 'required|string|max:255', // Назва притулку українською
            'name_en' => 'required|string|max:255', // Назва притулку англійською
            'phone' => 'required|string|max:20', // Телефон притулку
            'email' => 'required|email|max:255', // Email притулку
            'description' => 'required|string', // Опис притулку українською
            'description_en' => 'required|string', // Опис притулку англійською
            'main_photo' => 'required|string', // Головне фото для сторінки
            'rule_id' => 'required|integer|exists:adoption_rules,rule_id', // ID правил усиновлення

            // Необов'язкові поля
            'logo' => 'nullable|string',
            'facebook' => [
                'nullable',
                'string',
                'regex:/^(https?:\/\/)?(www\.)?facebook\.com\/.+/i' // Перевірка на домен facebook.com
            ],
            'instagram' => [
                'nullable',
                'string',
                'regex:/^(https?:\/\/)?(www\.)?instagram\.com\/.+/i' // Перевірка на домен instagram.com
            ],
            'short_description' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'about_photo' => 'nullable|string',
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
                'name.required' => 'Please enter the shelter name',
                'name_en.required' => 'Please enter the shelter name in English',
                'phone.required' => 'Please enter the shelter phone number',
                'email.required' => 'Please enter the shelter email address',
                'email.email' => 'Please enter a valid email address',
                'description.required' => 'Please enter the shelter description',
                'description_en.required' => 'Please enter the shelter description in English',
                'main_photo.required' => 'Please provide a main photo for the page',
                'rule_id.required' => 'Please select adoption rules',
                'rule_id.exists' => 'Selected adoption rules do not exist',

                // Error messages for optional fields
                'facebook.regex' => 'Please enter a valid Facebook profile URL',
                'instagram.regex' => 'Please enter a valid Instagram profile URL'
            ];
        }

        return [
            // Повідомлення про помилки для обов'язкових полів
            'name.required' => 'Будь ласка, введіть назву притулку',
            'name_en.required' => 'Будь ласка, введіть назву притулку англійською',
            'phone.required' => 'Будь ласка, введіть номер телефону притулку',
            'email.required' => 'Будь ласка, введіть електронну адресу притулку',
            'email.email' => 'Введіть коректну електронну адресу',
            'description.required' => 'Будь ласка, введіть опис притулку',
            'description_en.required' => 'Будь ласка, введіть опис притулку англійською',
            'main_photo.required' => 'Будь ласка, вкажіть головне фото для сторінки',
            'rule_id.required' => 'Будь ласка, виберіть правила усиновлення',
            'rule_id.exists' => 'Вибрані правила усиновлення не існують',

            // Повідомлення про помилки для необов'язкових полів
            'facebook.regex' => 'Введіть коректне посилання на профіль Facebook',
            'instagram.regex' => 'Введіть коректне посилання на профіль Instagram'
        ];
    }
} 