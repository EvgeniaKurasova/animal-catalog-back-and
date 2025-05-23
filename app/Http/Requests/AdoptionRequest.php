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
            'animalID' => 'required|integer|exists:animals,animalID', // ID тварини
            'animal_name' => 'required|string|max:255', // Ім'я тварини
            'first_name' => 'required|string|max:255', // Ім'я
            'last_name' => 'required|string|max:255', // Прізвище
            'phone' => 'required|string|max:20', // Телефон
            'gmail' => 'required|email|max:255', // Email

            // Необов'язкові поля
            'city' => 'nullable|string|max:255',
            'message' => 'nullable|string', // Повідомлення
            'is_processed' => 'boolean', // Чи оброблено заявку
            'is_archived' => 'boolean', // Чи в архіві заявка
            'is_viewed' => 'boolean', // Чи переглянуто заявку
            'comment' => 'nullable|string' // Коментар адміністратора
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
                'animalID.required' => 'Please select an animal',
                'animalID.exists' => 'Selected animal does not exist',
                'animal_name.required' => 'Animal name is required',
                'first_name.required' => 'Please enter your first name',
                'last_name.required' => 'Please enter your last name',
                'phone.required' => 'Please enter your phone number',
                'gmail.required' => 'Please enter your email address',
                'gmail.email' => 'Please enter a valid email address',
                'city.required' => 'Please enter your city'
            ];
        }

        return [
            // Повідомлення про помилки для обов'язкових полів
            'animalID.required' => 'Будь ласка, виберіть тварину',
            'animalID.exists' => 'Вибрана тварина не існує',
            'animal_name.required' => 'Ім\'я тварини обов\'язкове',
            'first_name.required' => 'Будь ласка, введіть ваше ім\'я',
            'last_name.required' => 'Будь ласка, введіть ваше прізвище',
            'phone.required' => 'Будь ласка, введіть ваш номер телефону',
            'gmail.required' => 'Будь ласка, введіть вашу електронну адресу',
            'gmail.email' => 'Введіть коректну електронну адресу',
            'city.required' => 'Будь ласка, введіть ваше місто'
        ];
    }
} 