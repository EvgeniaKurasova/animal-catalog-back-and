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
            'main_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Головне фото для сторінки

            // Необов'язкові поля
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Логотип притулку
            'facebook' => [
                'nullable',
                'url',
                'max:255',
                'regex:/^(https?:\/\/)?(www\.)?facebook\.com\/.+/i' // Перевірка на домен facebook.com
            ],
            'instagram' => [
                'nullable',
                'url',
                'max:255',
                'regex:/^(https?:\/\/)?(www\.)?instagram\.com\/.+/i' // Перевірка на домен instagram.com
            ]
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
                'main_photo.required' => 'Please upload a main photo for the page',
                'main_photo.image' => 'File must be an image',
                'main_photo.mimes' => 'Supported formats: jpeg, png, jpg, gif',
                'main_photo.max' => 'Image size cannot exceed 2MB',

                // Error messages for optional fields
                'logo.image' => 'Logo must be an image',
                'logo.mimes' => 'Supported formats: jpeg, png, jpg, gif',
                'logo.max' => 'Logo size cannot exceed 2MB',
                'facebook.url' => 'Please enter a valid Facebook URL',
                'facebook.regex' => 'Please enter a valid Facebook profile URL',
                'instagram.url' => 'Please enter a valid Instagram URL',
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
            'main_photo.required' => 'Будь ласка, завантажте головне фото для сторінки',
            'main_photo.image' => 'Файл має бути зображенням',
            'main_photo.mimes' => 'Підтримуються тільки формати: jpeg, png, jpg, gif',
            'main_photo.max' => 'Розмір зображення не може перевищувати 2MB',

            // Повідомлення про помилки для необов'язкових полів
            'logo.image' => 'Логотип має бути зображенням',
            'logo.mimes' => 'Підтримуються тільки формати: jpeg, png, jpg, gif',
            'logo.max' => 'Розмір логотипу не може перевищувати 2MB',
            'facebook.url' => 'Введіть коректне посилання на Facebook',
            'facebook.regex' => 'Введіть коректне посилання на профіль Facebook',
            'instagram.url' => 'Введіть коректне посилання на Instagram',
            'instagram.regex' => 'Введіть коректне посилання на профіль Instagram'
        ];
    }
} 