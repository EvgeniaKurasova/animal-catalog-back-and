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
            'address' => 'required|string|max:255', // Адреса притулку українською
            'address_en' => 'required|string|max:255', // Адреса притулку англійською
            'phone' => 'required|string|max:20', // Телефон притулку
            'email' => 'required|email|max:255', // Email притулку
            'working_hours' => 'required|string|max:255', // Графік роботи українською
            'working_hours_en' => 'required|string|max:255', // Графік роботи англійською

            // Необов'язкові поля
            'description' => 'nullable|string', // Опис притулку українською
            'description_en' => 'nullable|string', // Опис притулку англійською
            'facebook' => 'nullable|url|max:255', // Посилання на Facebook
            'instagram' => 'nullable|url|max:255', // Посилання на Instagram
            'website' => 'nullable|url|max:255', // Посилання на веб-сайт
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024' // Логотип притулку
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
                'address.required' => 'Please enter the shelter address',
                'address_en.required' => 'Please enter the shelter address in English',
                'phone.required' => 'Please enter the shelter phone number',
                'email.required' => 'Please enter the shelter email address',
                'email.email' => 'Please enter a valid email address',
                'working_hours.required' => 'Please enter the working hours',
                'working_hours_en.required' => 'Please enter the working hours in English',

                // Error messages for optional fields
                'facebook.url' => 'Please enter a valid Facebook URL',
                'instagram.url' => 'Please enter a valid Instagram URL',
                'website.url' => 'Please enter a valid website URL',
                'logo.image' => 'File must be an image',
                'logo.mimes' => 'Supported formats: jpeg, png, jpg, gif',
                'logo.max' => 'Image size cannot exceed 1MB'
            ];
        }

        return [
            // Повідомлення про помилки для обов'язкових полів
            'name.required' => 'Будь ласка, введіть назву притулку',
            'name_en.required' => 'Будь ласка, введіть назву притулку англійською',
            'address.required' => 'Будь ласка, введіть адресу притулку',
            'address_en.required' => 'Будь ласка, введіть адресу притулку англійською',
            'phone.required' => 'Будь ласка, введіть номер телефону притулку',
            'email.required' => 'Будь ласка, введіть електронну адресу притулку',
            'email.email' => 'Введіть коректну електронну адресу',
            'working_hours.required' => 'Будь ласка, введіть графік роботи',
            'working_hours_en.required' => 'Будь ласка, введіть графік роботи англійською',

            // Повідомлення про помилки для необов'язкових полів
            'facebook.url' => 'Введіть коректне посилання на Facebook',
            'instagram.url' => 'Введіть коректне посилання на Instagram',
            'website.url' => 'Введіть коректне посилання на веб-сайт',
            'logo.image' => 'Файл має бути зображенням',
            'logo.mimes' => 'Підтримуються тільки формати: jpeg, png, jpg, gif',
            'logo.max' => 'Розмір зображення не може перевищувати 1MB'
        ];
    }
} 